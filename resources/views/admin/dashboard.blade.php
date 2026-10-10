@extends('layouts.admin')

@section('page_title', 'لوحة التحكم الإدارية')

@section('admin_content')
<div class="space-y-8">

    <!-- KPI Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: SMM Orders -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400">إجمالي طلبات المتابعين</span>
                <span class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-bolt"></i>
                </span>
            </div>
            <div class="text-3xl font-black font-outfit text-white mb-1">
                {{ number_format($stats['smm_orders_count']) }}
            </div>
            <div class="text-xs text-slate-400 flex items-center gap-2">
                <span class="text-amber-400 font-bold font-mono">{{ $stats['smm_orders_pending'] }} معلق</span>
                <span>•</span>
                <span class="text-emerald-400 font-bold font-mono">${{ number_format($stats['smm_revenue'], 2) }} إيرادات</span>
            </div>
        </div>

        <!-- Card 2: Usernames Sold -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400">اليوزرات والحسابات</span>
                <span class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-at"></i>
                </span>
            </div>
            <div class="text-3xl font-black font-outfit text-white mb-1">
                {{ $stats['usernames_sold'] }} <span class="text-xs font-normal text-slate-400">مباع</span>
            </div>
            <div class="text-xs text-slate-400 flex items-center gap-2">
                <span class="text-pink-400 font-bold font-mono">{{ $stats['usernames_available'] }} متوفر</span>
                <span>•</span>
                <span class="text-emerald-400 font-bold font-mono">${{ number_format($stats['total_username_revenue'], 2) }} مبيعات</span>
            </div>
        </div>

        <!-- Card 3: Pending Deposits (All gateways) -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400">طلبات الشحن بانتظار التدقيق</span>
                <span class="w-10 h-10 rounded-xl bg-yellow-500/10 text-yellow-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-credit-card"></i>
                </span>
            </div>
            <div class="text-3xl font-black font-outfit text-yellow-400 mb-1">
                {{ $stats['pending_deposits_count'] }}
            </div>
            <div class="text-xs text-slate-400">
                إجمالي القيمة: <strong class="text-white font-mono">${{ number_format($stats['pending_deposits_amount'], 2) }}</strong>
                <span class="text-[10px] text-slate-500 block mt-0.5">سوبر كي / زين كاش / USDT</span>
            </div>
        </div>

        <!-- Card 4: Total Users & Balances -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400">العملاء ومحافظهم</span>
                <span class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-users"></i>
                </span>
            </div>
            <div class="text-3xl font-black font-outfit text-white mb-1">
                {{ number_format($stats['total_users']) }}
            </div>
            <div class="text-xs text-slate-400">
                إجمالي أرصدة المحافظ: <strong class="text-white font-mono">${{ number_format($stats['total_balances'], 2) }} USD</strong>
            </div>
        </div>

    </div>

    <!-- Pending Deposits Urgent Table -->
    @if($pendingDeposits->count() > 0)
        <div class="glass-card rounded-2xl border border-yellow-500/30 overflow-hidden">
            <div class="bg-yellow-500/10 px-6 py-4 border-b border-yellow-500/20 flex items-center justify-between">
                <h3 class="text-sm font-bold text-yellow-300 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation animate-pulse"></i> طلبات شحن رصيد معلقة (سوبر كي / زين كاش / USDT) بانتظار الاعتماد
                </h3>
                <a href="{{ route('admin.deposits.index') }}" class="text-xs text-yellow-300 font-bold hover:underline">عرض جميع الطلبات</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-black/40 text-slate-400 border-b border-white/10">
                        <tr>
                            <th class="py-3 px-6">كود الشحن</th>
                            <th class="py-3 px-4 font-sans">العميل</th>
                            <th class="py-3 px-4 text-center">طريقة الدفع</th>
                            <th class="py-3 px-4 text-center">المبلغ</th>
                            <th class="py-3 px-4">تفاصيل الحساب / البطاقة</th>
                            <th class="py-3 px-6 font-sans text-left">الإجراء السريع</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($pendingDeposits as $dep)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="py-3 px-6 font-bold font-mono text-white">#{{ $dep->deposit_code }}</td>
                                <td class="py-3 px-4 font-sans text-white">
                                    <div class="font-bold">{{ $dep->user->name ?? 'غير معروف' }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $dep->user->email ?? '' }}</div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    {!! $dep->method_badge !!}
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-emerald-400 text-sm">
                                    ${{ number_format($dep->amount_usd, 2) }}
                                    @if($dep->amount_iqd)
                                        <div class="text-[10px] text-yellow-400/90 font-mono">{{ number_format($dep->amount_iqd) }} د.ع</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-xs font-mono">
                                    @if($dep->card_last_four)
                                        <span class="text-yellow-300 block font-bold">بطاقة: {{ $dep->card_last_four }}</span>
                                    @endif
                                    @if($dep->sender_phone)
                                        <span class="text-purple-300 block">هاتف: {{ $dep->sender_phone }}</span>
                                    @endif
                                    @if($dep->txid)
                                        <span class="text-slate-400 block truncate max-w-xs" title="{{ $dep->txid }}">TX: {{ Str::limit($dep->txid, 20) }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left font-sans">
                                    <form action="{{ route('admin.deposits.approve', $dep->id) }}" method="POST" class="inline-block" onsubmit="return confirm('تأكيد قبول الإيداع وإضافة ${{ $dep->amount_usd }} لمحفظة العميل فوراً؟')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition flex items-center gap-1">
                                            <i class="fa-solid fa-check"></i> اعتماد فوري
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Recent Orders & Purchases Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Recent SMM Orders -->
        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
            <div class="bg-white/5 px-6 py-4 border-b border-white/10 flex items-center justify-between">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-yellow-400"></i> أحدث طلبات المتابعين والتفاعل
                </h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-pink-400 hover:underline">كل الطلبات</a>
            </div>
            <div class="divide-y divide-white/5 text-xs">
                @forelse($recentOrders as $ro)
                    <div class="p-4 flex items-center justify-between hover:bg-white/[0.02]">
                        <div>
                            <div class="font-bold text-white mb-0.5">
                                {{ $ro->service->name_ar ?? 'خدمة' }}
                                <span class="text-slate-400 font-mono text-[11px] font-normal">({{ number_format($ro->quantity) }})</span>
                            </div>
                            <div class="text-[11px] text-slate-400 font-mono">
                                <span>#{{ $ro->order_number }}</span> •
                                <span class="text-emerald-400">${{ number_format($ro->charge, 4) }}</span> •
                                <span>{{ $ro->user->name ?? 'عميل' }}</span>
                            </div>
                        </div>
                        <div>
                            {!! $ro->status_badge !!}
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-500">لا توجد طلبات بعد.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Username Purchases -->
        <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
            <div class="bg-white/5 px-6 py-4 border-b border-white/10 flex items-center justify-between">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-at text-pink-400"></i> أحدث مبيعات اليوزرات والحسابات
                </h3>
                <a href="{{ route('admin.usernames.index') }}" class="text-xs text-pink-400 hover:underline">إدارة اليوزرات</a>
            </div>
            <div class="divide-y divide-white/5 text-xs">
                @forelse($recentPurchases as $rp)
                    <div class="p-4 flex items-center justify-between hover:bg-white/[0.02]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl insta-gradient flex items-center justify-center text-white text-base">
                                <i class="fa-brands fa-instagram"></i>
                            </div>
                            <div>
                                <div class="font-bold text-white font-outfit dir-ltr text-right">
                                    @<span>{{ $rp->username->username ?? 'محذوف' }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    المشتري: <strong class="text-slate-200">{{ $rp->user->name ?? 'غير معروف' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="text-left font-mono">
                            <span class="font-bold text-emerald-400 text-sm">${{ number_format($rp->price, 2) }}</span>
                            <span class="text-[10px] text-slate-500 block">{{ $rp->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-500">لا توجد مبيعات يوزرات مسجلة حتى الآن.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
