@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white">يوزراتي وحساباتي المشتراة</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">سجل كامل بجميع اليوزرات التي اشتريتها وبيانات الدخول المسلمة لك</p>
        </div>
        <a href="{{ route('marketplace.index') }}" class="px-5 py-2.5 rounded-xl insta-gradient text-white text-xs font-bold shadow-md hover:opacity-95 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> شراء يوزر جديد
        </a>
    </div>

    <!-- Purchases Table / Cards -->
    <div class="glass-card rounded-3xl border border-white/10 overflow-hidden">
        @if($orders->count() > 0)
            <div class="divide-y divide-white/10">
                @foreach($orders as $order)
                    <div x-data="{ showDetails: false }" class="p-6 transition hover:bg-white/[0.02]">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <!-- Left: Username info -->
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl insta-gradient flex items-center justify-center text-white text-2xl shrink-0 shadow">
                                    <i class="fa-brands fa-instagram"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-xl font-black font-outfit text-white dir-ltr">
                                            @<span>{{ $order->username->username ?? 'محذوف' }}</span>
                                        </h3>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            مكتمل ومسلم
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-400 mt-1 flex flex-wrap items-center gap-3">
                                        <span>رقم الطلب: <strong class="text-slate-200 font-mono">#{{ $order->order_number }}</strong></span>
                                        <span>•</span>
                                        <span>السعر: <strong class="text-emerald-400 font-mono">${{ number_format($order->price, 2) }} USDT</strong></span>
                                        <span>•</span>
                                        <span>تاريخ الشراء: {{ $order->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Action Button -->
                            <button @click="showDetails = !showDetails" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-bold text-pink-400 hover:text-white transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-key"></i>
                                <span x-text="showDetails ? 'إخفاء بيانات الدخول' : 'عرض بيانات الحساب'">عرض بيانات الحساب</span>
                                <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': showDetails }"></i>
                            </button>
                        </div>

                        <!-- Expandable Secret Credentials -->
                        <div x-show="showDetails" x-cloak class="mt-4 pt-4 border-t border-white/10">
                            <div class="bg-black/60 rounded-2xl p-5 border border-amber-500/30">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-amber-400 flex items-center gap-1.5">
                                        <i class="fa-solid fa-lock"></i> تفاصيل الحساب والإيميل المسلمة:
                                    </span>
                                    <button type="button" @click="navigator.clipboard.writeText($refs.detailsText_{{ $order->id }}.innerText); alert('تم النسخ!');" class="text-xs text-pink-400 hover:text-pink-300">
                                        <i class="fa-regular fa-copy"></i> نسخ البيانات
                                    </button>
                                </div>
                                <div x-ref="detailsText_{{ $order->id }}" class="text-xs text-slate-200 font-mono whitespace-pre-wrap dir-ltr text-left">
{{ $order->delivery_details }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-white/10">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-16 p-6">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-white/5 text-slate-500 flex items-center justify-center text-3xl mb-4">
                    <i class="fa-solid fa-at"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">لم تقم بشراء أي يوزر حتى الآن</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mb-6">تصفح متجر اليوزرات الآن واختر اليوزر المناسب لمشروعك بتسليم فوري وآمن.</p>
                <a href="{{ route('marketplace.index') }}" class="px-6 py-3 rounded-xl insta-gradient text-white text-xs font-bold shadow-md hover:opacity-95 transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-bag-shopping"></i> تصفح متجر اليوزرات
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
