@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <div class="glass-card rounded-3xl p-8 sm:p-12 border border-emerald-500/30 shadow-2xl relative overflow-hidden text-center">

        <!-- Confetti/Celebration Icon -->
        <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center text-4xl mb-6 animate-bounce">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 inline-block mb-3">
            طلب رقم #{{ $order->order_number }}
        </span>

        <h1 class="text-3xl sm:text-4xl font-black text-white mb-2">تهانينا! تم شراء اليوزر بنجاح</h1>
        <p class="text-sm text-slate-300 max-w-lg mx-auto mb-8">
            تم إتمام عملية الدفع وخصم ${{ number_format($order->price, 2) }} USDT من محفظتك. إليك بيانات تسجيل الدخول وتفاصيل الحساب المشتراة:
        </p>

        <!-- Summary Banner -->
        <div class="bg-black/50 rounded-2xl p-6 border border-white/10 mb-8 flex flex-col sm:flex-row items-center justify-around gap-4 text-center sm:text-right">
            <div>
                <span class="text-xs text-slate-400 block">يوزر انستقرام</span>
                <span class="text-2xl font-black font-outfit text-white dir-ltr">@<span>{{ $order->username->username }}</span></span>
            </div>
            <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
            <div>
                <span class="text-xs text-slate-400 block">المبلغ المدفوع</span>
                <span class="text-xl font-bold font-outfit text-emerald-400">${{ number_format($order->price, 2) }} USDT</span>
            </div>
            <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
            <div>
                <span class="text-xs text-slate-400 block">تاريخ الشراء</span>
                <span class="text-xs font-mono text-slate-300">{{ $order->created_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        <!-- Secret Delivery Box -->
        <div class="text-right mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-amber-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-key"></i> بيانات تسليم الحساب المسجلة:
                </span>
                <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('deliveryDetails').innerText); alert('تم نسخ بيانات الحساب بنجاح!');" class="text-xs text-pink-400 hover:text-pink-300 flex items-center gap-1 bg-pink-500/10 px-3 py-1 rounded-lg border border-pink-500/20">
                    <i class="fa-regular fa-copy"></i> نسخ الكل
                </button>
            </div>

            <div id="deliveryDetails" class="bg-dark-900 border border-amber-500/30 rounded-2xl p-6 text-sm text-slate-200 font-mono whitespace-pre-wrap text-left dir-ltr selection:bg-amber-500 selection:text-black">
{{ $order->delivery_details }}
            </div>
        </div>

        <!-- Security Advice -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-right text-xs text-slate-300 space-y-2 mb-8">
            <div class="font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-shield-virus text-pink-400"></i> نصائح هامة جداً لحماية حسابك الجديد:
            </div>
            <ul class="list-disc list-inside space-y-1 text-slate-400">
                <li>قم بتغيير كلمة سر الإيميل الأساسي فوراً وضع رقم هاتفك أو إيميل استرداد خاص بك.</li>
                <li>قم بتسجيل الدخول إلى انستقرام من جهاز موثوق وقم بتفعيل المصادقة الثنائية (2FA).</li>
                <li>احتفظ بأكواد الاسترداد الاحتياطية في مكان آمن.</li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('marketplace.myUsernames') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white text-xs font-bold transition">
                <i class="fa-solid fa-tags ml-1"></i> استعراض قائمة يوزراتي المشتراة
            </a>
            <a href="{{ route('marketplace.index') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold transition">
                <i class="fa-solid fa-bag-shopping ml-1"></i> العودة للمتجر
            </a>
        </div>

    </div>

</div>
@endsection
