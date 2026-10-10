@extends('layouts.admin')

@section('page_title', 'إدارة وتدقيق إيداعات العملات الرقمية (USDT)')

@section('admin_content')
<div class="space-y-6">

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4 border border-white/10 flex items-center justify-between">
        <form action="{{ route('admin.deposits.index') }}" method="GET" class="flex items-center gap-3">
            <select name="status" onchange="this.form.submit()" class="bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-xs text-white">
                <option value="">جميع الحالات</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>بانتظار المراجعة (Pending)</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>مقبول ومضاف (Approved)</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض (Rejected)</option>
            </select>
        </form>

        <span class="text-xs text-slate-400 font-mono">{{ $deposits->total() }} إيداع مسجل</span>
    </div>

    <!-- Deposits Table -->
    <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-black/50 text-slate-400 border-b border-white/10">
                    <tr>
                        <th class="py-3 px-6">كود الإيداع</th>
                        <th class="py-3 px-4">العميل</th>
                        <th class="py-3 px-4 text-center">طريقة الدفع</th>
                        <th class="py-3 px-4 text-center">المبلغ</th>
                        <th class="py-3 px-4">تفاصيل المعاملة / الحساب</th>
                        <th class="py-3 px-4 text-center">الإيصال</th>
                        <th class="py-3 px-4 text-center">الحالة</th>
                        <th class="py-3 px-4 text-left">التاريخ</th>
                        <th class="py-3 px-6 text-left">إجراء الإدارة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($deposits as $dep)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="py-4 px-6 font-mono font-bold text-white">
                                #{{ $dep->deposit_code }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-white">{{ $dep->user->name ?? 'غير معروف' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $dep->user->email ?? '' }}</div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                {!! $dep->method_badge !!}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="font-mono font-bold text-emerald-400 text-sm">
                                    ${{ number_format($dep->amount_usd, 2) }}
                                </div>
                                @if($dep->amount_iqd)
                                    <div class="text-[10px] text-yellow-400/90 font-mono">
                                        {{ number_format($dep->amount_iqd) }} د.ع
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-mono text-xs">
                                @if($dep->card_last_four)
                                    <div class="text-white font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-credit-card text-yellow-400 text-[10px]"></i>
                                        <span>آخر 4 أرقام: {{ $dep->card_last_four }}</span>
                                    </div>
                                @endif
                                @if($dep->sender_phone)
                                    <div class="text-slate-300 text-[11px] dir-ltr text-right">
                                        <i class="fa-solid fa-phone text-purple-400 text-[10px]"></i>
                                        <span>{{ $dep->sender_phone }}</span>
                                    </div>
                                @endif
                                @if($dep->txid)
                                    <div class="truncate text-slate-400 text-[10px] dir-ltr text-right max-w-xs" title="{{ $dep->txid }}">
                                        TX: {{ $dep->txid }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($dep->proof_image)
                                    <a href="{{ asset('storage/' . $dep->proof_image) }}" target="_blank" class="text-pink-400 hover:text-pink-300 underline font-bold text-[11px]">
                                        <i class="fa-solid fa-image ml-1"></i> صورة
                                    </a>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                {!! $dep->status_badge !!}
                            </td>
                            <td class="py-4 px-4 text-left font-mono text-[11px] text-slate-400">
                                {{ $dep->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="py-4 px-6 text-left">
                                @if($dep->status === 'pending')
                                    <div class="flex items-center justify-end gap-2" x-data="{ openReject: false }">
                                        <!-- Approve Button -->
                                        <form action="{{ route('admin.deposits.approve', $dep->id) }}" method="POST" onsubmit="return confirm('تأكيد اعتماد التحويل وإضافة ${{ $dep->amount_usd }} USDT لحساب العميل فوراً؟')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition flex items-center gap-1">
                                                <i class="fa-solid fa-check"></i> قبول
                                            </button>
                                        </form>

                                        <!-- Reject Trigger -->
                                        <button @click="openReject = true" class="px-3 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold text-xs transition">
                                            رفض
                                        </button>

                                        <!-- Reject Modal -->
                                        <div x-show="openReject" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                                            <div @click.away="openReject = false" class="bg-dark-900 border border-white/10 rounded-2xl p-6 max-w-sm w-full text-right shadow-2xl">
                                                <h4 class="text-sm font-bold text-white mb-2">سبب رفض الإيداع #{{ $dep->deposit_code }}</h4>
                                                <form action="{{ route('admin.deposits.reject', $dep->id) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    <textarea name="reason" rows="3" placeholder="اكتب سبب الرفض (مثال: كود المعاملة غير صحيح)..." class="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-xs text-white"></textarea>
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button type="button" @click="openReject = false" class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs">إلغاء</button>
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-600 text-white font-bold text-xs">تأكيد الرفض</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-500 text-[11px]">مكتمل</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-500">لا توجد طلبات إيداع مسجلة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-white/10">
            {{ $deposits->links() }}
        </div>
    </div>

</div>
@endsection
