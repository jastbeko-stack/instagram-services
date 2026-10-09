<?php

namespace App\Http\Controllers;

use App\Models\SmmCategory;
use App\Models\SmmOrder;
use App\Models\SmmService;
use App\Services\SmmApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SmmOrderController extends Controller
{
    protected SmmApiService $smmApi;

    public function __construct(SmmApiService $smmApi)
    {
        $this->smmApi = $smmApi;
    }

    public function index()
    {
        $categories = \Illuminate\Support\Facades\Cache::remember('smm_categories_list', 600, function () {
            return SmmCategory::where('status', true)
                ->with(['services' => function ($query) {
                    $query->where('status', true)->orderBy('price_per_1k');
                }])
                ->orderBy('sort_order')
                ->get();
        });

        return view('smm.index', compact('categories'));
    }

    public function servicesList()
    {
        $categories = \Illuminate\Support\Facades\Cache::remember('smm_categories_list', 600, function () {
            return SmmCategory::where('status', true)
                ->with(['services' => function ($query) {
                    $query->where('status', true)->orderBy('price_per_1k');
                }])
                ->orderBy('sort_order')
                ->get();
        });

        return view('smm.services-table', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'service_id' => ['required', 'exists:smm_services,id'],
            'link' => ['required', 'string', 'url', 'max:500'],
            'quantity' => ['required', 'integer'],
        ]);

        $service = SmmService::with('provider')->findOrFail($validated['service_id']);

        if (!$service->status) {
            return back()->with('error', 'هذه الخدمة غير متاحة حالياً.');
        }

        if ($validated['quantity'] < $service->min_quantity || $validated['quantity'] > $service->max_quantity) {
            return back()->with('error', "الكمية المسموحة لهذه الخدمة يجب أن تكون بين {$service->min_quantity} و {$service->max_quantity}.");
        }

        $cost = $service->calculateCost($validated['quantity']);

        if (!$user->hasSufficientBalance($cost)) {
            $needed = number_format($cost - $user->balance, 2);
            return back()->with('error', "رصيدك غير كافٍ. التكلفة: \${$cost} USDT، ينقصك \${$needed} USDT. يرجى شحن محفظتك أولاً.");
        }

        try {
            $order = DB::transaction(function () use ($user, $service, $validated, $cost) {
                $orderNumber = 'SMM-' . strtoupper(Str::random(8));

                // Deduct balance
                $user->deductBalance(
                    $cost,
                    "طلب خدمة {$service->name_ar} (كمية: {$validated['quantity']})",
                    $orderNumber,
                    'smm_order'
                );

                $order = SmmOrder::create([
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                    'service_id' => $service->id,
                    'link' => $validated['link'],
                    'quantity' => $validated['quantity'],
                    'charge' => $cost,
                    'execution_type' => $service->execution_type,
                    'provider_id' => $service->provider_id,
                    'status' => 'pending',
                ]);

                // If Automatic API execution and provider configured
                if ($service->execution_type === 'api' && $service->provider && $service->provider_service_id) {
                    $apiResult = $this->smmApi->placeOrder(
                        $service->provider,
                        $service->provider_service_id,
                        $validated['link'],
                        $validated['quantity']
                    );

                    if ($apiResult['success']) {
                        $order->provider_order_id = $apiResult['order_id'];
                        $order->status = 'in_progress';
                        $order->save();
                    } else {
                        // Keep order pending for manual intervention or retry
                        $order->admin_notes = 'فشل الربط التلقائي: ' . ($apiResult['error'] ?? 'خطأ غير معروف');
                        $order->save();
                    }
                }

                return $order;
            });

            return redirect()->route('smm.myOrders')
                ->with('success', "تم استلام طلبك بنجاح برقم #{$order->order_number} ويجري تنفيذه!");
        } catch (\Throwable $e) {
            return back()->with('error', 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage());
        }
    }

    public function myOrders()
    {
        $orders = SmmOrder::with('service')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('smm.my-orders', compact('orders'));
    }
}
