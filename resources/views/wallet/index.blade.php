@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <!-- Header & Balance Banner -->
    <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 inline-block mb-3">
                    <i class="fa-solid fa-wallet"></i> المحفظة الرقمية
                </span>
                <h1 class="text-3xl font-black text-white mb-2">رصيد حسابك ومعاملاتك المالية</h1>
                <p class="text-xs sm:text-sm text-slate-300">
                    رصيدك يمكنك استخدامه مباشرة لشراء اليوزرات أو طلب خدمات المتابعين والتفاعل.
                </p>
            </div>

            <!-- Big Balance Box -->
            <div class="bg-black/60 rounded-2xl p-6 border border-white/10 text-center sm:text-right flex flex-col sm:flex-row items-center gap-6">
                <div>
                    <span class="text-xs text-slate-400 block mb-1">الرصيد المتاح حالياً</span>
                    <div class="text-3xl sm:text-4xl font-black font-outfit text-white">
                        ${{ number_format($user->balance, 2) }}
                        <span class="text-sm font-mono text-emerald-400">USD</span>
                    </div>
                </div>
                <a href="{{ route('wallet.deposit') }}" class="px-6 py-3.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs shadow-lg shadow-pink-600/30 transition flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-plus"></i> شحن الرصيد الآن
                </a>
            </div>
        </div>

        <!-- Direct Payment Methods Grid (Visible before entering deposit page) -->
        <div class="mt-8 pt-6 border-t border-white/10">
            <h3 class="text-sm font-bold text-slate-300 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-yellow-400"></i> طرق شحن الرصيد المتاحة:
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Method 1: Super Qi / Mastercard -->
                <a href="{{ route('wallet.deposit') }}" class="p-4 rounded-2xl bg-black/40 border border-yellow-500/30 hover:border-yellow-500/60 transition group flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-yellow-500/20 text-yellow-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white group-hover:text-yellow-400 transition-colors">سوبر كي / ماستركارد</h4>
                            <p class="text-[11px] text-slate-400">Super Qi & Qi Card</p>
                        </div>
                    </div>
                    <span class="text-xs text-yellow-400 font-bold bg-yellow-500/10 px-2.5 py-1 rounded-lg border border-yellow-500/20">شحن فوراً</span>
                </a>

                <!-- Method 2: ZainCash -->
                <a href="{{ route('wallet.deposit') }}" class="p-4 rounded-2xl bg-black/40 border border-purple-500/30 hover:border-purple-500/60 transition group flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-mobile-screen"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white group-hover:text-purple-400 transition-colors">زين كاش (ZainCash)</h4>
                            <p class="text-[11px] text-slate-400">تحويل سريع بالمحفظة</p>
                        </div>
                    </div>
                    <span class="text-xs text-purple-400 font-bold bg-purple-500/10 px-2.5 py-1 rounded-lg border border-purple-500/20">شحن فوراً</span>
                </a>

                <!-- Method 3: USDT -->
                <a href="{{ route('wallet.deposit') }}" class="p-4 rounded-2xl bg-black/40 border border-emerald-500/30 hover:border-emerald-500/60 transition group flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">العملات الرقمية (USDT)</h4>
                            <p class="text-[11px] text-slate-400">TRC20 / BEP20 Binance</p>
                        </div>
                    </div>
                    <span class="text-xs text-emerald-400 font-bold bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">شحن فوراً</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Pending Deposits Notice (if any) -->
    @if($pendingDeposits->count() > 0)
        <div class="glass-card rounded-2xl p-5 border border-amber-500/30 bg-amber-500/5">
            <h3 class="text-xs font-bold text-amber-400 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-hourglass-half"></i> طلبات شحن قيد المراجعة والتدقيق:
            </h3>
            <div class="space-y-2">
                @foreach($pendingDeposits as $p)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 rounded-xl bg-black/40 border border-white/5 text-xs">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-amber-300 font-bold">#{{ $p->deposit_code }}</span>
                            {!! $p->method_badge !!}
                            <span>شحن <strong class="text-white">${{ number_format($p->amount_usd, 2) }}</strong>
                                @if($p->amount_iqd)
                                    <span class="text-yellow-400 font-mono">({{ number_format($p->amount_iqd) }} د.ع)</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-400 font-mono text-[11px]">
                            @if($p->card_last_four)
                                <span>البطاقة: {{ $p->card_last_four }}</span>
                                <span>•</span>
                            @endif
                            @if($p->sender_phone)
                                <span>الهاتف: {{ $p->sender_phone }}</span>
                                <span>•</span>
                            @endif
                            <span>{{ $p->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Transactions Ledger -->
    <div class="glass-card rounded-3xl border border-white/10 overflow-hidden">
        <div class="bg-white/5 px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-receipt text-pink-400"></i> سجل العمليات والحركات المالية
            </h2>
            <span class="text-xs text-slate-400 font-mono">{{ $transactions->total() }} عملية مسجلة</span>
        </div>

        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-black/50 text-slate-400 border-b border-white/10">
                        <tr>
                            <th class="py-4 px-6 font-bold">نوع العملية</th>
                            <th class="py-4 px-4 font-bold">البيان / الوصف</th>
                            <th class="py-4 px-4 font-bold text-center">المبلغ</th>
                            <th class="py-4 px-4 font-bold text-center">الرصيد قبل</th>
                            <th class="py-4 px-4 font-bold text-center">الرصيد بعد</th>
                            <th class="py-4 px-6 font-bold text-left">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 font-mono">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="py-4 px-6">
                                    {!! $tx->type_badge !!}
                                </td>
                                <td class="py-4 px-4 font-sans text-slate-200">
                                    {{ $tx->description }}
                                </td>
                                <td class="py-4 px-4 text-center font-bold text-sm {{ $tx->amount >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $tx->amount >= 0 ? '+' : '' }}${{ number_format($tx->amount, 2) }}
                                </td>
                                <td class="py-4 px-4 text-center text-slate-400">
                                    ${{ number_format($tx->balance_before, 2) }}
                                </td>
                                <td class="py-4 px-4 text-center text-white font-bold">
                                    ${{ number_format($tx->balance_after, 2) }}
                                </td>
                                <td class="py-4 px-6 text-left text-slate-400 text-[11px]">
                                    {{ $tx->created_at->format('Y-m-d H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-white/10">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-16 p-6">
                <i class="fa-solid fa-receipt text-3xl text-slate-600 mb-3"></i>
                <h3 class="text-sm font-bold text-white mb-1">لا توجد حركات مالية حتى الآن</h3>
                <p class="text-xs text-slate-400">ستظهر هنا تفاصيل أي إيداع أو شراء يوزرات أو طلبات متابعين تنفذها.</p>
            </div>
        @endif
    </div>

</div>
@endsection
