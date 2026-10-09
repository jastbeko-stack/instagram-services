@extends('layouts.admin')

@section('page_title', 'إعدادات المنصة ومحافظ العملات الرقمية')

@section('admin_content')
<div class="max-w-3xl">

    <div class="glass-card rounded-2xl p-6 sm:p-8 border border-white/10">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Site Info -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-white/10 pb-2">
                    <i class="fa-solid fa-globe text-pink-400"></i> بيانات وهوية الموقع
                </h3>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">اسم المنصة</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] }}" required
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">شريط الإعلانات العلوي في الموقع</label>
                    <input type="text" name="notice_banner" value="{{ $settings['notice_banner'] }}"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500">
                </div>
            </div>

            <!-- Crypto USDT Wallets -->
            <div class="space-y-4 pt-4 border-t border-white/10">
                <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-white/10 pb-2">
                    <i class="fa-solid fa-coins text-emerald-400"></i> عناوين محافظ استقبال مدفوعات USDT
                </h3>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">عنوان محفظة USDT (شبكة TRC20 - ترون)</label>
                    <input type="text" name="usdt_trc20_address" value="{{ $settings['usdt_trc20_address'] }}" required
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono dir-ltr text-left focus:outline-none focus:border-emerald-500">
                    <span class="text-[10px] text-slate-500 mt-1 block">العنوان الذي يظهر للعملاء لشحن رصيدهم عبر شبكة TRC20</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">عنوان محفظة USDT (شبكة BEP20 - بايننس الذكية)</label>
                    <input type="text" name="usdt_bep20_address" value="{{ $settings['usdt_bep20_address'] }}" required
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono dir-ltr text-left focus:outline-none focus:border-amber-500">
                    <span class="text-[10px] text-slate-500 mt-1 block">العنوان الذي يظهر للعملاء لشحن رصيدهم عبر شبكة BEP20 (BSC)</span>
                </div>
            </div>

            <!-- Points & Gamification Settings -->
            <div class="space-y-4 pt-4 border-t border-white/10">
                <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-white/10 pb-2">
                    <i class="fa-solid fa-gift text-amber-400"></i> إعدادات نظام النقاط والمكافآت اليومية
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">عدد النقاط لكل 1.00 USDT</label>
                        <input type="number" min="1" name="points_per_dollar" value="{{ $settings['points_per_dollar'] ?? 1000 }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono dir-ltr text-left">
                        <span class="text-[10px] text-slate-500 mt-1 block">مثال: 1000 نقطة = 1 دولار</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">الحد الأدنى للنقاط للتحويل</label>
                        <input type="number" min="1" name="min_conversion_points" value="{{ $settings['min_conversion_points'] ?? 500 }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono dir-ltr text-left">
                        <span class="text-[10px] text-slate-500 mt-1 block">أقل كمية نقاط يمكن تحويلها لرصيد</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">مكافأة إكمال جميع مهمات اليوم</label>
                        <input type="number" min="0" name="daily_bonus_points" value="{{ $settings['daily_bonus_points'] ?? 100 }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white font-mono dir-ltr text-left">
                        <span class="text-[10px] text-slate-500 mt-1 block">نقاط مجانية إضافية عند إنجاز الكل</span>
                    </div>
                </div>
            </div>

            <!-- Support Channels -->
            <div class="space-y-4 pt-4 border-t border-white/10">
                <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-white/10 pb-2">
                    <i class="fa-solid fa-headset text-sky-400"></i> قنوات الدعم الفني والتواصل
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">معرف تليجرام للدعم</label>
                        <input type="text" name="telegram_support" value="{{ $settings['telegram_support'] }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white dir-ltr text-left focus:outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">رقم واتساب للدعم</label>
                        <input type="text" name="whatsapp_support" value="{{ $settings['whatsapp_support'] }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white dir-ltr text-left focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 flex items-center justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs shadow-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>حفظ التعديلات</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
