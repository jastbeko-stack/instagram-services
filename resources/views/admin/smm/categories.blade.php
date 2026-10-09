@extends('layouts.admin')

@section('page_title', 'تصنيفات خدمات السوشيال ميديا (Categories)')

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Add Category Form -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 h-fit">
        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-folder-plus text-pink-400"></i> إضافة تصنيف جديد
        </h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">الاسم بالعربية</label>
                <input type="text" name="name_ar" required placeholder="مثال: متابعين انستقرام حقيقيين"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">الاسم بالإنجليزية</label>
                <input type="text" name="name_en" required placeholder="e.g. Instagram Real Followers"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-500 dir-ltr text-left">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">أيقونة FontAwesome (مثال: fa-users)</label>
                <input type="text" name="icon" placeholder="fa-users"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-500 dir-ltr text-left">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">ترتيب الظهور</label>
                <input type="number" name="sort_order" value="0"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-500">
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs transition">
                إضافة التصنيف
            </button>
        </form>
    </div>

    <!-- Categories List -->
    <div class="lg:col-span-2 glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="bg-white/5 px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <h3 class="text-sm font-bold text-white">التصنيفات المتاحة</h3>
            <span class="text-xs text-slate-400 font-mono">{{ $categories->count() }} تصنيف</span>
        </div>

        <div class="divide-y divide-white/5 text-xs">
            @forelse($categories as $cat)
                <div class="p-4 flex items-center justify-between hover:bg-white/[0.02] transition">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center text-sm">
                            <i class="fa-solid {{ $cat->icon ?? 'fa-layer-group' }}"></i>
                        </div>
                        <div>
                            <div class="font-bold text-white text-sm">{{ $cat->name_ar }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $cat->name_en }} • {{ $cat->all_services_count }} خدمات تابعة</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('حذف هذا التصنيف سيحذف جميع الخدمات المندرجة تحته، هل أنت متأكد؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition" title="حذف">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">لا توجد تصنيفات بعد.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
