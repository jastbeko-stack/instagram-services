@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full glass-card p-8 rounded-3xl border border-white/10 shadow-2xl relative">
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-2xl insta-gradient flex items-center justify-center text-white text-2xl mb-4 shadow-lg shadow-pink-500/30">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h2 class="text-2xl font-black text-white">تسجيل الدخول</h2>
            <p class="text-xs text-slate-400 mt-2">سجل دخولك لمتابعة مشترياتك وشحن رصيد المحفظة</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-2">اسم المستخدم أو البريد الإلكتروني</label>
                <div class="relative">
                    <input type="text" name="login" value="{{ old('login') }}" required autofocus
                        placeholder="أدخل اسم المستخدم أو البريد"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition text-right">
                </div>
                @error('login')
                    <p class="text-xs text-rose-400 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-300">كلمة المرور</label>
                </div>
                <input type="password" name="password" required
                    placeholder="••••••••"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition text-left dir-ltr">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-400">
                    <input type="checkbox" name="remember" class="rounded bg-black/40 border-white/20 text-pink-600 focus:ring-0">
                    <span>تذكر بيانات الدخول</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl insta-gradient text-white font-bold text-sm shadow-lg shadow-pink-500/25 hover:opacity-95 transition">
                تسجيل الدخول الآن
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-white/10 text-center text-xs text-slate-400">
            ليس لديك حساب بعد؟
            <a href="{{ route('register') }}" class="text-pink-400 font-bold hover:text-pink-300 mr-1">
                أنشئ حساباً جديداً مجاناً
            </a>
        </div>
    </div>
</div>
@endsection
