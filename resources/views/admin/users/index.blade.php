@extends('layouts.admin')

@section('page_title', 'المستخدمون وإدارة المحافظ')

@section('admin_content')
<div class="space-y-6">

    <!-- Search & Stats Bar -->
    <div class="glass-card rounded-2xl p-4 border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="w-full sm:w-80">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الإيميل أو تليجرام..."
                class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-pink-500">
        </form>
        <span class="text-xs text-slate-400 font-mono">{{ $users->total() }} مستخدم مسجل</span>
    </div>

    <!-- Users Table -->
    <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-black/50 text-slate-400 border-b border-white/10">
                    <tr>
                        <th class="py-3 px-6">المستخدم</th>
                        <th class="py-3 px-4">البريد الإلكتروني</th>
                        <th class="py-3 px-4">تليجرام / هاتف</th>
                        <th class="py-3 px-4 text-center">الرتبة</th>
                        <th class="py-3 px-4 text-center">رصيد المحفظة</th>
                        <th class="py-3 px-4 text-left">تاريخ التسجيل</th>
                        <th class="py-3 px-6 text-left">تعديل الرصيد</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $u)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="py-4 px-6 font-bold text-white">
                                {{ $u->name }}
                            </td>
                            <td class="py-4 px-4 font-mono text-slate-300">
                                {{ $u->email }}
                            </td>
                            <td class="py-4 px-4 font-mono text-slate-400">
                                {{ $u->telegram ?? $u->phone ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($u->is_admin)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-purple-500/10 text-purple-400 border border-purple-500/20 font-bold">مدير</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-500/10 text-slate-400 border border-slate-500/20">عميل</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center font-mono font-bold text-emerald-400 text-sm">
                                ${{ number_format($u->balance, 2) }}
                            </td>
                            <td class="py-4 px-4 text-left font-mono text-[11px] text-slate-400">
                                {{ $u->created_at->format('Y-m-d') }}
                            </td>
                            <td class="py-4 px-6 text-left" x-data="{ openAdjust: false }">
                                <button @click="openAdjust = true" class="px-3 py-1.5 rounded-lg bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-400 font-bold text-xs transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-coins"></i> تعديل الرصيد
                                </button>

                                <!-- Balance Adjustment Modal -->
                                <div x-show="openAdjust" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                                    <div @click.away="openAdjust = false" class="bg-dark-900 border border-white/10 rounded-2xl p-6 max-w-sm w-full text-right shadow-2xl">
                                        <h4 class="text-sm font-bold text-white mb-1">تعديل رصيد: {{ $u->name }}</h4>
                                        <p class="text-xs text-slate-400 mb-4 font-mono">الرصيد الحالي: ${{ number_format($u->balance, 2) }} USDT</p>

                                        <form action="{{ route('admin.users.adjustBalance', $u->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            <div>
                                                <label class="block text-xs font-bold text-slate-300 mb-1">نوع العملية</label>
                                                <select name="action" class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                                                    <option value="add">إضافة رصيد لحسابه (+)</option>
                                                    <option value="deduct">خصم رصيد من حسابه (-)</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-300 mb-1">المبلغ بالدولار ($ USDT)</label>
                                                <input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00"
                                                    class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-300 mb-1">سبب التعديل (يظهر في سجل العمليات)</label>
                                                <input type="text" name="reason" required placeholder="مثال: مكافأة شحن / تسوية طلب ملغي"
                                                    class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                                            </div>

                                            <div class="flex items-center justify-end gap-2 pt-2">
                                                <button type="button" @click="openAdjust = false" class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs">إلغاء</button>
                                                <button type="submit" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition">تنفيذ</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">لا يوجد مستخدمون مسجلون بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-white/10">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
