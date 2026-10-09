@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <div class="glass-card rounded-3xl p-8 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-pink-500/10 text-pink-400 border border-pink-500/20 inline-block mb-3">
                <i class="fa-solid fa-list-check"></i> Pricing Table
            </span>
            <h1 class="text-3xl font-black text-white mb-2">قائمة أسعار خدمات المتابعين والتفاعل</h1>
            <p class="text-xs sm:text-sm text-slate-300">
                جميع الأسعار معلنة بالـ USDT لكل 1,000 وحدة مع توضيح الحدود الدنيا والقصوى ونوع التنفيذ.
            </p>
        </div>
        <a href="{{ route('smm.index') }}" class="px-6 py-3.5 rounded-xl insta-gradient text-white text-xs font-bold shadow-lg shadow-pink-500/20 hover:opacity-95 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-cart-plus"></i> تقديم طلب جديد
        </a>
    </div>

    @foreach($categories as $category)
        <div class="glass-card rounded-3xl border border-white/10 overflow-hidden">
            <!-- Category Header -->
            <div class="bg-white/5 px-6 py-4 border-b border-white/10 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center text-base">
                    <i class="fa-solid {{ $category->icon ?? 'fa-bolt' }}"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-white">{{ $category->name_ar }}</h2>
                    <span class="text-xs text-slate-400 font-mono">{{ $category->name_en }}</span>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-black/40 text-slate-400 border-b border-white/10">
                        <tr>
                            <th class="py-3 px-6 font-bold">اسم الخدمة</th>
                            <th class="py-3 px-4 font-bold text-center">السعر / 1K</th>
                            <th class="py-3 px-4 font-bold text-center">الحد الأدنى</th>
                            <th class="py-3 px-4 font-bold text-center">الحد الأقصى</th>
                            <th class="py-3 px-4 font-bold text-center">نوع التنفيذ</th>
                            <th class="py-3 px-6 font-bold text-left">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($category->services as $service)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-white mb-0.5">{{ $service->name_ar }}</div>
                                    <div class="text-[11px] text-slate-400 line-clamp-1">{{ $service->description ?? 'جودة ممتازة وسرعة عالية.' }}</div>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-bold text-emerald-400 text-sm">
                                    ${{ number_format($service->price_per_1k, 4) }}
                                </td>
                                <td class="py-4 px-4 text-center font-mono text-slate-300">
                                    {{ number_format($service->min_quantity) }}
                                </td>
                                <td class="py-4 px-4 text-center font-mono text-slate-300">
                                    {{ number_format($service->max_quantity) }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($service->execution_type === 'api')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">تلقائي API</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">يدوي مدقق</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-left">
                                    <a href="{{ route('smm.index') }}?category={{ $category->id }}&service={{ $service->id }}" class="px-3 py-1.5 rounded-lg bg-pink-600/20 hover:bg-pink-600 text-pink-400 hover:text-white transition font-bold text-[11px] inline-flex items-center gap-1">
                                        طلب <i class="fa-solid fa-arrow-left text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-500">لا توجد خدمات متاحة في هذا التصنيف حالياً.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

</div>
@endsection
