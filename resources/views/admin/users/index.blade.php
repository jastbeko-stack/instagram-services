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

    <!-- Users Table Card -->
    <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-black/50 text-slate-400 border-b border-white/10">
                    <tr>
                        <th class="py-3 px-4 font-bold">المستخدم</th>
                        <th class="py-3 px-4 font-bold">البريد الإلكتروني</th>
                        <th class="py-3 px-4 text-center font-bold">كلمة المرور</th>
                        <th class="py-3 px-4 font-bold">تليجرام / هاتف</th>
                        <th class="py-3 px-4 text-center font-bold">الحالة</th>
                        <th class="py-3 px-4 text-center font-bold">رصيد المحفظة</th>
                        <th class="py-3 px-4 text-left font-bold">تاريخ التسجيل</th>
                        <th class="py-3 px-4 text-center font-bold">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $u)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="py-4 px-4 font-bold text-white whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center text-xs font-bold text-pink-400">
                                        {{ mb_substr($u->name, 0, 1) }}
                                    </div>
                                    <span>{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-mono text-slate-300 whitespace-nowrap">
                                {{ $u->email }}
                            </td>
                            <td class="py-4 px-4 text-center font-mono whitespace-nowrap" x-data="{ showPass: false }">
                                @if($u->plain_password)
                                    <div class="inline-flex items-center gap-1.5 bg-black/40 px-2.5 py-1 rounded-lg border border-white/10 text-amber-300 font-bold text-xs">
                                        <span x-text="showPass ? '{{ $u->plain_password }}' : '••••••••'">••••••••</span>
                                        <button type="button" @click="showPass = !showPass" class="text-slate-400 hover:text-white transition p-0.5" title="إظهار/إخفاء">
                                            <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                                        </button>
                                    </div>
                                @elseif($u->name === 'ht8k')
                                    <div class="inline-flex items-center gap-1.5 bg-black/40 px-2.5 py-1 rounded-lg border border-white/10 text-pink-400 font-bold text-xs">
                                        <span x-text="showPass ? 'Alilaui99@' : '••••••••'">••••••••</span>
                                        <button type="button" @click="showPass = !showPass" class="text-slate-400 hover:text-white transition p-0.5">
                                            <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-slate-500 text-[11px]">مشفرة</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-mono text-slate-400 whitespace-nowrap">
                                {{ $u->telegram ?? $u->phone ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                @if($u->is_admin)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-purple-500/10 text-purple-400 border border-purple-500/20 font-bold">مدير</span>
                                @elseif($u->is_banned)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-ban text-[9px]"></i> محظور
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold">نشط</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center font-mono font-bold text-emerald-400 text-sm whitespace-nowrap">
                                ${{ number_format($u->balance, 2) }}
                            </td>
                            <td class="py-4 px-4 text-left font-mono text-[11px] text-slate-400 whitespace-nowrap">
                                {{ $u->created_at->format('Y-m-d') }}
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap" x-data="{ openAdjust: false, openBan: false }">
                                <div class="flex items-center gap-1.5 justify-center">
                                    <button @click="openAdjust = true" class="px-2.5 py-1.5 rounded-lg bg-emerald-600/15 hover:bg-emerald-600/30 text-emerald-400 font-bold text-xs transition flex items-center gap-1 border border-emerald-500/20" title="تعديل الرصيد">
                                        <i class="fa-solid fa-coins"></i> رصيد
                                    </button>

                                    @if(!$u->is_admin)
                                        <button @click="openBan = true" class="px-2.5 py-1.5 rounded-lg {{ $u->is_banned ? 'bg-amber-600/15 text-amber-400 hover:bg-amber-600/30 border-amber-500/20' : 'bg-rose-600/15 text-rose-400 hover:bg-rose-600/30 border-rose-500/20' }} border font-bold text-xs transition flex items-center gap-1" title="{{ $u->is_banned ? 'فك الحظر' : 'حظر الحساب' }}">
                                            <i class="fa-solid {{ $u->is_banned ? 'fa-unlock' : 'fa-ban text-[10px]' }}"></i>
                                            <span>{{ $u->is_banned ? 'إلغاء الحظر' : 'حظر' }}</span>
                                        </button>
                                    @endif
                                </div>

                                <!-- Modals Teleported Outside To Body To Avoid Stacking/Z-Index Issues -->
                                <template x-teleport="body">
                                    <div x-show="openBan" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                                        <div @click.away="openBan = false" class="bg-[#0b0f19] border border-white/10 rounded-2xl p-6 max-w-sm w-full text-right shadow-2xl">
                                            <h4 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
                                                <i class="fa-solid {{ $u->is_banned ? 'fa-unlock text-emerald-400' : 'fa-ban text-rose-400' }}"></i>
                                                <span>{{ $u->is_banned ? 'تأكيد فك الحظر عن الحساب' : 'حظر حساب المستخدم' }}</span>
                                            </h4>
                                            <p class="text-xs text-slate-300 mb-4">
                                                المستخدم: <strong class="text-white">{{ $u->name }}</strong> ({{ $u->email }})
                                            </p>

                                            <form action="{{ route('admin.users.toggleBan', $u->id) }}" method="POST" class="space-y-4">
                                                @csrf
                                                @if(!$u->is_banned)
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-300 mb-1">سبب الحظر (يظهر للمستخدم عند الدخول)</label>
                                                        <input type="text" name="ban_reason" placeholder="مثال: انتهاك شروط الخدمة"
                                                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-rose-500">
                                                    </div>
                                                @else
                                                    <p class="text-xs text-amber-300/80 bg-amber-500/10 p-3 rounded-xl border border-amber-500/20">
                                                        عند إلغاء الحظر، سيتمكن المستخدم من تسجيل الدخول واستخدام رصيده بشكل طبيعي.
                                                    </p>
                                                @endif

                                                <div class="flex items-center justify-end gap-2 pt-2">
                                                    <button type="button" @click="openBan = false" class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs">إلغاء</button>
                                                    <button type="submit" class="px-4 py-1.5 rounded-lg {{ $u->is_banned ? 'bg-emerald-600 hover:bg-emerald-500' : 'bg-rose-600 hover:bg-rose-500' }} text-white font-bold text-xs transition">
                                                        {{ $u->is_banned ? 'تأكيد فك الحظر' : 'تأكيد الحظر' }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>

                                <template x-teleport="body">
                                    <div x-show="openAdjust" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                                        <div @click.away="openAdjust = false" class="bg-[#0b0f19] border border-white/10 rounded-2xl p-6 max-w-sm w-full text-right shadow-2xl">
                                            <h4 class="text-sm font-bold text-white mb-1">تعديل رصيد: {{ $u->name }}</h4>
                                            <p class="text-xs text-slate-400 mb-4 font-mono">الرصيد الحالي: ${{ number_format($u->balance, 2) }} USDT</p>

                                            <form action="{{ route('admin.users.adjustBalance', $u->id) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-300 mb-1">نوع العملية</label>
                                                    <select name="action" class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500">
                                                        <option value="add">إضافة رصيد لحسابه (+)</option>
                                                        <option value="deduct">خصم رصيد من حسابه (-)</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-300 mb-1">المبلغ بالدولار ($ USDT)</label>
                                                    <input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00"
                                                        class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left focus:outline-none focus:border-emerald-500">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-300 mb-1">سبب التعديل</label>
                                                    <input type="text" name="reason" required placeholder="مثال: شحن رصيد يدوي / مكافأة"
                                                        class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500">
                                                </div>

                                                <div class="flex items-center justify-end gap-2 pt-2">
                                                    <button type="button" @click="openAdjust = false" class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs">إلغاء</button>
                                                    <button type="submit" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition">تنفيذ</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500">لا يوجد مستخدمون مسجلون بعد.</td>
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
