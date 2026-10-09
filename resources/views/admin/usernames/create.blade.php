@extends('layouts.admin')

@section('page_title', 'إضافة يوزر / حساب انستقرام جديد')

@section('admin_content')
<div class="max-w-3xl">

    <div class="glass-card rounded-2xl p-6 sm:p-8 border border-white/10">
        <form action="{{ route('admin.usernames.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">اسم اليوزر (بدون @)</label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="مثال: x_99"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500 font-mono dir-ltr text-left">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">نوع وتصنيف اليوزر</label>
                    <select name="type" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500">
                        <option value="quad">رباعي (4L)</option>
                        <option value="tri_semi">شبه ثلاثي (Semi 3L)</option>
                        <option value="vintage">قديم (Vintage 2012-2015)</option>
                        <option value="verified">موثق بالعلامة الزرقاء (Verified)</option>
                        <option value="special">يوزر مميز آخر</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">السعر بالدولار ($ USDT)</label>
                    <input type="number" step="0.01" min="1" name="price" value="{{ old('price') }}" required placeholder="150.00"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500 font-mono dir-ltr text-left">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">عدد المتابعين الحالي</label>
                    <input type="number" min="0" name="followers_count" value="{{ old('followers_count', 0) }}" placeholder="0"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500 font-mono dir-ltr text-left">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">سنة التأسيس (اختياري)</label>
                    <input type="text" name="creation_year" value="{{ old('creation_year') }}" placeholder="2014"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500 font-mono dir-ltr text-left">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">وصف ومميزات الحساب (يظهر للعميل في المتجر)</label>
                <textarea name="description" rows="3" placeholder="اكتب وصفاً جذاباً لليوزر وميزاته..."
                    class="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-pink-500 leading-relaxed">{{ old('description') }}</textarea>
            </div>

            <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
                <label class="block text-xs font-bold text-amber-300 mb-1.5">
                    <i class="fa-solid fa-key"></i> بيانات التسليم السرية (تسلم للمشتري آلياً فور الدفع):
                </label>
                <textarea name="delivery_info" rows="4" required placeholder="الإيميل الأساسي، كلمة السر، يوزر الانستا وباسورده، كود المصادقة الثنائية 2FA..."
                    class="w-full bg-black/60 border border-white/10 rounded-xl p-3 text-xs text-slate-200 font-mono focus:outline-none focus:border-amber-400 dir-ltr text-left">{{ old('delivery_info') }}</textarea>
                <span class="text-[11px] text-amber-200/80 mt-1 block">هذه البيانات مشفرة ومحفوظة بأمان ولا تظهر للعامة إطلاقاً، بل للمشتري فقط بعد إتمام الشراء.</span>
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs transition">
                    حفظ وإضافة اليوزر للمتجر
                </button>
                <a href="{{ route('admin.usernames.index') }}" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs transition">
                    إلغاء
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
