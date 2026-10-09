@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
     x-data="smmOrderApp()">

    <!-- Header -->
    <div class="glass-card rounded-3xl p-8 border border-white/10 mb-8 relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 inline-block mb-3">
                    <i class="fa-solid fa-bolt"></i> SMM Panel Services
                </span>
                <h1 class="text-3xl font-black text-white mb-2">طلب زيادة المتابعين والتفاعل الفوري</h1>
                <p class="text-xs sm:text-sm text-slate-300">
                    اختر الخدمة، ضع رابط حسابك أو منشورك، وحدد الكمية لتبدأ العملية فوراً وبأعلى جودة.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('smm.myOrders') }}" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-bold text-slate-300 hover:text-white transition flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-yellow-400"></i> سجل طلباتي
                </a>
                <a href="{{ route('smm.servicesList') }}" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-bold text-slate-300 hover:text-white transition flex items-center gap-2">
                    <i class="fa-solid fa-list text-pink-400"></i> قائمة الأسعار
                </a>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Form Column (2 Cols) -->
        <div class="lg:col-span-2">
            <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/10 shadow-xl">
                <form action="{{ route('smm.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- 1. Category Selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2">1. اختر التصنيف</label>
                        <select x-model="selectedCategoryId" @change="onCategoryChange()" class="w-full bg-black/50 border border-white/10 rounded-2xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-pink-500 transition">
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name_ar"></option>
                            </template>
                        </select>
                    </div>

                    <!-- 2. Service Selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2">2. اختر الخدمة المطلوبة</label>
                        <select name="service_id" x-model="selectedServiceId" @change="onServiceChange()" class="w-full bg-black/50 border border-white/10 rounded-2xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-pink-500 transition">
                            <template x-for="serv in availableServices" :key="serv.id">
                                <option :value="serv.id" x-text="`${serv.name_ar} — [$${parseFloat(serv.price_per_1k).toFixed(2)} / 1K]`"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Service Description Box -->
                    <div x-show="currentService" class="p-4 rounded-2xl bg-white/5 border border-white/5 text-xs text-slate-300 space-y-2">
                        <div class="font-bold text-amber-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info"></i> وصف ومواصفات الخدمة:
                        </div>
                        <p x-text="currentService?.description || 'خدمة موثوقة وعالية الجودة بدون نقص.'"></p>
                        <div class="flex flex-wrap items-center gap-4 text-[11px] text-slate-400 pt-2 border-t border-white/5 font-mono">
                            <span>الحد الأدنى: <strong class="text-white" x-text="currentService?.min_quantity"></strong></span>
                            <span>الحد الأقصى: <strong class="text-white" x-text="Number(currentService?.max_quantity).toLocaleString()"></strong></span>
                            <span>السعر لكل 1,000: <strong class="text-emerald-400" x-text="'$' + parseFloat(currentService?.price_per_1k || 0).toFixed(4) + ' USDT'"></strong></span>
                        </div>
                    </div>

                    <!-- 3. Link Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2">3. الرابط (Link)</label>
                        <input type="url" name="link" required
                            placeholder="https://instagram.com/username أو رابط البوست/الريلز"
                            class="w-full bg-black/50 border border-white/10 rounded-2xl px-4 py-3.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition text-left dir-ltr">
                        <span class="text-[11px] text-slate-400 mt-1 block">تأكد أن الحساب عام (Public) وليس خاص (Private) أثناء التنفيذ.</span>
                    </div>

                    <!-- 4. Quantity Input -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-bold text-slate-300">4. الكمية المطلوبة</label>
                            <span class="text-xs text-slate-400 font-mono" x-show="currentService">
                                الحد: <span x-text="currentService?.min_quantity"></span> - <span x-text="Number(currentService?.max_quantity).toLocaleString()"></span>
                            </span>
                        </div>
                        <input type="number" name="quantity" x-model.number="quantity" @input="calculateTotal()"
                            :min="currentService?.min_quantity || 1"
                            :max="currentService?.max_quantity || 1000000"
                            required
                            class="w-full bg-black/50 border border-white/10 rounded-2xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-pink-500 transition font-mono dir-ltr text-left">
                    </div>

                    <!-- Submit Button -->
                    @auth
                        <button type="submit" class="w-full py-4 rounded-2xl insta-gradient text-white font-bold text-base shadow-xl shadow-pink-500/25 hover:opacity-95 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>تأكيد وإرسال الطلب الآن</span>
                        </button>
                    @else
                        <div class="text-center p-4 rounded-2xl bg-pink-500/10 border border-pink-500/20 text-xs text-slate-300">
                            يرجى <a href="{{ route('login') }}" class="text-pink-400 font-bold underline">تسجيل الدخول</a> لتتمكن من تقديم الطلب.
                        </div>
                    @endauth

                </form>
            </div>
        </div>

        <!-- Order Summary & Wallet Column (1 Col) -->
        <div class="space-y-6">

            <!-- Live Calculation Summary Card -->
            <div class="glass-card rounded-3xl p-6 border border-white/10 sticky top-28 shadow-xl">
                <h3 class="text-sm font-bold text-white mb-4 pb-3 border-b border-white/10 flex items-center gap-2">
                    <i class="fa-solid fa-calculator text-yellow-400"></i> ملخص التكلفة المباشرة
                </h3>

                <div class="space-y-3 text-xs mb-6">
                    <div class="flex justify-between items-center text-slate-400">
                        <span>الكمية المحددة:</span>
                        <span class="font-bold text-white font-mono" x-text="Number(quantity || 0).toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between items-center text-slate-400">
                        <span>سعر الألف (1K):</span>
                        <span class="font-mono text-emerald-400" x-text="'$' + parseFloat(currentService?.price_per_1k || 0).toFixed(4)"></span>
                    </div>

                    <div class="pt-3 border-t border-white/10 flex justify-between items-baseline">
                        <span class="font-bold text-white">الإجمالي المستحق:</span>
                        <div class="text-right">
                            <span class="text-2xl font-black font-outfit text-white" x-text="'$' + calculatedCost"></span>
                            <span class="text-[11px] text-emerald-400 font-mono">USDT</span>
                        </div>
                    </div>
                </div>

                <!-- Wallet Check -->
                @auth
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 text-xs space-y-2 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">رصيدك المتاح:</span>
                            <span class="font-bold text-white font-mono">${{ number_format(Auth::user()->balance, 2) }} USDT</span>
                        </div>

                        <template x-if="Number(calculatedCost) <= {{ (float)Auth::user()->balance }}">
                            <p class="text-emerald-400 flex items-center gap-1.5 pt-1 border-t border-white/10">
                                <i class="fa-solid fa-circle-check"></i> رصيدك يغطي تكلفة هذا الطلب.
                            </p>
                        </template>
                        <template x-if="Number(calculatedCost) > {{ (float)Auth::user()->balance }}">
                            <div class="pt-2 border-t border-white/10">
                                <p class="text-rose-400 mb-2 flex items-center gap-1">
                                    <i class="fa-solid fa-triangle-exclamation"></i> رصيدك غير كافٍ.
                                </p>
                                <a href="{{ route('wallet.deposit') }}" class="block w-full py-2 text-center rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition text-xs">
                                    شحن الرصيد الآن بـ USDT
                                </a>
                            </div>
                        </template>
                    </div>
                @endauth

                <!-- Guarantee Points -->
                <div class="space-y-2 text-[11px] text-slate-400 pt-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-400"></i>
                        <span>بدء فوري وتلقائي عبر سيرفرات API</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-400"></i>
                        <span>حماية كاملة وأمان لحساب انستقرام</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-400"></i>
                        <span>استرجاع تلقائي للرصيد في حال الإلغاء</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@php
    $categoriesJson = $categories->map(function ($cat) {
        return [
            'id' => $cat->id,
            'name_ar' => $cat->name_ar,
            'services' => $cat->services->map(function ($serv) {
                return [
                    'id' => $serv->id,
                    'name_ar' => $serv->name_ar,
                    'price_per_1k' => (float)$serv->price_per_1k,
                    'min_quantity' => $serv->min_quantity,
                    'max_quantity' => $serv->max_quantity,
                    'description' => $serv->description,
                ];
            })
        ];
    });
