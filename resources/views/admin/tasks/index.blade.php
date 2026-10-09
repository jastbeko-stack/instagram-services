@extends('layouts.admin')

@section('page_title', 'إدارة مهمات التفاعل ونقاط المكافآت')

@section('admin_content')
<div class="space-y-8" x-data="{ showCreateModal: false }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-white">المهمات اليومية المنشورة</h2>
            <p class="text-xs text-slate-400">يمكنك إنشاء مهمات لايكات وريلز للمستخدمين لتنفيذها وكسب النقاط</p>
        </div>
        <button @click="showCreateModal = true" class="px-5 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs shadow-lg shadow-pink-600/25 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> إضافة مهمة تفاعل جديدة
        </button>
    </div>

    <!-- Tasks Table -->
    <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-black/50 text-slate-400 border-b border-white/10">
                    <tr>
                        <th class="py-3 px-6">عنوان المهمة</th>
                        <th class="py-3 px-4">نوع المهمة</th>
                        <th class="py-3 px-4">الرابط المستهدف</th>
                        <th class="py-3 px-4 text-center">النقاط المكتسبة</th>
                        <th class="py-3 px-4 text-center">مرات التنفيذ</th>
                        <th class="py-3 px-4 text-center">الحالة</th>
                        <th class="py-3 px-6 text-left">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="py-4 px-6 font-bold text-white text-sm">
                                {{ $task->title }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] bg-white/5 border border-white/10 text-slate-300 inline-flex items-center gap-1.5">
                                    <i class="fa-solid {{ $task->type_icon }}"></i>
                                    <span>{{ $task->formatted_type }}</span>
                                </span>
                            </td>
                            <td class="py-4 px-4 max-w-[200px] truncate">
                                <a href="{{ $task->target_url }}" target="_blank" class="text-pink-400 hover:underline font-mono text-[11px] dir-ltr inline-block">
                                    {{ Str::limit($task->target_url, 30) }} <i class="fa-solid fa-arrow-up-right-from-square text-[9px] mr-1"></i>
                                </a>
                            </td>
                            <td class="py-4 px-4 text-center font-mono font-bold text-amber-400 text-sm">
                                +{{ number_format($task->points_reward) }}
                            </td>
                            <td class="py-4 px-4 text-center font-mono text-slate-300">
                                {{ number_format($task->completions_count) }}
                                @if($task->max_completions)
                                    <span class="text-slate-500 text-[10px]">/ {{ number_format($task->max_completions) }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($task->status)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">نشطة</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-500/10 text-rose-400 border border-rose-500/20">معطلة</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-left">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.tasks.toggle', $task->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white transition" title="{{ $task->status ? 'تعطيل' : 'تفعيل' }}">
                                            <i class="fa-solid {{ $task->status ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المهمة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition" title="حذف">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">لا توجد مهمات منشورة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-white/10">
            {{ $tasks->links() }}
        </div>
    </div>

    <!-- Conversions Ledger Table -->
    <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="bg-white/5 px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-arrow-right-arrow-left text-emerald-400"></i> سجل استبدال النقاط إلى رصيد للمستخدمين
            </h3>
            <span class="text-xs text-slate-400 font-mono">{{ $conversions->total() }} عملية تحويل</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-black/50 text-slate-400">
                    <tr>
                        <th class="py-3 px-6">المستخدم</th>
                        <th class="py-3 px-4 text-center">النقاط المستبدلة</th>
                        <th class="py-3 px-4 text-center">الرصيد المضاف بالـ USDT</th>
                        <th class="py-3 px-6 text-left">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 font-mono">
                    @forelse($conversions as $conv)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="py-3 px-6 font-sans font-bold text-white">
                                {{ $conv->user->name ?? 'مستخدم' }}
                                <span class="text-slate-400 font-normal text-[11px] block">{{ $conv->user->email ?? '' }}</span>
                            </td>
                            <td class="py-3 px-4 text-center text-amber-400 font-bold">
                                {{ number_format($conv->points_spent) }} نقطة
                            </td>
                            <td class="py-3 px-4 text-center text-emerald-400 font-bold text-sm">
                                +${{ number_format($conv->balance_credited, 2) }} USDT
                            </td>
                            <td class="py-3 px-6 text-left text-slate-400 text-[11px]">
                                {{ $conv->created_at->format('Y-m-d H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500 font-sans">لا توجد عمليات تحويل حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-white/10">
            {{ $conversions->links() }}
        </div>
    </div>

    <!-- Create Task Modal -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="showCreateModal = false" class="bg-dark-900 border border-white/10 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl">
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-white/10">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-pink-400"></i> إنشاء مهمة تفاعل جديدة
                </h3>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.tasks.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">عنوان المهمة</label>
                    <input type="text" name="title" required placeholder="مثال: وضع لايك على بوست العروض الجديدة"
                        class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">نوع المهمة</label>
                    <select name="type" required class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                        <option value="like_post">لايك بوست انستقرام (Post Like)</option>
                        <option value="like_reel">لايك ومشاهدة ريلز (Reel Like & View)</option>
                        <option value="follow_account">متابعة حساب انستقرام (Account Follow)</option>
                        <option value="comment">كتابة تعليق (Comment)</option>
                        <option value="story_view">مشاهدة وتصويت ستوري (Story)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">رابط المنشور أو الحساب على انستقرام</label>
                    <input type="url" name="target_url" required placeholder="https://instagram.com/p/..."
                        class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-500 dir-ltr text-left">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">النقاط المكتسبة</label>
                        <input type="number" min="1" name="points_reward" value="50" required
                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">أقصى عدد للمنفذين (اختياري)</label>
                        <input type="number" min="1" name="max_completions" placeholder="غير محدود"
                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">تعليمات للمستخدم (اختياري)</label>
                    <textarea name="instructions" rows="2" placeholder="مثال: ادخل للرابط واضغط لايك ثم أكد إنجاز المهمة..."
                        class="w-full bg-black/50 border border-white/10 rounded-xl p-3 text-xs text-white"></textarea>
                </div>

                <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300">
                        <input type="checkbox" name="is_daily" value="1" checked class="rounded bg-black text-pink-600 focus:ring-0">
                        <span>مهمة يومية تتجدد كل 24 ساعة للمستخدم</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-white/10">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl bg-white/5 text-slate-300 text-xs">إلغاء</button>
                    <button type="submit" class="px-6 py-2 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs transition">نشر المهمة</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
