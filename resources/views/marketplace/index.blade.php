@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Header & Intro -->
    <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden">
        <div class="max-w-3xl">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-pink-500/10 text-pink-400 border border-pink-500/20 inline-block mb-3">
                <i class="fa-solid fa-at"></i> Instagram Marketplace
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-white mb-3">متجر يوزرات انستقرام الفخمة والمميزة</h1>
            <p class="text-slate-300 text-sm leading-relaxed">
                تصفح تشكيلتنا الحصرية من اليوزرات الرباعية، شبه الثلاثية، والحسابات القديمة التأسيس. الشراء فوري بخصم من رصيد محفظتك مع تسليم تفاصيل الحساب والإيميل الأساسي فوراً.
            </p>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="glass-card rounded-2xl p-6 border border-white/10">
        <form action="{{ route('marketplace.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-slate-300 mb-1.5">البحث عن يوزر محدد</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="اكتب اسم اليوزر (مثال: x_99)"
                        class="w-full bg-black/40 border border-white/10 rounded-xl pr-10 pl-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition">
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">نوع وتصنيف اليوزر</label>
                <select name="type" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-pink-500 transition">
                    <option value="all">جميع الأنواع</option>
                    <option value="quad" {{ request('type') == 'quad' ? 'selected' : '' }}>رباعي (4 Letters)</option>
                    <option value="tri_semi" {{ request('type') == 'tri_semi' ? 'selected' : '' }}>شبه ثلاثي (Semi 3L)</option>
                    <option value="vintage" {{ request('type') == 'vintage' ? 'selected' : '' }}>قديم 2012-2015</option>
                    <option value="verified" {{ request('type') == 'verified' ? 'selected' : '' }}>موثق رسمي (Verified)</option>
                    <option value="special" {{ request('type') == 'special' ? 'selected' : '' }}>يوزر مميز</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">الترتيب حسب</label>
                <select name="sort" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-pink-500 transition">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث إضافة</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>الأقل سعراً</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>الأعلى سعراً</option>
                </select>
            </div>

            <!-- Submit Filter Button -->
            <div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-sm shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter"></i> تطبيق التصفية
                </button>
            </div>
        </form>
    </div>

    <!-- Usernames Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($usernames as $item)
            <div class="glass-card rounded-3xl p-5 border border-white/10 flex flex-col justify-between hover:-translate-y-1.5 transition-all duration-300 glow-hover relative overflow-hidden group">
                <!-- Status & Badges -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-pink-500/10 text-pink-400 border border-pink-500/20">
                            {{ $item->formatted_type }}
                        </span>
                        {!! $item->status_badge !!}
                    </div>

                    <!-- Instagram Card Preview -->
                    <div class="bg-black/50 rounded-2xl p-5 border border-white/5 text-center mb-4 relative">
                        <div class="w-12 h-12 mx-auto rounded-full insta-gradient flex items-center justify-center text-white text-xl mb-2 shadow">
                            <i class="fa-brands fa-instagram"></i>
                        </div>
                        <h3 class="text-xl font-black font-outfit text-white tracking-wide dir-ltr">
                            @<span>{{ $item->username }}</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">
                            <i class="fa-solid fa-users text-pink-400 ml-1"></i> {{ number_format($item->followers_count) }} متابع
                            @if($item->creation_year)
                                <span class="mx-1">•</span> <i class="fa-regular fa-clock text-amber-400 ml-0.5"></i> {{ $item->creation_year }}
                            @endif
                        </p>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed mb-4">
                        {{ $item->description ?? 'يوزر مميز ونظيف متاح للتسليم الفوري مع بيانات الحساب الأساسية.' }}
                    </p>
                </div>

                <!-- Price and Action -->
                <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 block">السعر</span>
                        <span class="text-xl font-black font-outfit text-white">${{ number_format($item->price, 2) }} <span class="text-xs text-emerald-400 font-mono">USDT</span></span>
                    </div>

                    @if($item->status === 'available')
                        <a href="{{ route('marketplace.show', $item->id) }}" class="px-4 py-2 rounded-xl insta-gradient text-white text-xs font-bold shadow-md shadow-pink-500/20 hover:opacity-90 transition flex items-center gap-1.5">
                            <span>شراء</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    @else
                        <button disabled class="px-4 py-2 rounded-xl bg-white/5 text-slate-500 text-xs font-bold cursor-not-allowed">
                            غير متاح
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 glass-card rounded-3xl border border-white/10">
                <i class="fa-solid fa-magnifying-glass text-4xl text-slate-600 mb-3"></i>
                <h3 class="text-lg font-bold text-white mb-1">لم يتم العثور على يوزرات تطابق بحثك</h3>
                <p class="text-xs text-slate-400">جرب البحث بكلمات أخرى أو اختر تصنيفاً مختلفاً.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $usernames->links() }}
    </div>

</div>
@endsection
