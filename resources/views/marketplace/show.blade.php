@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-white transition">الرئيسية</a>
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
        <a href="{{ route('marketplace.index') }}" class="hover:text-white transition">متجر اليوزرات</a>
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
        <span class="text-pink-400">@ {{ $username->username }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Main Left Column (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Username Main Display Card -->
            <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-pink-500/10 text-pink-400 border border-pink-500/20">
                        {{ $username->formatted_type }}
                    </span>
                    {!! $username->status_badge !!}
                </div>

                <div class="bg-black/60 rounded-3xl p-8 border border-white/10 text-center mb-8 relative">
                    <div class="w-20 h-20 mx-auto rounded-3xl insta-gradient flex items-center justify-center text-white text-4xl mb-4 shadow-xl shadow-pink-500/20">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-black font-outfit text-white tracking-wider dir-ltr mb-3">
                        @<span>{{ $username->username }}</span>
                    </h1>
                    <div class="flex items-center justify-center gap-6 text-sm text-slate-300">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-users text-pink-400"></i> {{ number_format($username->followers_count) }} متابع</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-calendar-check text-amber-400"></i> سنة التأسيس: {{ $username->creation_year ?? 'غير محدد' }}</span>
                    </div>
                </div>

                <!-- Description & Details -->
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-pink-400"></i> تفاصيل ومميزات الحساب
                    </h3>
                    <p class="text-sm text-slate-300 leading-relaxed bg-white/5 p-5 rounded-2xl border border-white/5">
                        {{ $username->description ?? 'يوزر مميز ونظيف جداً بدون أي مخالفات على السياسات، يشمل الإيميل الأساسي للحساب، يتم تسليم بيانات تسجيل الدخول تلقائياً للمشتري فوراً بعد الدفع.' }}
                    </p>
                </div>
            </div>

            <!-- Guarantee Features -->
            <div class="glass-card rounded-3xl p-6 border border-white/10 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-envelope-circle-check"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">إيميل أساسي OGE</h4>
                        <p class="text-[11px] text-slate-400">تسليم الإيميل الأول الأصلي</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">ضمان ضد السحب</h4>
                        <p class="text-[11px] text-slate-400">حسابات أصلية ومفحوصة</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-bolt-lightning"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">تسليم فوري 100%</h4>
                        <p class="text-[11px] text-slate-400">البيانات تظهر مباشرة</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar / Purchase Box (1 Col) -->
        <div class="space-y-6">

            <div class="glass-card rounded-3xl p-6 border border-white/10 sticky top-28 shadow-2xl">
                <div class="text-center pb-6 border-b border-white/10">
                    <span class="text-xs text-slate-400 block mb-1">السعر الإجمالي للشراء</span>
                    <div class="text-4xl font-black font-outfit text-white">
                        ${{ number_format($username->price, 2) }}
                        <span class="text-sm text-emerald-400 font-mono font-bold">USDT</span>
                    </div>
                </div>

                <div class="py-6 space-y-4">
                    @auth
                        <!-- User Balance Check -->
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 text-xs space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">رصيدك في المحفظة:</span>
                                <span class="font-bold text-white font-outfit">${{ number_format(Auth::user()->balance, 2) }} USDT</span>
                            </div>

                            @if(Auth::user()->hasSufficientBalance($username->price))
                                <div class="text-emerald-400 font-medium flex items-center gap-1.5 pt-1 border-t border-white/10">
                                    <i class="fa-solid fa-circle-check"></i> رصيدك كافٍ لإتمام عملية الشراء فوراً!
                                </div>
                            @else
                                <div class="text-rose-400 font-medium flex items-center gap-1.5 pt-1 border-t border-white/10">
                                    <i class="fa-solid fa-circle-exclamation"></i> رصيدك الحالي غير كافٍ.
                                </div>
                            @endif
                        </div>

                        @if($username->status === 'available')
                            @if(Auth::user()->hasSufficientBalance($username->price))
                                <form action="{{ route('marketplace.buy', $username->id) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من رغبتك في شراء اليوزر @ {{ $username->username }} بخصم ${{ $username->price }} USDT من رصيدك؟')">
                                    @csrf
                                    <button type="submit" class="w-full py-4 rounded-2xl insta-gradient text-white font-bold text-base shadow-xl shadow-pink-500/25 hover:opacity-95 transition flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                        <span>تأكيد الشراء الفوري الآن</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('wallet.deposit') }}" class="w-full py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-xl shadow-emerald-600/25 transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-wallet"></i>
                                    <span>شحن المحفظة بـ USDT للمتابعة</span>
                                </a>
                            @endif
                        @else
                            <button disabled class="w-full py-4 rounded-2xl bg-white/5 text-slate-500 font-bold text-sm cursor-not-allowed">
                                تم بيع هذا الحساب مسبقاً
                            </button>
                        @endif

                    @else
                        <!-- Guest Prompt -->
                        <div class="text-center p-4 rounded-2xl bg-pink-500/10 border border-pink-500/20 text-xs text-slate-300 space-y-3">
                            <p>يجب تسجيل الدخول أو إنشاء حساب لإتمام عملية شراء اليوزر.</p>
                            <a href="{{ route('login') }}" class="block w-full py-3 rounded-xl insta-gradient text-white font-bold shadow-md hover:opacity-95 transition">
                                تسجيل الدخول للشراء
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Instant Delivery Notice -->
                <div class="p-3.5 rounded-2xl bg-black/40 border border-white/5 text-[11px] text-slate-400 space-y-1">
                    <p class="font-bold text-white flex items-center gap-1.5">
                        <i class="fa-solid fa-lock text-amber-400"></i> طريقة التسليم:
                    </p>
                    <p>بمجرد النقر على الشراء سيتم خصم المبلغ من رصيدك فورياً ونقلك لصفحة استلام الإيميل، الباسورد، وأكواد الأمان.</p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
