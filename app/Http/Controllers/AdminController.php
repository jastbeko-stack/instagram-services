<?php

namespace App\Http\Controllers;

use App\Models\CryptoDeposit;
use App\Models\InstagramUsername;
use App\Models\Setting;
use App\Models\SmmCategory;
use App\Models\SmmOrder;
use App\Models\SmmProvider;
use App\Models\SmmService;
use App\Models\User;
use App\Models\UsernameOrder;
use App\Services\SmmApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected SmmApiService $smmApi;

    public function __construct(SmmApiService $smmApi)
    {
        $this->smmApi = $smmApi;
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('is_admin', false)->count(),
            'total_balances' => User::sum('balance'),
            'usernames_available' => InstagramUsername::where('status', 'available')->count(),
            'usernames_sold' => InstagramUsername::where('status', 'sold')->count(),
            'total_username_revenue' => UsernameOrder::sum('price'),
            'smm_orders_count' => SmmOrder::count(),
            'smm_orders_pending' => SmmOrder::where('status', 'pending')->count(),
            'smm_revenue' => SmmOrder::sum('charge'),
            'pending_deposits_count' => CryptoDeposit::where('status', 'pending')->count(),
            'pending_deposits_amount' => CryptoDeposit::where('status', 'pending')->sum('amount_usd'),
        ];

        $recentOrders = SmmOrder::with(['user', 'service'])->latest()->take(6)->get();
        $recentPurchases = UsernameOrder::with(['user', 'username'])->latest()->take(6)->get();
        $pendingDeposits = CryptoDeposit::with('user')->where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentPurchases', 'pendingDeposits'));
    }

    // ==========================================
    // 1. INSTAGRAM USERNAMES MANAGEMENT
    // ==========================================
    public function usernamesIndex(Request $request)
    {
        $query = InstagramUsername::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . ltrim($request->search, '@') . '%');
        }

        $usernames = $query->latest()->paginate(15)->withQueryString();

        return view('admin.usernames.index', compact('usernames'));
    }

    public function usernameCreate()
    {
        return view('admin.usernames.create');
    }

    public function usernameStore(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'unique:instagram_usernames', 'max:100'],
            'type' => ['required', 'string'],
            'followers_count' => ['nullable', 'integer', 'min:0'],
            'creation_year' => ['nullable', 'string', 'max:20'],
            'price' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],
            'delivery_info' => ['required', 'string'],
        ]);

        $validated['username'] = ltrim($validated['username'], '@');
        $validated['status'] = 'available';

        InstagramUsername::create($validated);

        return redirect()->route('admin.usernames.index')->with('success', "تمت إضافة اليوزر @{$validated['username']} بنجاح!");
    }

    public function usernameEdit($id)
    {
        $username = InstagramUsername::findOrFail($id);
        return view('admin.usernames.edit', compact('username'));
    }

    public function usernameUpdate(Request $request, $id)
    {
        $username = InstagramUsername::findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', 'string', "unique:instagram_usernames,username,{$id}", 'max:100'],
            'type' => ['required', 'string'],
            'followers_count' => ['nullable', 'integer', 'min:0'],
            'creation_year' => ['nullable', 'string', 'max:20'],
            'price' => ['required', 'numeric', 'min:1'],
            'status' => ['required', 'in:available,reserved,sold'],
            'description' => ['nullable', 'string'],
            'delivery_info' => ['required', 'string'],
        ]);

        $validated['username'] = ltrim($validated['username'], '@');

        $username->update($validated);

        return redirect()->route('admin.usernames.index')->with('success', 'تم تحديث بيانات اليوزر بنجاح!');
    }

    public function usernameDestroy($id)
    {
        $username = InstagramUsername::findOrFail($id);
        $username->delete();

        return back()->with('success', 'تم حذف اليوزر بنجاح.');
    }

    // ==========================================
    // 2. SMM SERVICES & CATEGORIES MANAGEMENT
    // ==========================================
    public function categoriesIndex()
    {
        $categories = SmmCategory::withCount('allServices')->orderBy('sort_order')->get();
        return view('admin.smm.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        SmmCategory::create($validated);

        return back()->with('success', 'تمت إضافة التصنيف بنجاح!');
    }

    public function categoryDestroy($id)
    {
        $category = SmmCategory::findOrFail($id);
        $category->delete();

        return back()->with('success', 'تم حذف التصنيف وجميع خدماته.');
    }

    public function servicesIndex()
    {
        $services = SmmService::with(['category', 'provider'])->latest()->paginate(20);
        $categories = SmmCategory::where('status', true)->get();
        $providers = SmmProvider::where('status', true)->get();

        return view('admin.smm.services', compact('services', 'categories', 'providers'));
    }

    public function serviceStore(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:smm_categories,id'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'price_per_1k' => ['required', 'numeric', 'min:0.0001'],
            'min_quantity' => ['required', 'integer', 'min:1'],
            'max_quantity' => ['required', 'integer', 'gt:min_quantity'],
            'execution_type' => ['required', 'in:api,manual'],
            'provider_id' => ['nullable', 'required_if:execution_type,api', 'exists:smm_providers,id'],
            'provider_service_id' => ['nullable', 'required_if:execution_type,api', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        SmmService::create($validated);

        return back()->with('success', 'تمت إضافة الخدمة بنجاح!');
    }

    public function serviceUpdate(Request $request, $id)
    {
        $service = SmmService::findOrFail($id);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:smm_categories,id'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'price_per_1k' => ['required', 'numeric', 'min:0.0001'],
            'min_quantity' => ['required', 'integer', 'min:1'],
            'max_quantity' => ['required', 'integer', 'gt:min_quantity'],
            'execution_type' => ['required', 'in:api,manual'],
            'provider_id' => ['nullable', 'required_if:execution_type,api'],
            'provider_service_id' => ['nullable', 'required_if:execution_type,api', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $service->update($validated);

        return back()->with('success', 'تم تحديث الخدمة بنجاح!');
    }

    public function serviceDestroy($id)
    {
        $service = SmmService::findOrFail($id);
        $service->delete();

        return back()->with('success', 'تم حذف الخدمة بنجاح.');
    }

    // ==========================================
    // 3. SMM PROVIDERS
    // ==========================================
    public function providersIndex()
    {
        $providers = SmmProvider::withCount(['services', 'orders'])->get();
        return view('admin.smm.providers', compact('providers'));
    }

    public function providerStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'api_url' => ['required', 'url', 'max:255'],
            'api_key' => ['required', 'string', 'max:255'],
        ]);

        $provider = SmmProvider::create($validated);

        // Fetch balance right away
        $balanceRes = $this->smmApi->getProviderBalance($provider);
        if ($balanceRes['success']) {
            $provider->balance = $balanceRes['balance'];
            $provider->currency = $balanceRes['currency'];
            $provider->save();
        }

        return back()->with('success', 'تمت إضافة مزود SMM بنجاح والتحقق من الاتصال!');
    }

    public function providerSyncBalance($id)
    {
        $provider = SmmProvider::findOrFail($id);
        $res = $this->smmApi->getProviderBalance($provider);

        if ($res['success']) {
            $provider->balance = $res['balance'];
            $provider->currency = $res['currency'];
            $provider->save();
            return back()->with('success', "تم تحديث رصيد المزود بنجاح: {$provider->balance} {$provider->currency}");
        }

        return back()->with('error', 'فشل قراءة رصيد المزود: ' . ($res['error'] ?? 'خطأ غير معروف'));
    }

    public function providerDestroy($id)
    {
        $provider = SmmProvider::findOrFail($id);
        $provider->delete();

        return back()->with('success', 'تم حذف المزود بنجاح.');
    }

    // ==========================================
    // 4. SMM ORDERS MANAGEMENT
    // ==========================================
    public function smmOrdersIndex(Request $request)
    {
        $query = SmmOrder::with(['user', 'service', 'provider']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('execution_type')) {
            $query->where('execution_type', $request->execution_type);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('link', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.smm.orders', compact('orders'));
    }

    public function smmOrderUpdateStatus(Request $request, $id)
    {
        $order = SmmOrder::with('user')->findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed,partial,canceled'],
            'start_count' => ['nullable', 'integer'],
            'remains' => ['nullable', 'integer'],
            'admin_notes' => ['nullable', 'string'],
            'refund_balance' => ['nullable', 'boolean'],
        ]);

        if ($validated['status'] === 'canceled' && ($request->boolean('refund_balance') || $order->status !== 'canceled')) {
            // Refund to user
            $order->user->addBalance(
                $order->charge,
                "استرجاع تكلفة طلب المتابعين الملغي #{$order->order_number}",
                $order->order_number,
                'refund'
            );
        }

        $order->status = $validated['status'];
        if (isset($validated['start_count'])) $order->start_count = $validated['start_count'];
        if (isset($validated['remains'])) $order->remains = $validated['remains'];
        if (isset($validated['admin_notes'])) $order->admin_notes = $validated['admin_notes'];
        $order->save();

        return back()->with('success', "تم تحديث حالة الطلب #{$order->order_number} بنجاح!");
    }

    public function smmOrderRetryApi($id)
    {
        $order = SmmOrder::with(['service.provider'])->findOrFail($id);
        $service = $order->service;

        if (!$service || !$service->provider || !$service->provider_service_id) {
            return back()->with('error', 'هذه الخدمة غير مربوطة بمزود تلقائي صالح.');
        }

        $res = $this->smmApi->placeOrder(
            $service->provider,
            $service->provider_service_id,
            $order->link,
            $order->quantity
        );

        if ($res['success']) {
            $order->provider_id = $service->provider->id;
            $order->provider_order_id = $res['order_id'];
            $order->status = 'in_progress';
            $order->admin_notes = 'تم إرسال الطلب بنجاح عبر API المزود.';
            $order->save();

            return back()->with('success', "تم إرسال الطلب للمزود بنجاح برقم #{$res['order_id']}");
        }

        return back()->with('error', 'فشل الربط: ' . ($res['error'] ?? 'خطأ مجهول'));
    }

    // ==========================================
    // 5. CRYPTO DEPOSITS (USDT)
    // ==========================================
    public function depositsIndex(Request $request)
    {
        $query = CryptoDeposit::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deposits = $query->latest()->paginate(15)->withQueryString();

        return view('admin.deposits.index', compact('deposits'));
    }

    public function depositApprove($id)
    {
        $deposit = CryptoDeposit::with('user')->findOrFail($id);

        if ($deposit->status !== 'pending') {
            return back()->with('error', 'هذا الطلب تم تدقيقه مسبقاً.');
        }

        DB::transaction(function () use ($deposit) {
            $deposit->status = 'approved';
            $deposit->approved_at = now();
            $deposit->save();

            // Add balance to user
            $deposit->user->addBalance(
                $deposit->amount_usd,
                "إيداع رصيد USDT ({$deposit->network}) - كود العملية #{$deposit->deposit_code}",
                $deposit->deposit_code,
                'deposit'
            );
        });

        return back()->with('success', "تم قبول الإيداع وإضافة \${$deposit->amount_usd} إلى محفظة المستخدم {$deposit->user->name} بنجاح!");
    }

    public function depositReject(Request $request, $id)
    {
        $deposit = CryptoDeposit::findOrFail($id);

        if ($deposit->status !== 'pending') {
            return back()->with('error', 'هذا الطلب تم تدقيقه مسبقاً.');
        }

        $deposit->status = 'rejected';
        $deposit->admin_notes = $request->input('reason', 'تم رفض الطلب لعدم مطابقة كود المعاملة أو عدم وصول التحويل.');
        $deposit->save();

        return back()->with('success', 'تم رفض طلب الإيداع.');
    }

    // ==========================================
    // 6. USERS MANAGEMENT
    // ==========================================
    public function usersIndex(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('telegram', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function userAdjustBalance(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'action' => ['required', 'in:add,deduct'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        if ($validated['action'] === 'add') {
            $user->addBalance($validated['amount'], "تعديل رصيد يدوي من الإدارة: {$validated['reason']}", null, 'admin_adjustment');
            $msg = "تمت إضافة \${$validated['amount']} إلى حساب العميل {$user->name}.";
        } else {
            if ($user->balance < $validated['amount']) {
                return back()->with('error', 'رصيد المستخدم أقل من المبلغ المطلوب خصمه.');
            }
            $user->deductBalance($validated['amount'], "خصم رصيد يدوي من الإدارة: {$validated['reason']}", null, 'admin_adjustment');
            $msg = "تم خصم \${$validated['amount']} من حساب العميل {$user->name}.";
        }

        return back()->with('success', $msg);
    }

    public function userToggleBan(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id() || $user->is_admin) {
            return back()->with('error', 'لا يمكنك حظر حساب المدير!');
        }

        $user->is_banned = !$user->is_banned;
        if ($user->is_banned) {
            $user->ban_reason = $request->input('ban_reason', 'مخالفة شروط وسياسات استخدام المنصة');
        } else {
            $user->ban_reason = null;
        }
        $user->save();

        $actionText = $user->is_banned ? 'تم حظر المستخدم بنجاح ومنعه من تسجيل الدخول.' : 'تم إلغاء حظر المستخدم بنجاح.';
        return back()->with('success', $actionText);
    }

    // ==========================================
    // 7. TASKS & REWARDS MANAGEMENT
    // ==========================================
    public function tasksIndex()
    {
        $tasks = \App\Models\Task::withCount('completions')->latest()->paginate(15);
        $conversions = \App\Models\PointConversion::with('user')->latest()->paginate(15);

        return view('admin.tasks.index', compact('tasks', 'conversions'));
    }

    public function taskStore(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:like_post,like_reel,follow_account,comment,story_view'],
            'target_url' => ['required', 'url'],
            'points_reward' => ['required', 'integer', 'min:1'],
            'max_completions' => ['nullable', 'integer', 'min:1'],
            'instructions' => ['nullable', 'string'],
            'is_daily' => ['nullable', 'boolean'],
        ]);

        $validated['is_daily'] = $request->boolean('is_daily');
        $validated['status'] = true;

        \App\Models\Task::create($validated);

        return back()->with('success', 'تم إنشاء المهمة ونشرها للمستخدمين بنجاح!');
    }

    public function taskToggleStatus($id)
    {
        $task = \App\Models\Task::findOrFail($id);
        $task->status = !$task->status;
        $task->save();

        return back()->with('success', 'تم تغيير حالة المهمة بنجاح.');
    }

    public function taskDestroy($id)
    {
        $task = \App\Models\Task::findOrFail($id);
        $task->delete();

        return back()->with('success', 'تم حذف المهمة بنجاح.');
    }

    // ==========================================
    // 8. SETTINGS MANAGEMENT
    // ==========================================
    public function settingsIndex()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'انستازون | InstaZone'),
            'usdt_trc20_address' => Setting::get('usdt_trc20_address', 'TYDzsYUEWzK1oP4vB7g8W8c4BvHnK7f8zM'),
            'usdt_bep20_address' => Setting::get('usdt_bep20_address', '0x71C67Ed37037C0d7Bf3014B1F20606B5e4492A72'),
            'qi_card_number' => Setting::get('qi_card_number', '9821 0000 1234 5678'),
            'qi_account_name' => Setting::get('qi_account_name', 'انستازون لخدمات الدفع'),
            'zaincash_phone' => Setting::get('zaincash_phone', '07800000000'),
            'zaincash_account_name' => Setting::get('zaincash_account_name', 'InstaZone Official'),
            'usd_to_iqd_rate' => Setting::get('usd_to_iqd_rate', '1500'),
            'telegram_support' => Setting::get('telegram_support', '@InstaZone_Support'),
            'whatsapp_support' => Setting::get('whatsapp_support', '+9647700000000'),
            'notice_banner' => Setting::get('notice_banner', 'أهلاً بكم في متجر انستازون - تسليم فوري لليوزرات المميزة وسيرفرات زيادة المتابعين الأسرع والأضمن.'),
            'points_per_dollar' => Setting::get('points_per_dollar', '1000'),
            'min_conversion_points' => Setting::get('min_conversion_points', '500'),
            'daily_bonus_points' => Setting::get('daily_bonus_points', '100'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'تم حفظ وتحديث الإعدادات بنجاح!');
    }
}
