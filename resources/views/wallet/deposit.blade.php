@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     x-data="{
        method: 'super_qi', // 'super_qi', 'zaincash', 'crypto'
        cryptoNetwork: 'TRC20',
        trc20Address: '{{ $trc20Address }}',
        bep20Address: '{{ $bep20Address }}',
        qiCardNumber: '{{ $qiCardNumber }}',
        qiAccountName: '{{ $qiAccountName }}',
        zainCashNumber: '{{ $zainCashNumber }}',
        zainCashName: '{{ $zainCashName }}',
        rate: {{ $usdToIqdRate }},
        amountUsd: 10,
        amountIqd: {{ 10 * $usdToIqdRate }},

        onUsdChange() {
            let u = parseFloat(this.amountUsd) || 0;
            this.amountIqd = Math.round(u * this.rate);
        },
        onIqdChange() {
            let q = parseFloat(this.amountIqd) || 0;
            this.amountUsd = (q / this.rate).toFixed(2);
        },
        copy(text, msg) {
            navigator.clipboard.writeText(text);
            alert(msg || 'تم النسخ بنجاح!');
        }
     }">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-white transition">الرئيسية</a>
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
        <a href="{{ route('wallet.index') }}" class="hover:text-white transition">المحفظة</a>
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
        <span class="text-pink-400">شحن الرصيد</span>
    </div>

    <!-- Header Section -->
    <div class="text-center max-w-xl mx-auto mb-8">
        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-pink-500/10 text-pink-400 border border-pink-500/20 inline-block mb-2">
            <i class="fa-solid fa-bolt mr-1"></i> بوابات الدفع الإلكتروني المباشرة
        </span>
        <h1 class="text-2xl sm:text-3xl font-black text-white">اختر طريقة الدفع واشحن رصيدك</h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-2">
            ادفع مباشرة عبر تطبيق <strong class="text-yellow-400">سوبر كي (Super Qi)</strong>، <strong class="text-purple-400">زين كاش</strong> أو العملات الرقمية <strong class="text-emerald-400">USDT</strong>.
        </p>
    </div>

    <!-- Payment Methods Selector Tabs -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
        <!-- Tab 1: Super Qi / Qi Card / Mastercard -->
        <button type="button" @click="method = 'super_qi'"
            :class="method === 'super_qi' ? 'border-yellow-500 bg-yellow-500/10 shadow-lg shadow-yellow-500/10' : 'border-white/10 bg-black/40 hover:border-white/20'"
            class="p-4 rounded-2xl border text-right transition relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl bg-yellow-500/20 flex items-center justify-center text-yellow-400 text-lg">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-yellow-500/20 text-yellow-300">سوبر كي / ماستركارد</span>
            </div>
            <h3 class="text-sm font-bold text-white mb-0.5">سوبر كي (Super Qi)</h3>
            <p class="text-[11px] text-slate-400">تحويل سريع ومباشر من تطبيق سوبر كي أو بطاقات Qi</p>
            <div x-show="method === 'super_qi'" class="absolute bottom-0 left-0 right-0 h-1 bg-yellow-500"></div>
        </button>

        <!-- Tab 2: ZainCash -->
        <button type="button" @click="method = 'zaincash'"
            :class="method === 'zaincash' ? 'border-purple-500 bg-purple-500/10 shadow-lg shadow-purple-500/10' : 'border-white/10 bg-black/40 hover:border-white/20'"
            class="p-4 rounded-2xl border text-right transition relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 text-lg">
                    <i class="fa-solid fa-mobile-screen"></i>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-300">محفظة رقمية</span>
            </div>
            <h3 class="text-sm font-bold text-white mb-0.5">زين كاش (ZainCash)</h3>
            <p class="text-[11px] text-slate-400">تحويل مباشر برقم الهاتف من تطبيق زين كاش</p>
            <div x-show="method === 'zaincash'" class="absolute bottom-0 left-0 right-0 h-1 bg-purple-500"></div>
        </button>

        <!-- Tab 3: USDT Crypto -->
        <button type="button" @click="method = 'crypto'"
            :class="method === 'crypto' ? 'border-emerald-500 bg-emerald-500/10 shadow-lg shadow-emerald-500/10' : 'border-white/10 bg-black/40 hover:border-white/20'"
            class="p-4 rounded-2xl border text-right transition relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 text-lg">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300">USDT مشفر</span>
            </div>
            <h3 class="text-sm font-bold text-white mb-0.5">العملات الرقمية (USDT)</h3>
            <p class="text-[11px] text-slate-400">إيداع عبر شبكة TRC20 أو BEP20 (Binance)</p>
            <div x-show="method === 'crypto'" class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500"></div>
        </button>
    </div>

    <!-- Main Payment Container -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Details Panel (5 cols) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- METHOD 1: Super Qi Instructions -->
            <div x-show="method === 'super_qi'" class="glass-card rounded-3xl p-6 sm:p-7 border border-yellow-500/30 space-y-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-yellow-500/20 text-yellow-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">بيانات الدفع عبر سوبر كي</h3>
                            <p class="text-[11px] text-slate-400">حول المبلغ المطلوب إلى بطاقتنا التالية:</p>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-yellow-400 bg-yellow-500/10 px-2.5 py-1 rounded-full border border-yellow-500/20">Qi Card</span>
                </div>

                <!-- Digital Mock Card -->
                <div class="rounded-2xl p-5 bg-gradient-to-tr from-amber-900/60 via-yellow-700/40 to-amber-950/80 border border-yellow-500/40 shadow-xl relative overflow-hidden text-white font-mono">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[10px] tracking-widest text-yellow-300 font-sans font-bold">SUPER QI / MASTERCARD</span>
                        <div class="flex items-center gap-1">
                            <div class="w-6 h-6 rounded-full bg-red-500 opacity-80"></div>
                            <div class="w-6 h-6 rounded-full bg-amber-400 -mr-3 opacity-80"></div>
                        </div>
                    </div>
                    <div class="text-xs text-yellow-200/80 mb-1">رقم البطاقة / الحساب</div>
                    <div class="text-lg sm:text-xl font-bold tracking-wider text-yellow-100 select-all cursor-pointer flex items-center justify-between"
                         @click="copy(qiCardNumber, 'تم نسخ رقم البطاقة!')">
                        <span x-text="qiCardNumber"></span>
                        <i class="fa-regular fa-copy text-xs text-yellow-300 hover:text-white ml-2"></i>
                    </div>
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-yellow-500/30 font-sans text-xs">
                        <div>
                            <span class="text-[10px] text-yellow-300/70 block">اسم المستلم</span>
                            <span class="font-bold text-white" x-text="qiAccountName"></span>
                        </div>
                        <div class="text-left font-mono">
                            <span class="text-[10px] text-yellow-300/70 block">سعر الصرف</span>
                            <span class="font-bold text-yellow-300">1$ = <span x-text="rate"></span> د.ع</span>
                        </div>
                    </div>
                </div>

                <!-- Step-by-Step Qi Guide -->
                <div class="p-4 rounded-2xl bg-black/40 border border-white/5 space-y-2 text-xs text-slate-300">
                    <h4 class="font-bold text-yellow-400 flex items-center gap-1.5 text-xs">
                        <i class="fa-solid fa-circle-info"></i> خطوات التحويل في تطبيق سوبر كي:
                    </h4>
                    <ol class="list-decimal list-inside space-y-1.5 text-[11px] leading-relaxed text-slate-400 pr-1">
                        <li>افتح تطبيق <strong class="text-white">سوبر كي (Super Qi)</strong> على هاتفك.</li>
                        <li>اختر <strong class="text-white">إرسال أموال</strong> ثم إدخال رقم البطاقة المستلمة.</li>
                        <li>الصق رقم البطاقة الموضح أعلاه والمبلغ بالدينار العراقي.</li>
                        <li>بعد نجاح التحويل، اكتب آخر 4 أرقام من بطاقتك أو أرفق الإشعار في النموذج المقابل.</li>
                    </ol>
                </div>
            </div>

            <!-- METHOD 2: ZainCash Instructions -->
            <div x-show="method === 'zaincash'" class="glass-card rounded-3xl p-6 sm:p-7 border border-purple-500/30 space-y-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-mobile-screen"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">بيانات الدفع عبر زين كاش</h3>
                            <p class="text-[11px] text-slate-400">حول المبلغ إلى رقم محفظتنا التالي:</p>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-purple-400 bg-purple-500/10 px-2.5 py-1 rounded-full border border-purple-500/20">ZainCash</span>
                </div>

                <!-- ZainCash Box -->
                <div class="rounded-2xl p-5 bg-gradient-to-tr from-purple-950/80 via-purple-900/50 to-indigo-950/80 border border-purple-500/40 shadow-xl relative overflow-hidden text-white">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] tracking-widest text-purple-300 font-bold">ZAIN CASH WALLET</span>
                        <i class="fa-solid fa-wallet text-purple-300 text-lg"></i>
                    </div>
                    <div class="text-xs text-purple-200/80 mb-1">رقم هاتف المحفظة المستلمة</div>
                    <div class="text-xl sm:text-2xl font-bold font-mono tracking-wider text-purple-100 select-all cursor-pointer flex items-center justify-between"
                         @click="copy(zainCashNumber, 'تم نسخ رقم محفظة زين كاش!')">
                        <span x-text="zainCashNumber"></span>
                        <i class="fa-regular fa-copy text-xs text-purple-300 hover:text-white ml-2"></i>
                    </div>
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-purple-500/30 text-xs">
                        <div>
                            <span class="text-[10px] text-purple-300/70 block">اسم صاحب المحفظة</span>
                            <span class="font-bold text-white" x-text="zainCashName"></span>
                        </div>
                        <div class="text-left font-mono">
                            <span class="text-[10px] text-purple-300/70 block">سعر الصرف</span>
                            <span class="font-bold text-purple-300">1$ = <span x-text="rate"></span> د.ع</span>
                        </div>
                    </div>
                </div>

                <!-- Steps -->
                <div class="p-4 rounded-2xl bg-black/40 border border-white/5 space-y-2 text-xs text-slate-300">
                    <h4 class="font-bold text-purple-400 flex items-center gap-1.5 text-xs">
                        <i class="fa-solid fa-circle-info"></i> خطوات التحويل في تطبيق زين كاش:
                    </h4>
                    <ol class="list-decimal list-inside space-y-1.5 text-[11px] leading-relaxed text-slate-400 pr-1">
                        <li>افتح تطبيق <strong class="text-white">زين كاش</strong> على هاتفك.</li>
                        <li>اختر <strong class="text-white">تحويل أموال</strong> وأدخل رقم المحفظة الموضح أعلاه.</li>
                        <li>أدخل المبلغ بالدينار العراقي وأكد التحويل برقمك السري.</li>
                        <li>أدخل رقم هاتفك الذي حولت منه في الخانة المقابلة.</li>
                    </ol>
                </div>
            </div>

            <!-- METHOD 3: Crypto USDT Instructions -->
            <div x-show="method === 'crypto'" class="glass-card rounded-3xl p-6 sm:p-7 border border-emerald-500/30 space-y-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">عنوان محفظة USDT</h3>
                            <p class="text-[11px] text-slate-400">اختر الشبكة وحول الرصيد</p>
                        </div>
                    </div>
                </div>

                <!-- Network Toggle Tabs -->
                <div class="grid grid-cols-2 gap-2 bg-black/40 p-1.5 rounded-2xl border border-white/10">
                    <button type="button" @click="cryptoNetwork = 'TRC20'"
                        :class="cryptoNetwork === 'TRC20' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                        class="py-2 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5">
                        <span class="w-2 h-2 rounded-full" :class="cryptoNetwork === 'TRC20' ? 'bg-white' : 'bg-emerald-400'"></span>
                        <span>TRC20 (ترون)</span>
                    </button>
                    <button type="button" @click="cryptoNetwork = 'BEP20'"
                        :class="cryptoNetwork === 'BEP20' ? 'bg-amber-600 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                        class="py-2 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5">
                        <span class="w-2 h-2 rounded-full" :class="cryptoNetwork === 'BEP20' ? 'bg-white' : 'bg-amber-400'"></span>
                        <span>BEP20 (بايننس)</span>
                    </button>
                </div>

                <!-- QR Code -->
                <div class="bg-white p-3 rounded-2xl w-40 h-40 mx-auto shadow-xl flex items-center justify-center">
                    <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${cryptoNetwork === 'TRC20' ? trc20Address : bep20Address}`" alt="Wallet QR" class="w-full h-full object-contain">
                </div>

                <!-- Address Input -->
                <div class="flex items-center gap-2 bg-black/60 p-2.5 rounded-xl border border-white/10">
                    <input type="text" readonly :value="cryptoNetwork === 'TRC20' ? trc20Address : bep20Address" class="w-full bg-transparent text-[11px] font-mono text-emerald-300 focus:outline-none select-all dir-ltr text-left">
                    <button type="button" @click="copy(cryptoNetwork === 'TRC20' ? trc20Address : bep20Address, 'تم نسخ عنوان المحفظة!')" class="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs transition shrink-0" title="نسخ">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
            </div>

            <!-- Need Help Box -->
            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-between text-xs">
                <span class="text-slate-400">واجهت مشكلة في التحويل؟</span>
                <a href="https://t.me/{{ ltrim(\App\Models\Setting::get('telegram_support', 'InstaZone_Support'), '@') }}" target="_blank" class="text-pink-400 font-bold hover:underline flex items-center gap-1">
                    <i class="fa-brands fa-telegram"></i> كلم الدعم الفني
                </a>
            </div>

        </div>

        <!-- Right Submission Form (7 cols) -->
        <div class="lg:col-span-7 glass-card rounded-3xl p-6 sm:p-8 border border-white/10 space-y-6">
            <div>
                <h2 class="text-lg font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane text-pink-400"></i> تأكيد عملية الشحن
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    أدخل المبلغ وتفاصيل المعاملة لنقوم بتدقيقها وإضافة الرصيد إلى محفظتك فوراً.
                </p>
            </div>

            <form action="{{ route('wallet.submitDeposit') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Hidden Input for Selected Method -->
                <input type="hidden" name="payment_method" :value="method">
                <input type="hidden" name="network" :value="cryptoNetwork">

                <!-- Dynamic Amount Calculator (USD / IQD) -->
                <div class="p-4 rounded-2xl bg-black/40 border border-white/10 space-y-4">
                    <label class="block text-xs font-bold text-slate-300">المبلغ المطلوب شحنه:</label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <span class="text-[11px] text-slate-400 mb-1 block">بالدولار ($ USDT):</span>
                            <div class="relative">
                                <input type="number" step="0.5" min="1" name="amount_usd" x-model="amountUsd" @input="onUsdChange()" required
                                    class="w-full bg-black/60 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white font-mono dir-ltr text-left focus:outline-none focus:border-pink-500 font-bold">
                                <span class="absolute left-3 top-2.5 text-xs text-slate-400 font-mono">$</span>
                            </div>
                        </div>

                        <div x-show="method !== 'crypto'">
                            <span class="text-[11px] text-slate-400 mb-1 block">المعادل بالدينار العراقي:</span>
                            <div class="relative">
                                <input type="number" step="250" min="1000" name="amount_iqd" x-model="amountIqd" @input="onIqdChange()"
                                    class="w-full bg-black/60 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-yellow-300 font-mono dir-ltr text-left focus:outline-none focus:border-yellow-500 font-bold">
                                <span class="absolute left-3 top-2.5 text-xs text-yellow-400 font-sans">د.ع</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2 border-t border-white/5 font-mono">
                        <span>سعر الصرف المعتمد:</span>
                        <span class="text-white font-bold">1 USD = <span x-text="rate"></span> IQD</span>
                    </div>
                </div>

                <!-- SUPER QI Specific Inputs -->
                <div x-show="method === 'super_qi'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">آخر 4 أرقام من بطاقتك / حسابك في سوبر كي</label>
                        <input type="text" maxlength="8" name="card_last_four" placeholder="مثال: 4589"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white font-mono dir-ltr text-left focus:outline-none focus:border-yellow-500">
                        <span class="text-[11px] text-slate-400 mt-1 block">لنتأكد من هوية البطاقة المحولة.</span>
                    </div>
                </div>

                <!-- ZAINCASH Specific Inputs -->
                <div x-show="method === 'zaincash'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">رقم هاتفك الذي قمت بالتحويل منه (زين كاش)</label>
                        <input type="text" name="sender_phone" placeholder="مثال: 07801234567"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white font-mono dir-ltr text-left focus:outline-none focus:border-purple-500">
                        <span class="text-[11px] text-slate-400 mt-1 block">رقم المحفظة التي أرسلت منها الدفعة.</span>
                    </div>
                </div>

                <!-- CRYPTO Specific Inputs -->
                <div x-show="method === 'crypto'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">كود المعاملة / الهاش (TXID)</label>
                        <input type="text" name="txid" placeholder="مثال: 3e8b15fa2b..."
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white font-mono dir-ltr text-left focus:outline-none focus:border-emerald-500">
                        <span class="text-[11px] text-slate-400 mt-1 block">تجد رمز الـ TXID في سجل سحوبات Binance أو محفظتك.</span>
                    </div>
                </div>

                <!-- Proof Image Attachment (Universal) -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">صورة إشعار / إيصال التحويل (اختياري لتسريع الاعتماد)</label>
                    <input type="file" name="proof_image" accept="image/*"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white/10 file:text-white hover:file:bg-white/20 transition">
                    <span class="text-[11px] text-slate-500 mt-1 block">لقطة شاشة (سيد التحويل) تظهر المبلغ ورقم العملية.</span>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    :class="{
                        'bg-yellow-600 hover:bg-yellow-500 shadow-yellow-600/25': method === 'super_qi',
                        'bg-purple-600 hover:bg-purple-500 shadow-purple-600/25': method === 'zaincash',
                        'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/25': method === 'crypto'
                    }"
                    class="w-full py-4 rounded-xl text-white font-bold text-sm shadow-xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>إرسال طلب الشحن والاعتماد</span>
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
