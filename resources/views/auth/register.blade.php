@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full glass-card p-8 rounded-3xl border border-white/10 shadow-2xl relative">
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-2xl insta-gradient flex items-center justify-center text-white text-2xl mb-4 shadow-lg shadow-pink-500/30">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2 class="text-2xl font-black text-white">إنشاء حساب جديد</h2>
            <p class="text-xs text-slate-400 mt-2">انضم إلينا لشراء اليوزرات وإدارة طلباتك بكل سهولة</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">الاسم الكامل</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="مثال: أحمد علي"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    placeholder="example@mail.com"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition text-left dir-ltr">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">رقم الهاتف (اختياري)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        placeholder="+964..."
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition text-left dir-ltr">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">معرف تليجرام (اختياري)</label>
                    <input type="text" name="telegram" value="{{ old('telegram') }}"
                        placeholder="@username"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition text-left dir-ltr">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">كلمة المرور</label>
                <input type="password" name="password" required
                    placeholder="8 خانات على الأقل"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition text-left dir-ltr">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" required
                    placeholder="أعد كتابة كلمة المرور"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition text-left dir-ltr">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl insta-gradient text-white font-bold text-sm shadow-lg shadow-pink-500/25 hover:opacity-95 transition mt-2">
                إنشاء الحساب والبدء
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-white/10 text-center text-xs text-slate-400">
            لديك حساب بالفعل؟
            <a href="{{ route('login') }}" class="text-pink-400 font-bold hover:text-pink-300 mr-1">
                تسجيل الدخول
            </a>
        </div>
    </div>
</div>
@endsection