@endphp

@endsection

@section('scripts')
<script>
    function smmOrderApp() {
        const categories = {!! json_encode($categoriesJson) !!};
        const initialCat = categories.length > 0 ? categories[0] : null;
        const initialServ = initialCat && initialCat.services.length > 0 ? initialCat.services[0] : null;

        return {
            categories: categories,
            selectedCategoryId: initialCat ? initialCat.id : null,
            availableServices: initialCat ? initialCat.services : [],
            selectedServiceId: initialServ ? initialServ.id : null,
            currentService: initialServ,
            quantity: initialServ ? initialServ.min_quantity : 100,
            calculatedCost: '0.00',

            init() {
                this.calculateTotal();
            },

            onCategoryChange() {
                const cat = this.categories.find(c => c.id == this.selectedCategoryId);
                if (cat) {
                    this.availableServices = cat.services;
                    if (this.availableServices.length > 0) {
                        this.selectedServiceId = this.availableServices[0].id;
                        this.currentService = this.availableServices[0];
                        this.quantity = this.currentService.min_quantity;
                    } else {
                        this.selectedServiceId = null;
                        this.currentService = null;
                    }
                }
                this.calculateTotal();
            },

            onServiceChange() {
                this.currentService = this.availableServices.find(s => s.id == this.selectedServiceId) || null;
                if (this.currentService) {
                    this.quantity = this.currentService.min_quantity;
                }
                this.calculateTotal();
            },

            calculateTotal() {
                if (!this.currentService || !this.quantity) {
                    this.calculatedCost = '0.00';
                    return;
                }
                const cost = (this.quantity / 1000) * this.currentService.price_per_1k;
                this.calculatedCost = cost.toFixed(4);
            }
        }
    }
</script>
@endsection
