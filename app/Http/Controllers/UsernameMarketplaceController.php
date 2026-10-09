<?php

namespace App\Http\Controllers;

use App\Models\InstagramUsername;
use App\Models\UsernameOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsernameMarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = InstagramUsername::query();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = ltrim($request->search, '@');
            $query->where('username', 'like', "%{$search}%");
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default show available first
            $query->orderByRaw("CASE WHEN status = 'available' THEN 1 ELSE 2 END");
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->latest(),
        };

        $usernames = $query->paginate(12)->withQueryString();

        return response()
            ->view('marketplace.index', compact('usernames'))
            ->header('Cache-Control', 'public, s-maxage=60, stale-while-revalidate=300');
    }

    public function show($id)
    {
        $username = InstagramUsername::findOrFail($id);
        $related = InstagramUsername::where('id', '!=', $id)
            ->where('status', 'available')
            ->where('type', $username->type)
            ->take(3)
            ->get();

        return view('marketplace.show', compact('username', 'related'));
    }

    public function buy(Request $request, $id)
    {
        $user = Auth::user();

        try {
            $order = DB::transaction(function () use ($user, $id) {
                // Lock the username row for update
                $item = InstagramUsername::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($item->status !== 'available') {
                    throw new \Exception('عذراً، هذا اليوزر لم يعد متاحاً للشراء (تم بيعه أو حجزه).');
                }

                if (!$user->hasSufficientBalance($item->price)) {
                    $needed = number_format($item->price - $user->balance, 2);
                    throw new \Exception("رصيدك الحالي غير كافٍ لإتمام عملية الشراء. ينقصك \${$needed} USDT.");
                }

                // Deduct balance
                $orderNumber = 'USR-' . strtoupper(Str::random(8));
                $user->deductBalance(
                    $item->price,
                    "شراء يوزر انستقرام @{$item->username}",
                    $orderNumber,
                    'username_purchase'
                );

                // Update item status
                $item->status = 'sold';
                $item->save();

                // Create Order record with delivery snapshot
                $order = UsernameOrder::create([
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                    'instagram_username_id' => $item->id,
                    'price' => $item->price,
                    'delivery_details' => $item->delivery_info ?? 'تم تسليم الحساب بنجاح. تواصل مع الدعم الفني لأي استفسار.',
                    'status' => 'completed',
                ]);

                return $order;
            });

            return redirect()->route('marketplace.orderSuccess', $order->id)
                ->with('success', 'تهانينا! تم شراء اليوزر بنجاح وتسليم معلومات الحساب.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function orderSuccess($orderId)
    {
        $order = UsernameOrder::with('username')
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        return view('marketplace.order-success', compact('order'));
    }

    public function myUsernames()
    {
        $orders = UsernameOrder::with('username')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('marketplace.my-purchases', compact('orders'));
    }
}
