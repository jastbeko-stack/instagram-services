@extends('layouts.app')

@section('content')
<div class="space-y-14 sm:space-y-24">

    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-8 sm:pt-12 pb-14 sm:pb-20">
        <!-- Glow background effects -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[350px] sm:w-[600px] h-[350px] sm:h-[600px] bg-gradient-to-tr from-pink-600/20 via-purple-600/20 to-amber-500/10 blur-[100px] sm:blur-[130px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-1.5 rounded-full bg-pink-500/10 border border-pink-500/20 text-pink-300 text-[11px] sm:text-xs font-semibold mb-5 sm:mb-6">
                <i class="fa-solid fa-fire text-amber-400"></i> المنصة العربية الأولى المتكاملة لخدمات انستقرام
            </div>

            <!-- Main Heading -->
            <h1 class="text-3xl sm:text-5xl lg:text-7xl font-black text-white tracking-tight leading-[1.25] mb-5 sm:mb-6 max-w-4xl mx-auto">
                امتلك أفخم <span class="insta-gradient-text">اليوزرات</span> وضاعف <span class="text-amber-400">متابعيك</span> بثوانٍ
            </h1>

            <!-- Subtitle -->
            <p class="text-slate-300 text-sm sm:text-lg lg:text-xl max-w-2xl mx-auto mb-8 sm:mb-10 leading-relaxed px-2">
                متجر متخصص لبيع يوزرات انستقرام الرباعية والشبه ثلاثية والقديمة بتسليم فوري مع الإيميل الأساسي، وسيرفرات SMM فائقة السرعة مع مهام يومية لكسب النقاط والدفع بالـ USDT.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 mb-12 sm:mb-16 w-full max-w-md sm:max-w-none mx-auto">
                <a href="{{ route('marketplace.index') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl insta-gradient text-white font-bold text-sm sm:text-base shadow-xl shadow-pink-500/25 hover:scale-105 transition-all flex items-center justify-center gap-2.5">
                    <i class="fa-solid fa-at text-lg"></i>
                    <span>تصفح متجر اليوزرات</span>
                </a>
                <a href="{{ route('smm.index') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl glass-card hover:bg-white/10 text-white font-bold text-sm sm:text-base transition-all border border-white/10 flex items-center justify-center gap-2.5">
                    <i class="fa-solid fa-bolt text-yellow-400 text-lg"></i>
                    <span>طلب زيادة متابعين</span>
                </a>
                <a href="{{ route('tasks.index') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 font-bold text-sm sm:text-base border border-amber-500/30 transition-all flex items-center justify-center gap-2.5">
                    <i class="fa-solid fa-gift text-amber-400 text-lg"></i>
                    <span>أكمل مهام واكسب رصيد</span>
                </a>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 max-w-4xl mx-auto">
                <div class="glass-card p-3.5 sm:p-5 rounded-2xl text-center">
                    <div class="text-2xl sm:text-3xl font-black font-outfit text-white mb-0.5">+{{ $stats['total_usernames_sold'] }}</div>
                    <div class="text-[11px] sm:text-xs text-slate-400 font-medium">يوزر وحساب تم بيعه</div>
                </div>
                <div class="glass-card p-3.5 sm:p-5 rounded-2xl text-center">
                    <div class="text-2xl sm:text-3xl font-black font-outfit text-pink-400 mb-0.5">+{{ $stats['total_smm_orders'] }}</div>
                    <div class="text-[11px] sm:text-xs text-slate-400 font-medium">طلب متابعين منفذ</div>
                </div>
                <div class="glass-card p-3.5 sm:p-5 rounded-2xl text-center">
                    <div class="text-2xl sm:text-3xl font-black font-outfit text-amber-400 mb-0.5">{{ $stats['available_usernames'] }}</div>
                    <div class="text-[11px] sm:text-xs text-slate-400 font-medium">يوزر متوفر للشراء</div>
                </div>
                <div class="glass-card p-3.5 sm:p-5 rounded-2xl text-center">
                    <div class="text-2xl sm:text-3xl font-black font-outfit text-emerald-400 mb-0.5">100%</div>
                    <div class="text-[11px] sm:text-xs text-slate-400 font-medium">تسليم آمن وفوري</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Usernames Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <span class="text-xs text-pink-400 font-bold uppercase tracking-wider block mb-1">Instagram Marketplace</span>
                <h2 class="text-3xl font-black text-white">يوزرات مميزة متاحة للشراء الآن</h2>
            </div>
            <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-pink-400 hover:text-pink-300">
                عرض كل المعروض في المتجر <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredUsernames as $item)
                <div class="glass-card rounded-3xl p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1.5 glow-hover flex flex-col justify-between">
                    <!-- Top Badge & Type -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-pink-500/10 text-pink-400 border border-pink-500/20">
                                {{ $item->formatted_type }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">
                                <i class="fa-regular fa-calendar ml-1"></i> {{ $item->creation_year ?? 'قديم' }}
                            </span>
                        </div>

                        <!-- Username Display Card -->
                        <div class="bg-black/40 rounded-2xl p-5 border border-white/5 text-center mb-5 group">
                            <div class="w-12 h-12 mx-auto rounded-full insta-gradient flex items-center justify-center text-white text-xl mb-3 shadow-md">
                                <i class="fa-brands fa-instagram"></i>
                            </div>
                            <div class="text-2xl font-black tracking-wide text-white font-outfit dir-ltr">
                                @<span>{{ $item->username }}</span>
                            </div>
                            <div class="mt-2 text-xs text-slate-400 flex items-center justify-center gap-4">
                                <span><i class="fa-solid fa-users text-pink-400 ml-1"></i> {{ number_format($item->followers_count) }} متابع</span>
                                <span><i class="fa-solid fa-envelope-circle-check text-emerald-400 ml-1"></i> إيميل أساسي</span>
                            </div>
                        </div>

                        <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed mb-6">
                            {{ $item->description ?? 'يوزر مميز ونظيف متاح للتسليم الفوري مع بيانات الحساب الأساسية.' }}
                        </p>
                    </div>

                    <!-- Price & Buy Action -->
                    <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">السعر المطلوب</span>
                            <span class="text-2xl font-black font-outfit text-white">${{ number_format($item->price, 2) }} <span class="text-xs text-emerald-400">USDT</span></span>
                        </div>
                        <a href="{{ route('marketplace.show', $item->id) }}" class="px-5 py-2.5 rounded-xl insta-gradient text-white font-bold text-xs shadow-md shadow-pink-500/20 hover:opacity-90 transition flex items-center gap-2">
                            <span>تفاصيل وشراء</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    لا توجد يوزرات معروضة حالياً. تابعنا قريباً!
                </div>
            @endforelse
        </div>
    </section>

    <!-- SMM Services Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card rounded-3xl p-8 lg:p-12 border border-white/10 relative overflow-hidden">
            <div class="max-w-3xl mb-10">
                <span class="text-xs text-amber-400 font-bold uppercase tracking-wider block mb-2">SMM Fast Services</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">خدمات زيادة المتابعين والتفاعل الفوري</h2>
                <p class="text-slate-300 text-sm leading-relaxed">
                    نوفر لك أفضل سيرفرات المتابعين واللايكات والمشاهدات بدقة وسرعة لا تقارن. يمكنك طلب الكمية التي تناسبك فورياً ومتابعة حالة طلبك خطوة بخطوة.
                </p>
            </div>

            <!-- Categories Tabs / Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach($categories as $cat)
                    <div class="bg-black/30 rounded-2xl p-5 border border-white/5 hover:border-pink-500/30 transition group">
                        <div class="w-12 h-12 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center text-xl mb-4 group-hover:bg-pink-600 group-hover:text-white transition">
                            <i class="fa-solid {{ $cat->icon ?? 'fa-bolt' }}"></i>
                        </div>
                        <h3 class="text-base font-bold text-white mb-2">{{ $cat->name_ar }}</h3>
                        <p class="text-xs text-slate-400 mb-4">{{ $cat->services->count() }} خدمات متوفرة بأسعار تبدأ من <span class="text-emerald-400 font-mono font-bold">${{ number_format($cat->services->min('price_per_1k') ?? 0.5, 2) }}</span> / 1K</p>
                        <a href="{{ route('smm.index') }}?category={{ $cat->id }}" class="text-xs font-bold text-pink-400 hover:text-pink-300 flex items-center gap-1.5">
                            طلب الخدمة <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="text-center pt-4">
                <a href="{{ route('smm.index') }}" class="inline-flex items-center gap-3 px-8 py-3.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-sm shadow-lg shadow-pink-600/30 transition">
                    <i class="fa-solid fa-cart-plus"></i> افتح لوحة تقديم الطلبات الآن
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us / Features -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs text-pink-400 font-bold uppercase tracking-wider block mb-2">لماذا انستازون؟</span>
            <h2 class="text-3xl font-black text-white">أمان تام، سرعة قياسية، ودفع مشفر سهل</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-card p-8 rounded-3xl text-center">
                <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-2xl mx-auto mb-6">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">تسليم فوري ومضمون 100%</h3>
                <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                    اليوزرات المباعة يتم تسليم بيانات الدخول والإيميل الأساسي فور إتمام عملية الشراء في صفحة طلبك الخاصة دون انتظار.
                </p>
            </div>

            <div class="glass-card p-8 rounded-3xl text-center">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl mx-auto mb-6">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">دفع آمن بالـ USDT</h3>
                <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                    اشحن محفظتك بخصوصية تامة عبر شبكات TRC20 و BEP20 بدون عمولات بنكية مع تأكيد فوري للحوالة.
                </p>
            </div>

            <div class="glass-card p-8 rounded-3xl text-center">
                <div class="w-16 h-16 rounded-2xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center text-2xl mx-auto mb-6">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">دعم فني مستمر 24/7</h3>
                <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                    فريق دعم فني متواجد على مدار الساعة عبر تليجرام للإجابة على استفساراتك وضمان استلام طلباتك بسلاسة.
                </p>
            </div>
        </div>
    </section>

</div>
@endsection
