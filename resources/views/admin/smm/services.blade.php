@extends('layouts.admin')

@section('page_title', 'إدارة خدمات المتابعين والتفاعل (SMM Services)')

@section('admin_content')
<div class="space-y-6" x-data="{ showCreateModal: false }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <span class="text-xs text-slate-400">إجمالي الخدمات المضافة: <strong class="text-white">{{ $services->total() }}</strong></span>
        <button @click="showCreateModal = true" class="px-5 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs shadow-lg shadow-pink-600/25 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> إضافة خدمة جديدة
        </button>
    </div>

    <!-- Services Table -->
    <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-black/50 text-slate-400 border-b border-white/10">
                    <tr>
                        <th class="py-3 px-6">الخدمة والتصنيف</th>
                        <th class="py-3 px-4 text-center">السعر / 1K</th>
                        <th class="py-3 px-4 text-center">الحد الأدنى والأقصى</th>
                        <th class="py-3 px-4 text-center">نوع التنفيذ</th>
                        <th class="py-3 px-4 text-center">المزود الخارجي</th>
                        <th class="py-3 px-4 text-center">الحالة</th>
                        <th class="py-3 px-6 text-left">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($services as $serv)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="py-4 px-6">
                                <div class="font-bold text-white text-sm mb-0.5">{{ $serv->name_ar }}</div>
                                <div class="text-[11px] text-pink-400 font-mono">{{ $serv->category->name_ar ?? 'بدون تصنيف' }}</div>
                            </td>
                            <td class="py-4 px-4 text-center font-mono font-bold text-emerald-400 text-sm">
                                ${{ number_format($serv->price_per_1k, 4) }}
                            </td>
                            <td class="py-4 px-4 text-center font-mono text-slate-300">
                                {{ number_format($serv->min_quantity) }} - {{ number_format($serv->max_quantity) }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($serv->execution_type === 'api')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">تلقائي API</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">يدوي Admin</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center font-mono text-[11px] text-slate-400">
                                @if($serv->provider)
                                    <span class="text-white">{{ $serv->provider->name }}</span>
                                    <span class="block text-[10px] text-slate-500">ID: {{ $serv->provider_service_id }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($serv->status)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">نشط</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-500/10 text-rose-400 border border-rose-500/20">معطل</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-left">
                                <form action="{{ route('admin.services.destroy', $serv->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition" title="حذف">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">لا توجد خدمات مضافة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-white/10">
            {{ $services->links() }}
        </div>
    </div>

    <!-- Create Service Modal -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="showCreateModal = false" class="bg-dark-900 border border-white/10 rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-white/10">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-pink-400"></i> إضافة خدمة SMM جديدة
                </h3>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-4" x-data="{ execType: 'manual' }">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">التصنيف</label>
                    <select name="category_id" required class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name_ar }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">اسم الخدمة (عربي)</label>
                        <input type="text" name="name_ar" required placeholder="مثال: متابعين انستقرام حقيقيين"
                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">اسم الخدمة (إنجليزي)</label>
                        <input type="text" name="name_en" required placeholder="Instagram Real Followers"
                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white dir-ltr text-left">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">السعر لكل 1,000 ($ USDT)</label>
                        <input type="number" step="0.0001" min="0.0001" name="price_per_1k" required placeholder="1.5000"
                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">الحد الأدنى</label>
                        <input type="number" min="1" name="min_quantity" value="100" required
                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">الحد الأقصى</label>
                        <input type="number" min="1" name="max_quantity" value="50000" required
                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">طريقة التنفيذ</label>
                    <select name="execution_type" x-model="execType" class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                        <option value="manual">تنفيذ يدوي (يقوم المدير بمراجعة الطلب وتنفيذه)</option>
                        <option value="api">تلقائي عبر API المزود الخارجي (Instant SMM API)</option>
                    </select>
                </div>

                <!-- API Provider Fields -->
                <div x-show="execType === 'api'" class="p-4 rounded-xl bg-white/5 border border-white/10 space-y-4">
                    <div class="font-bold text-sky-400 text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-server"></i> إعدادات الربط مع المزود الخارجي:
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">اختر المزود</label>
                            <select name="provider_id" class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                                <option value="">-- اختر سيرفر المزود --</option>
                                @foreach($providers as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">رقم الخدمة عند المزود (Service ID)</label>
                            <input type="text" name="provider_service_id" placeholder="مثال: 1024"
                                class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">وصف الخدمة وشروطها للعميل</label>
                    <textarea name="description" rows="2" placeholder="وصف الخدمة ومميزاتها ونسبة النقص وسرعة البدء..."
                        class="w-full bg-black/50 border border-white/10 rounded-xl p-3 text-xs text-white"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl bg-white/5 text-slate-300 text-xs">إلغاء</button>
                    <button type="submit" class="px-6 py-2 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs transition">حفظ الخدمة</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
