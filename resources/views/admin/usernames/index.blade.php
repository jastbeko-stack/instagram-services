@extends('layouts.admin')

@section('page_title', 'إدارة يوزرات وحسابات انستقرام')

@section('admin_content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-xs text-slate-400">إجمالي المعروض: <strong class="text-white">{{ $usernames->total() }}</strong></span>
        </div>
        <a href="{{ route('admin.usernames.create') }}" class="px-5 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs shadow-lg shadow-pink-600/25 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> إضافة يوزر / حساب جديد
        </a>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4 border border-white/10">
        <form action="{{ route('admin.usernames.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">بحث بالاسم</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="@username" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">الحالة</label>
                <select name="status" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                    <option value="">الكل</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>متاح (Available)</option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>محجوز (Reserved)</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>تم البيع (Sold)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">النوع</label>
                <select name="type" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                    <option value="">الكل</option>
                    <option value="quad" {{ request('type') == 'quad' ? 'selected' : '' }}>رباعي</option>
                    <option value="tri_semi" {{ request('type') == 'tri_semi' ? 'selected' : '' }}>شبه ثلاثي</option>
                    <option value="vintage" {{ request('type') == 'vintage' ? 'selected' : '' }}>قديم</option>
                    <option value="verified" {{ request('type') == 'verified' ? 'selected' : '' }}>موثق</option>
                    <option value="special" {{ request('type') == 'special' ? 'selected' : '' }}>مميز</option>
                </select>
            </div>
            <button type="submit" class="py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                <i class="fa-solid fa-filter ml-1"></i> تصفية
            </button>
        </form>
    </div>

    <!-- Usernames Table -->
    <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-black/50 text-slate-400 border-b border-white/10">
                    <tr>
                        <th class="py-3 px-6">اليوزر</th>
                        <th class="py-3 px-4">النوع</th>
                        <th class="py-3 px-4">المتابعين</th>
                        <th class="py-3 px-4">السنة</th>
                        <th class="py-3 px-4">السعر</th>
                        <th class="py-3 px-4">الحالة</th>
                        <th class="py-3 px-6 text-left">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($usernames as $u)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="py-4 px-6 font-bold text-white font-outfit text-sm dir-ltr text-right">
                                @<span>{{ $u->username }}</span>
                            </td>
                            <td class="py-4 px-4 text-slate-300">
                                {{ $u->formatted_type }}
                            </td>
                            <td class="py-4 px-4 font-mono text-slate-300">
                                {{ number_format($u->followers_count) }}
                            </td>
                            <td class="py-4 px-4 font-mono text-slate-400">
                                {{ $u->creation_year ?? '-' }}
                            </td>
                            <td class="py-4 px-4 font-mono font-bold text-emerald-400 text-sm">
                                ${{ number_format($u->price, 2) }}
                            </td>
                            <td class="py-4 px-4">
                                {!! $u->status_badge !!}
                            </td>
                            <td class="py-4 px-6 text-left">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.usernames.edit', $u->id) }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white transition" title="تعديل">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.usernames.destroy', $u->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا اليوزر؟')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition" title="حذف">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">لا توجد يوزرات مسجلة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-white/10">
            {{ $usernames->links() }}
        </div>
    </div>

</div>
@endsection
