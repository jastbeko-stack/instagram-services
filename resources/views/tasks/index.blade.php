@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10"
     x-data="{
        userPoints: {{ $user->points }},
        pointsToConvert: {{ max(500, min($user->points, 1000)) }},
        rate: {{ $pointsPerDollar }},
        get calculatedUsdt() {
            if (!this.pointsToConvert || this.pointsToConvert <= 0) return '0.00';
            return (this.pointsToConvert / this.rate).toFixed(2);
        }
     }">

    <!-- Header Banner -->
    <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 inline-block mb-3">
                    <i class="fa-solid fa-gift"></i> نظام المهام اليومية والمكافآت
                </span>
                <h1 class="text-3xl font-black text-white mb-2">أكمل المهمات واكسب رصيداً حقيقياً!</h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl">
                    قم بوضع لايكات على بوستات وريلز معينة أو متابعة الحسابات لكسب نقاط يومية، ثم حول نقاطك إلى رصيد USDT تستخدمه في شراء اليوزرات وخدمات المتابعين.
                </p>
            </div>

            <!-- Points & Value Counter -->
            <div class="bg-black/60 rounded-2xl p-6 border border-amber-500/30 text-center sm:text-right shrink-0">
                <span class="text-xs text-slate-400 block mb-1">رصيدك من النقاط</span>
                <div class="text-3xl sm:text-4xl font-black font-outfit text-amber-400 flex items-center justify-center sm:justify-start gap-2">
                    <i class="fa-solid fa-coins text-2xl"></i>
                    <span>{{ number_format($user->points) }}</span>
                    <span class="text-xs font-sans text-slate-400 font-bold">نقطة</span>
                </div>
                <span class="text-xs text-slate-400 mt-1 block">
                    تعادل تقريباً: <strong class="text-emerald-400 font-mono">${{ number_format($user->points / $pointsPerDollar, 2) }} USDT</strong>
                </span>
            </div>
        </div>
    </div>

    <!-- Daily Progress & All Tasks Bonus Card -->
    <div class="glass-card rounded-3xl p-6 border border-white/10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-pink-400"></i> تقدم مهمات اليوم (Daily Progress)
                </h3>
                <p class="text-xs text-slate-400">أنجزت {{ $completedCount }} من إجمالي {{ $totalTasksCount }} مهمات متاحة اليوم</p>
            </div>

            <!-- Claim Daily Bonus Button -->
            <div>
                @if($allCompleted)
                    @if($dailyBonusClaimed)
                        <span class="px-4 py-2 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-bold text-xs inline-flex items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> تم استلام مكافأة اليوم (+{{ $dailyBonusPoints }} نقطة)
                        </span>
                    @else
                        <form action="{{ route('tasks.claimBonus') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-pink-500 text-white font-bold text-xs shadow-lg shadow-amber-500/25 hover:scale-105 transition flex items-center gap-2 animate-bounce">
                                <i class="fa-solid fa-gift"></i> استلام المكافأة اليومية الكبرى (+{{ $dailyBonusPoints }} نقطة)!
                            </button>
                        </form>
                    @endif
                @else
                    <span class="text-xs text-amber-300/80 bg-amber-500/10 px-3 py-1.5 rounded-xl border border-amber-500/20 font-medium">
                        أكمل جميع مهمات اليوم للحصول على +{{ $dailyBonusPoints }} نقطة إضافية!
                    </span>
                @endif
            </div>
        </div>

        <!-- Progress Bar -->
        @php
            $percentage = $totalTasksCount > 0 ? round(($completedCount / $totalTasksCount) * 100) : 0;
        @endphp
        <div class="w-full bg-black/50 rounded-full h-3 border border-white/10 overflow-hidden">
            <div class="insta-gradient h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
        </div>
        <div class="flex justify-between items-center text-[11px] text-slate-400 mt-2 font-mono">
            <span>{{ $percentage }}% مكتمل</span>
            <span>{{ $completedCount }} / {{ $totalTasksCount }} مهمة</span>
        </div>
    </div>

    <!-- Convert Points to Balance Section -->
    <div class="glass-card rounded-3xl p-6 sm:p-8 border border-emerald-500/30 bg-emerald-950/10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-md">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 inline-block mb-2">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i> تحويل فوري للرصيد
                </span>
                <h3 class="text-xl font-bold text-white mb-2">استبدال النقاط برصيد محفظة USDT</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    سعر التحويل: كل <strong class="text-amber-400 font-mono">{{ $pointsPerDollar }}</strong> نقطة = <strong class="text-emerald-400 font-mono">$1.00 USDT</strong>. الحد الأدنى للتحويل هو <strong class="text-white font-mono">{{ $minConversionPoints }}</strong> نقطة.
                </p>
            </div>

            <!-- Interactive Conversion Form -->
            <form action="{{ route('tasks.convert') }}" method="POST" class="bg-black/60 p-5 rounded-2xl border border-white/10 flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                @csrf
                <div>
                    <label class="block text-[11px] text-slate-400 font-bold mb-1">النقاط المراد استبدالها</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="points" x-model.number="pointsToConvert"
                            min="{{ $minConversionPoints }}" max="{{ $user->points }}" step="50"
                            class="bg-dark-900 border border-white/10 rounded-xl px-3 py-2 text-sm text-white font-mono w-32 focus:outline-none focus:border-emerald-500 text-left dir-ltr">
                        <button type="button" @click="pointsToConvert = userPoints" class="px-2 py-1 bg-white/5 hover:bg-white/10 rounded-lg text-[10px] text-slate-300 border border-white/10">
                            الكل
                        </button>
                    </div>
                </div>

                <div class="text-center sm:text-right">
                    <span class="block text-[11px] text-slate-400 font-bold mb-1">الرصيد المستلم</span>
                    <div class="text-xl font-black font-outfit text-emerald-400 font-mono">
                        $<span x-text="calculatedUsdt"></span> <span class="text-xs">USDT</span>
                    </div>
                </div>

                <div class="pt-4 sm:pt-0">
                    <button type="submit"
                        :disabled="userPoints < {{ $minConversionPoints }} || pointsToConvert > userPoints"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs shadow-lg shadow-emerald-600/30 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> تحويل الآن
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Available Daily Tasks List -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-white flex items-center gap-2">
                <i class="fa-solid fa-list-check text-pink-400"></i> المهمات اليومية المتاحة
            </h2>
            <span class="text-xs text-slate-400">تتجدد المهام يومياً في منتصف الليل</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($tasks as $task)
                @php
                    $isCompletedToday = in_array($task->id, $completedTaskIdsToday);
                @endphp
                <div class="glass-card rounded-2xl p-5 border border-white/10 flex flex-col justify-between hover:border-pink-500/30 transition {{ $isCompletedToday ? 'opacity-80 bg-white/[0.02]' : '' }}">
                    <div>
                        <!-- Header / Type & Points -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white/5 border border-white/10 text-slate-300 flex items-center gap-1.5">
                                <i class="fa-solid {{ $task->type_icon }}"></i>
                                <span>{{ $task->formatted_type }}</span>
                            </span>

                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-amber-500/10 text-amber-300 border border-amber-500/20">
                                +{{ $task->points_reward }} نقطة
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-sm font-bold text-white mb-2">{{ $task->title }}</h3>

                        <!-- Instructions -->
                        <p class="text-xs text-slate-400 leading-relaxed mb-4">
                            {{ $task->instructions ?? 'قم بفتح الرابط وتنفيذ التفاعل المطلوب ثم أكد إنجاز المهمة.' }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 border-t border-white/10 flex items-center justify-between gap-3">
                        <a href="{{ $task->target_url }}" target="_blank" class="px-3 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-pink-400 hover:text-pink-300 text-xs font-bold border border-white/10 transition flex items-center gap-1.5 shrink-0">
                            <i class="fa-brands fa-instagram"></i>
                            <span>فتح الرابط</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                        </a>

                        @if($isCompletedToday)
                            <span class="px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check"></i> مكتملة اليوم
                            </span>
                        @else
                            <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-check"></i> تأكيد واستلام النقاط
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 glass-card rounded-2xl border border-white/10 text-slate-400 text-xs">
                    لا توجد مهمات متاحة حالياً. تفقد الصفحة لاحقاً!
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Conversions History Table -->
    @if($recentConversions->count() > 0)
        <div class="glass-card rounded-3xl border border-white/10 overflow-hidden">
            <div class="bg-white/5 px-6 py-4 border-b border-white/10 flex items-center justify-between">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-400"></i> سجل استبدال النقاط الأخير
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-black/50 text-slate-400">
                        <tr>
                            <th class="py-3 px-6">النقاط المستبدلة</th>
                            <th class="py-3 px-4 text-center">الرصيد المضاف</th>
                            <th class="py-3 px-6 text-left">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 font-mono">
                        @foreach($recentConversions as $conv)
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-3 px-6 text-amber-400 font-bold">-{{ number_format($conv->points_spent) }} نقطة</td>
                                <td class="py-3 px-4 text-center text-emerald-400 font-bold text-sm">+${{ number_format($conv->balance_credited, 2) }} USDT</td>
                                <td class="py-3 px-6 text-left text-slate-400 text-[11px]">{{ $conv->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
