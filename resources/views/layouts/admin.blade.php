<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'لوحة الإدارة | انستازون' }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
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
                        dark: {
                            950: '#05070a',
                            900: '#0b0f19',
                            800: '#111827',
                            700: '#1f2937'
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #05070a; color: #f1f5f9; -webkit-tap-highlight-color: transparent; }
        .glass-card { background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .insta-gradient { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen flex bg-[#05070a] text-slate-200" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="lg:hidden fixed inset-0 z-40 bg-black/80 backdrop-blur-sm transition-opacity"></div>

    <!-- Admin Sidebar (Off-canvas on mobile, fixed on desktop) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
           class="fixed lg:static inset-y-0 right-0 z-50 w-64 bg-dark-900 border-l border-white/10 flex flex-col shrink-0 min-h-screen transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none">
        <!-- Logo -->
        <div class="h-16 lg:h-20 flex items-center justify-between px-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl insta-gradient flex items-center justify-center text-white shadow-lg">
                    <i class="fa-solid fa-shield-halved text-base"></i>
                </div>
                <div>
                    <span class="text-base font-black text-white block">لوحة الإدارة</span>
                    <span class="text-[9px] text-pink-400 font-bold uppercase tracking-wider">InstaZone Control</span>
                </div>
            </div>
            <!-- Close button on mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-white rounded-lg bg-white/5">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto text-sm">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-pink-600 text-white shadow-lg shadow-pink-600/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i> الرئيسية والإحصائيات
            </a>

            <div class="pt-4 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3">
                متجر اليوزرات
            </div>
            <a href="{{ route('admin.usernames.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl font-medium transition {{ request()->routeIs('admin.usernames.*') ? 'bg-pink-600/20 text-pink-400 border border-pink-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-at w-5 text-center"></i> إدارة اليوزرات
            </a>

            <div class="pt-4 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3">
                سيرفرات وخدمات SMM
            </div>
            <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl font-medium transition {{ request()->routeIs('admin.services.*') ? 'bg-pink-600/20 text-pink-400 border border-pink-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-bolt w-5 text-center"></i> خدمات المتابعين
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-pink-600/20 text-pink-400 border border-pink-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-layer-group w-5 text-center"></i> تصنيفات الخدمات
            </a>
            <a href="{{ route('admin.providers.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl font-medium transition {{ request()->routeIs('admin.providers.*') ? 'bg-pink-600/20 text-pink-400 border border-pink-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-server w-5 text-center"></i> مزودو API الخارجيين
            </a>

            <div class="pt-4 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3">
                الطلبات والمالية
            </div>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl font-medium transition {{ request()->routeIs('admin.orders.*') ? 'bg-pink-600/20 text-pink-400 border border-pink-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <span class="flex items-center gap-3">
                    <i class="fa-solid fa-cart-shopping w-5 text-center"></i> طلبات المتابعين
                </span>
                @php $pendingOrdersCount = \App\Models\SmmOrder::where('status', 'pending')->count(); @endphp
                @if($pendingOrdersCount > 0)
                    <span class="px-2 py-0.5 text-xs bg-amber-500 text-black font-bold rounded-full">{{ $pendingOrdersCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.deposits.index') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl font-medium transition {{ request()->routeIs('admin.deposits.*') ? 'bg-pink-600/20 text-pink-400 border border-pink-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <span class="flex items-center gap-3">
                    <i class="fa-solid fa-coins w-5 text-center text-emerald-400"></i> إيداعات USDT
                </span>
                @php $pendingDepCount = \App\Models\CryptoDeposit::where('status', 'pending')->count(); @endphp
                @if($pendingDepCount > 0)
                    <span class="px-2 py-0.5 text-xs bg-emerald-500 text-black font-bold rounded-full animate-pulse">{{ $pendingDepCount }}</span>
                @endif
            </a>

            <div class="pt-4 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3">
                المهمات ونقاط المكافآت
            </div>
            <a href="{{ route('admin.tasks.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl font-medium transition {{ request()->routeIs('admin.tasks.*') ? 'bg-pink-600/20 text-pink-400 border border-pink-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-gift w-5 text-center text-amber-400"></i> إدارة المهمات والنقاط
            </a>

            <div class="pt-4 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3">
                النظام
            </div>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-pink-600/20 text-pink-400 border border-pink-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-users w-5 text-center"></i> المستخدمين والمحافظ
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl font-medium transition {{ request()->routeIs('admin.settings.*') ? 'bg-pink-600/20 text-pink-400 border border-pink-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-sliders w-5 text-center"></i> إعدادات المنصة
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-white/10 space-y-2">
            <a href="{{ route('home') }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-white/5 hover:bg-white/10 text-xs text-slate-300 font-semibold transition">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> عرض المتجر
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-xs text-rose-400 font-semibold transition">
                    <i class="fa-solid fa-right-from-bracket"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Header with Mobile Hamburger -->
        <header class="h-16 lg:h-20 bg-dark-900 border-b border-white/10 px-4 sm:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
                <h1 class="text-base sm:text-xl font-bold text-white truncate">@yield('page_title', 'لوحة التحكم الإدارية')</h1>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <span class="text-xs text-slate-400 block">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-emerald-400 font-mono">مدير النظام (Admin)</span>
                </div>
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-pink-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Notifications -->
        <div class="p-4 sm:p-8 pb-0">
            @if(session('success'))
                <div class="p-3.5 sm:p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs sm:text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3.5 sm:p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs sm:text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-xmark text-rose-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Page Body -->
        <main class="p-4 sm:p-8 flex-1 overflow-y-auto">
            @yield('admin_content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
