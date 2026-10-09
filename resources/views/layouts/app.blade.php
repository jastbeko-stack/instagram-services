<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'انستازون | متجر يوزرات انستقرام وخدمات زيادة المتابعين' }}</title>
    <!-- Google Fonts: Cairo & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fdf2f8',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                        },
                        dark: {
                            900: '#07090e',
                            800: '#0f1422',
                            700: '#171f33',
                            600: '#232f4c'
                        }
                    },
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #07090e;
            color: #f1f5f9;
            -webkit-tap-highlight-color: transparent;
        }
        .insta-gradient {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }
        .insta-gradient-text {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-card {
            background: rgba(15, 20, 34, 0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glow-hover:hover {
            box-shadow: 0 0 25px rgba(220, 39, 67, 0.25);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-pink-500 selection:text-white pb-24 md:pb-0">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-purple-900/60 via-pink-900/60 to-amber-900/60 border-b border-white/10 text-[11px] sm:text-xs py-2 px-3 sm:px-4 text-center flex items-center justify-center gap-2">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
        <span class="font-medium text-slate-200 truncate">
            {{ \App\Models\Setting::get('notice_banner', '✨ متجر انستازون - تسليم فوري لليوزرات وزيادة المتابعين مع الدفع بالـ USDT') }}
        </span>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 glass-card border-b border-white/10 shadow-lg" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo & Brand + Mobile Hamburger -->
                <div class="flex items-center gap-2.5 sm:gap-4">
                    <!-- Mobile Hamburger Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white focus:outline-none" aria-label="القائمة">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>

                    <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group">
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl insta-gradient flex items-center justify-center shadow-lg shadow-pink-500/20 group-hover:scale-105 transition-transform">
                            <i class="fa-brands fa-instagram text-xl sm:text-2xl text-white"></i>
                        </div>
                        <div>
                            <span class="text-xl sm:text-2xl font-black tracking-tight text-white block leading-tight">
                                انستا<span class="insta-gradient-text">زون</span>
                            </span>
                            <span class="text-[9px] sm:text-[10px] text-slate-400 tracking-wider font-outfit uppercase">InstaZone Services</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center gap-1 bg-white/5 p-1.5 rounded-2xl border border-white/10">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('home') ? 'bg-pink-600 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-house ml-1.5 opacity-80"></i> الرئيسية
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('marketplace.*') ? 'bg-pink-600 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-at ml-1.5 text-pink-400"></i> متجر اليوزرات
                        <span class="mr-1 px-1.5 py-0.5 text-[10px] bg-amber-500/20 text-amber-300 rounded-md border border-amber-500/30">مميز</span>
                    </a>
                    <a href="{{ route('smm.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('smm.index') ? 'bg-pink-600 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-bolt ml-1.5 text-yellow-400"></i> زيادة المتابعين والتفاعل
                    </a>
                    <a href="{{ route('tasks.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('tasks.*') ? 'bg-amber-600 text-white shadow-md' : 'text-amber-300 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-gift ml-1.5 text-amber-400"></i> المهمات والمكافآت
                        <span class="mr-1 px-1.5 py-0.5 text-[10px] bg-emerald-500/20 text-emerald-300 rounded-md border border-emerald-500/30">كسب رصيد</span>
                    </a>
                    <a href="{{ route('smm.servicesList') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('smm.servicesList') ? 'bg-pink-600 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <i class="fa-solid fa-list-check ml-1.5 opacity-80"></i> قائمة الأسعار
                    </a>
                </nav>

                <!-- User Controls / Auth -->
                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <!-- User Points Counter (Desktop) -->
                        <a href="{{ route('tasks.index') }}" class="hidden lg:flex items-center gap-2 bg-amber-500/10 border border-amber-500/30 hover:border-amber-500/60 px-3 py-2 rounded-xl text-amber-300 transition group" title="رصيدك من النقاط المكتسبة">
                            <i class="fa-solid fa-coins text-sm text-amber-400 group-hover:rotate-12 transition-transform"></i>
                            <div class="text-right">
                                <span class="text-[9px] block text-amber-300/70 font-semibold leading-none">النقاط</span>
                                <span class="text-xs font-bold font-mono text-white">{{ number_format(Auth::user()->points) }} <span class="text-[10px] text-amber-400">نقطة</span></span>
                            </div>
                        </a>

                        <!-- User Balance Indicator (Compact on mobile) -->
                        <a href="{{ route('wallet.index') }}" class="flex items-center gap-1.5 sm:gap-2.5 bg-emerald-500/10 border border-emerald-500/30 hover:border-emerald-500/60 px-2 sm:px-3.5 py-1.5 sm:py-2 rounded-xl text-emerald-400 transition group">
                            <i class="fa-solid fa-wallet text-xs sm:text-sm text-emerald-400 group-hover:scale-110 transition-transform"></i>
                            <div class="text-right">
                                <span class="text-[9px] hidden sm:block text-emerald-300/70 font-semibold leading-none">الرصيد المتاح</span>
                                <span class="text-xs sm:text-sm font-bold font-outfit text-white">${{ number_format(Auth::user()->balance, 2) }}</span>
                            </div>
                            <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg bg-emerald-500/20 flex items-center justify-center text-[10px] sm:text-xs text-emerald-300">
                                <i class="fa-solid fa-plus text-[9px]"></i>
                            </span>
                        </a>

                        <!-- User Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-1 sm:gap-2 p-1 sm:p-1.5 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition text-right">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-gradient-to-tr from-pink-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden lg:block">
                                    <div class="text-xs font-bold text-white leading-tight">{{ Str::limit(Auth::user()->name, 14) }}</div>
                                    <div class="text-[10px] text-slate-400">{{ Auth::user()->is_admin ? 'مدير المنصة' : 'عميل' }}</div>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-0.5"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak class="absolute left-0 mt-2 w-60 rounded-2xl glass-card border border-white/10 shadow-2xl py-2 z-50 text-sm">
                                <div class="px-4 py-2.5 border-b border-white/10">
                                    <p class="text-xs text-slate-400">مسجل الدخول كـ</p>
                                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->email }}</p>
                                    <div class="mt-2 flex items-center justify-between text-[11px] font-mono bg-black/40 p-2 rounded-lg border border-white/5 text-amber-300">
                                        <span>نقاط المكافآت:</span>
                                        <strong>{{ number_format(Auth::user()->points) }} نقطة</strong>
                                    </div>
                                </div>

                                @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-amber-300 hover:bg-amber-500/10 transition">
                                        <i class="fa-solid fa-shield-halved w-5"></i> لوحة التحكم الإدارية
                                    </a>
                                @endif

                                <a href="{{ route('tasks.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-amber-300 hover:text-white hover:bg-white/5 transition">
                                    <i class="fa-solid fa-gift w-5 text-amber-400"></i> المهمات وكسب النقاط
                                </a>

                                <a href="{{ route('marketplace.myUsernames') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-300 hover:text-white hover:bg-white/5 transition">
                                    <i class="fa-solid fa-tags w-5 text-pink-400"></i> يوزراتي المشتراة
                                </a>

                                <a href="{{ route('smm.myOrders') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-300 hover:text-white hover:bg-white/5 transition">
                                    <i class="fa-solid fa-clock-rotate-left w-5 text-yellow-400"></i> طلبات المتابعين
                                </a>

                                <a href="{{ route('wallet.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-300 hover:text-white hover:bg-white/5 transition">
                                    <i class="fa-solid fa-wallet w-5 text-emerald-400"></i> المحفظة وشحن USDT
                                </a>

                                <div class="border-t border-white/10 my-1"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-right flex items-center gap-2 px-4 py-2.5 text-rose-400 hover:bg-rose-500/10 transition">
                                        <i class="fa-solid fa-right-from-bracket w-5"></i> تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest Buttons -->
                        <a href="{{ route('login') }}" class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/5 transition">
                            دخول
                        </a>
                        <a href="{{ route('register') }}" class="px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-sm font-bold insta-gradient text-white shadow-md shadow-pink-500/20 hover:opacity-90 transition">
                            حساب جديد
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Slide-out Drawer Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden fixed inset-0 z-50 flex">
            <!-- Overlay Backdrop -->
            <div @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity"></div>

            <!-- Drawer Body -->
            <div class="relative w-4/5 max-w-xs bg-dark-900 border-l border-white/10 p-5 flex flex-col justify-between z-10 shadow-2xl overflow-y-auto">
                <div>
                    <!-- Drawer Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl insta-gradient flex items-center justify-center text-white">
                                <i class="fa-brands fa-instagram text-lg"></i>
                            </div>
                            <span class="text-lg font-black text-white">انستا<span class="insta-gradient-text">زون</span></span>
                        </div>
                        <button @click="mobileMenuOpen = false" class="p-2 text-slate-400 hover:text-white rounded-lg bg-white/5">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="space-y-1 text-sm font-semibold">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('home') ? 'bg-pink-600 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                            <i class="fa-solid fa-house w-5 text-center"></i> الرئيسية
                        </a>
                        <a href="{{ route('marketplace.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('marketplace.*') ? 'bg-pink-600 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                            <i class="fa-solid fa-at w-5 text-center text-pink-400"></i> متجر اليوزرات
                        </a>
                        <a href="{{ route('smm.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('smm.index') ? 'bg-pink-600 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                            <i class="fa-solid fa-bolt w-5 text-center text-yellow-400"></i> زيادة المتابعين والتفاعل
                        </a>
                        <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('tasks.*') ? 'bg-amber-600 text-white' : 'text-amber-300 hover:bg-white/5' }}">
                            <i class="fa-solid fa-gift w-5 text-center text-amber-400"></i> المهمات والمكافآت
                        </a>
                        <a href="{{ route('smm.servicesList') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('smm.servicesList') ? 'bg-pink-600 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                            <i class="fa-solid fa-list-check w-5 text-center"></i> قائمة الأسعار
                        </a>
                        <a href="{{ route('wallet.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('wallet.*') ? 'bg-emerald-600 text-white' : 'text-emerald-400 hover:bg-white/5' }}">
                            <i class="fa-solid fa-wallet w-5 text-center"></i> المحفظة وشحن USDT
                        </a>
                    </nav>
                </div>

                @auth
                    <div class="pt-4 border-t border-white/10 space-y-2">
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-amber-500/10 text-amber-300 font-bold text-xs border border-amber-500/20">
                                <i class="fa-solid fa-shield-halved"></i> لوحة التحكم الإدارية
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-500/10 text-rose-400 font-bold text-xs border border-rose-500/20">
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-3.5 sm:p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs sm:text-sm shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-3.5 sm:p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs sm:text-sm shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-400 shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-400 hover:text-rose-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs sm:text-sm shadow-lg">
                <div class="font-bold mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> يرجى تصحيح الأخطاء التالية:
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs text-rose-200">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation Bar (Smartphones App Bar) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-[#07090e]/95 backdrop-blur-lg border-t border-white/10 px-2 py-1.5 flex items-center justify-around shadow-2xl safe-area-pb">
        <a href="{{ route('home') }}" class="flex flex-col items-center py-1 px-3 rounded-xl transition {{ request()->routeIs('home') ? 'text-pink-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-house text-base"></i>
            <span class="text-[10px] mt-0.5">الرئيسية</span>
        </a>
        <a href="{{ route('marketplace.index') }}" class="flex flex-col items-center py-1 px-3 rounded-xl transition {{ request()->routeIs('marketplace.*') ? 'text-pink-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-at text-base"></i>
            <span class="text-[10px] mt-0.5">اليوزرات</span>
        </a>
        <a href="{{ route('tasks.index') }}" class="flex flex-col items-center py-1 px-3 rounded-xl transition relative {{ request()->routeIs('tasks.*') ? 'text-amber-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-gift text-base"></i>
            <span class="text-[10px] mt-0.5">المهام</span>
            <span class="absolute top-1 right-2 w-2 h-2 rounded-full bg-amber-400"></span>
        </a>
        <a href="{{ route('smm.index') }}" class="flex flex-col items-center py-1 px-3 rounded-xl transition {{ request()->routeIs('smm.index') ? 'text-pink-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-bolt text-base"></i>
            <span class="text-[10px] mt-0.5">المتابعين</span>
        </a>
        <a href="{{ route('wallet.index') }}" class="flex flex-col items-center py-1 px-3 rounded-xl transition {{ request()->routeIs('wallet.*') ? 'text-emerald-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i class="fa-solid fa-wallet text-base"></i>
            <span class="text-[10px] mt-0.5">المحفظة</span>
        </a>
    </nav>

    <!-- Footer -->
    <footer class="glass-card border-t border-white/10 mt-16 sm:mt-20 pt-10 sm:pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10 sm:mb-12">
                <!-- About -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl insta-gradient flex items-center justify-center text-white">
                            <i class="fa-brands fa-instagram text-xl"></i>
                        </div>
                        <span class="text-xl font-black text-white">انستا<span class="insta-gradient-text">زون</span></span>
                    </div>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed max-w-md">
                        المنصة المتكاملة الأولى لخدمات انستقرام الاحترافية: متجر بيع اليوزرات القديمة والرباعية النادرة مع التسليم الفوري والآمن، وسيرفرات زيادة المتابعين والتفاعل الأسرع عربياً بدعم الدفع بالعملات الرقمية USDT.
                    </p>
                    <div class="flex items-center gap-3 mt-5">
                        <a href="https://t.me/{{ ltrim(\App\Models\Setting::get('telegram_support', 'InstaZone_Support'), '@') }}" target="_blank" class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 hover:bg-sky-500 hover:text-white transition flex items-center justify-center">
                            <i class="fa-brands fa-telegram text-lg"></i>
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp_support', '9647700000000')) }}" target="_blank" class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500 hover:text-white transition flex items-center justify-center">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4">روابط سريعة</h4>
                    <ul class="space-y-2 text-xs sm:text-sm text-slate-400">
                        <li><a href="{{ route('marketplace.index') }}" class="hover:text-pink-400 transition">متجر اليوزرات المميزة</a></li>
                        <li><a href="{{ route('smm.index') }}" class="hover:text-pink-400 transition">طلب زيادة متابعين</a></li>
                        <li><a href="{{ route('tasks.index') }}" class="hover:text-pink-400 transition">المهمات والمكافآت اليومية</a></li>
                        <li><a href="{{ route('smm.servicesList') }}" class="hover:text-pink-400 transition">جدول أسعار الخدمات</a></li>
                        <li><a href="{{ route('wallet.deposit') }}" class="hover:text-pink-400 transition">شحن المحفظة (USDT)</a></li>
                    </ul>
                </div>

                <!-- Crypto Payment Accepted -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4">الدفع بالعملات الرقمية</h4>
                    <p class="text-xs text-slate-400 mb-3">نقبل الدفع والشحن الفوري عبر شبكات USDT الأكثر انتشاراً وأقلها عمولة:</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-mono">
                            USDT - TRC20
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-mono">
                            USDT - BEP20
                        </span>
                    </div>
                    <div class="mt-4 p-3 rounded-xl bg-white/5 border border-white/10 text-xs text-slate-400">
                        <i class="fa-solid fa-headset ml-1 text-pink-400"></i> الدعم الفني متواجد 24/7 عبر تليجرام
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>© {{ date('Y') }} انستازون (InstaZone) - جميع الحقوق محفوظة.</p>
                <p class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span> جميع الخوادم تعمل بكفاءة 100%
                </p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
