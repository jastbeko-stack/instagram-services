@extends('layouts.admin')

@section('page_title', 'تعديل يوزر @' . $username->username)

@section('admin_content')
<div class="max-w-3xl">

    <div class="glass-card rounded-2xl p-6 sm:p-8 border border-white/10">
        <form action="{{ route('admin.usernames.update', $username->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">اسم اليوزر</label>
                    <input type="text" name="username" value="{{ old('username', $username->username) }}" required
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500 font-mono dir-ltr text-left">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">الحالة</label>
                    <select name="status" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500">
                        <option value="available" {{ $username->status == 'available' ? 'selected' : '' }}>متاح (Available)</option>
                        <option value="reserved" {{ $username->status == 'reserved' ? 'selected' : '' }}>محجوز (Reserved)</option>
                        <option value="sold" {{ $username->status == 'sold' ? 'selected' : '' }}>تم البيع (Sold)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">نوع وتصنيف اليوزر</label>
                    <select name="type" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500">
                        <option value="quad" {{ $username->type == 'quad' ? 'selected' : '' }}>رباعي (4L)</option>
                        <option value="tri_semi" {{ $username->type == 'tri_semi' ? 'selected' : '' }}>شبه ثلاثي (Semi 3L)</option>
                        <option value="vintage" {{ $username->type == 'vintage' ? 'selected' : '' }}>قديم (Vintage 2012-2015)</option>
                        <option value="verified" {{ $username->type == 'verified' ? 'selected' : '' }}>موثق رسمي (Verified)</option>
                        <option value="special" {{ $username->type == 'special' ? 'selected' : '' }}>يوزر مميز</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">السعر بالدولار ($ USDT)</label>
                    <input type="number" step="0.01" min="1" name="price" value="{{ old('price', $username->price) }}" required
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500 font-mono dir-ltr text-left">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">عدد المتابعين</label>
                    <input type="number" min="0" name="followers_count" value="{{ old('followers_count', $username->followers_count) }}"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-pink-500 font-mono dir-ltr text-left">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">وصف ومميزات الحساب</label>
                <textarea name="description" rows="3"
                    class="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-pink-500 leading-relaxed">{{ old('description', $username->description) }}</textarea>
            </div>

            <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
                <label class="block text-xs font-bold text-amber-300 mb-1.5">
                    <i class="fa-solid fa-key"></i> بيانات التسليم السرية (تسلم للمشتري آلياً فور الدفع):
                </label>
                <textarea name="delivery_info" rows="4" required
                    class="w-full bg-black/60 border border-white/10 rounded-xl p-3 text-xs text-slate-200 font-mono focus:outline-none focus:border-amber-400 dir-ltr text-left">{{ old('delivery_info', $username->delivery_info) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs transition">
                    حفظ التعديلات
                </button>
                <a href="{{ route('admin.usernames.index') }}" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs transition">
                    إلغاء
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
