@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
     x-data="{
        network: 'TRC20',
        trc20Address: '{{ $trc20Address }}',
        bep20Address: '{{ $bep20Address }}',
        get currentAddress() {
            return this.network === 'TRC20' ? this.trc20Address : this.bep20Address;
        },
        copyAddress() {
            navigator.clipboard.writeText(this.currentAddress);
            alert('تم نسخ عنوان المحفظة بنجاح!');
        }
     }">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-white transition">الرئيسية</a>
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
        <a href="{{ route('wallet.index') }}" class="hover:text-white transition">المحفظة</a>
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
        <span class="text-emerald-400">شحن رصيد USDT</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

        <!-- Column 1: QR & Wallet Address -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/10 space-y-6">
            <div class="text-center">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 inline-block mb-3">
                    <i class="fa-solid fa-coins"></i> Crypto USDT Deposit
                </span>
                <h2 class="text-xl font-black text-white">عنوان محفظة الإيداع</h2>
                <p class="text-xs text-slate-400 mt-1">اختر الشبكة وقم بتحويل المبلغ المطلوب</p>
            </div>

            <!-- Network Toggle Tabs -->
            <div class="grid grid-cols-2 gap-2 bg-black/40 p-1.5 rounded-2xl border border-white/10">
                <button type="button" @click="network = 'TRC20'"
                    :class="network === 'TRC20' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                    class="py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" :class="network === 'TRC20' ? 'bg-white' : 'bg-emerald-400'"></span>
                    <span>USDT (TRC20 - ترون)</span>
                </button>
                <button type="button" @click="network = 'BEP20'"
                    :class="network === 'BEP20' ? 'bg-amber-600 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                    class="py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5">
                    <span class="w-2 h-2 rounded-full" :class="network === 'BEP20' ? 'bg-white' : 'bg-amber-400'"></span>
                    <span>USDT (BEP20 - بايننس)</span>
                </button>
            </div>

            <!-- QR Code Display -->
            <div class="bg-white p-4 rounded-2xl w-48 h-48 mx-auto shadow-xl flex items-center justify-center">
                <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${currentAddress}`" alt="Wallet QR" class="w-full h-full object-contain">
            </div>

            <!-- Address Copy Box -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-2">عنوان المحفظة للشبكة المحددة:</label>
                <div class="flex items-center gap-2 bg-black/60 p-3 rounded-2xl border border-white/10">
                    <input type="text" readonly :value="currentAddress" class="w-full bg-transparent text-xs font-mono text-emerald-300 focus:outline-none select-all dir-ltr text-left">
                    <button type="button" @click="copyAddress()" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs transition shrink-0" title="نسخ العنوان">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
            </div>

            <!-- Important Warning -->
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-300 space-y-1">
                <p class="font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-triangle-exclamation"></i> تنبيه هام جداً:
                </p>
                <p>تأكد من اختيار نفس الشبكة (<span class="font-bold font-mono" x-text="network"></span>) عند التحويل من محفظتك أو منصتك (Binance / TrustWallet / OKX) لتجنب فقدان الأموال.</p>
            </div>
        </div>

        <!-- Column 2: Confirmation / Submit Form -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/10">
            <h3 class="text-lg font-black text-white mb-2">تأكيد عملية الإيداع</h3>
            <p class="text-xs text-slate-400 mb-6">بعد إتمام التحويل، أدخل كود المعاملة (TXID) ليتم التحقق وإضافة الرصيد فوراً.</p>

            <form action="{{ route('wallet.submitDeposit') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Hidden Selected Network -->
                <input type="hidden" name="network" :value="network">

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">الشبكة المستخدمة</label>
                    <div class="p-3 rounded-xl bg-black/40 border border-white/10 font-mono text-sm text-emerald-400 font-bold" x-text="`USDT - ${network}`"></div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">المبلغ المحول ($ USDT)</label>
                    <input type="number" step="0.01" min="1" name="amount_usd" required placeholder="مثال: 50.00"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-pink-500 transition font-mono dir-ltr text-left">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">كود العملية / الهاش (Transaction Hash / TXID)</label>
                    <input type="text" name="txid" required placeholder="مثال: 3e8b15fa2b..."
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-pink-500 transition font-mono dir-ltr text-left">
                    <span class="text-[11px] text-slate-400 mt-1 block">تجد كود الـ TXID في سجل سحوبات محفظتك أو المنصة التي حولت منها.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">صورة إيصال التحويل (اختياري)</label>
                    <input type="file" name="proof_image" accept="image/*"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white/10 file:text-white hover:file:bg-white/20 transition">
                </div>

                <button type="submit" class="w-full py-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-xl shadow-emerald-600/25 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>إرسال طلب الإيداع للتدقيق</span>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-white/10 text-center text-xs text-slate-400">
                هل تحتاج لمساعدة في الدفع؟
                <a href="https://t.me/{{ ltrim(\App\Models\Setting::get('telegram_support', 'InstaZone_Support'), '@') }}" target="_blank" class="text-pink-400 font-bold hover:underline mr-1">
                    تواصل مع الدعم الفني
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
