@extends('layouts.admin')

@section('page_title', 'مزودو سيرفرات SMM الخارجيين (API Providers)')

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Add Provider Form -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 h-fit">
        <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-server text-sky-400"></i> إضافة مزود SMM API جديد
        </h3>

        <form action="{{ route('admin.providers.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">اسم المزود / السيرفر</label>
                <input type="text" name="name" required placeholder="مثال: Peakerr SMM / JustExpan"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">رابط الـ API (API URL)</label>
                <input type="url" name="api_url" required placeholder="https://provider.com/api/v2"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-500 dir-ltr text-left">
                <span class="text-[10px] text-slate-500 mt-1 block">يدعم بروتوكول SMM API v2 القياسي</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">مفتاح الـ API (API Key)</label>
                <input type="password" name="api_key" required placeholder="مفتاح الـ API الخاص بحسابك لديهم"
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-500 dir-ltr text-left">
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs transition">
                إضافة والتحقق من الاتصال
            </button>
        </form>
    </div>

    <!-- Providers List -->
    <div class="lg:col-span-2 glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="bg-white/5 px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <h3 class="text-sm font-bold text-white">المزودين المربوطين</h3>
            <span class="text-xs text-slate-400 font-mono">{{ $providers->count() }} مزود</span>
        </div>

        <div class="divide-y divide-white/5 text-xs">
            @forelse($providers as $prov)
                <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-white/[0.02] transition">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-white text-sm">{{ $prov->name }}</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="text-[11px] font-mono text-slate-400 mt-1 dir-ltr text-right">
                            {{ $prov->api_url }}
                        </div>
                        <div class="mt-2 text-[11px] text-slate-400 flex items-center gap-3">
                            <span>الخدمات المربوطة: <strong class="text-white">{{ $prov->services_count }}</strong></span>
                            <span>•</span>
                            <span>إجمالي الطلبات: <strong class="text-white">{{ $prov->orders_count }}</strong></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="text-left font-mono bg-black/40 px-3 py-2 rounded-xl border border-white/10">
                            <span class="text-[10px] text-slate-400 block">رصيد حسابك لديهم</span>
                            <span class="text-sm font-bold text-emerald-400">${{ number_format($prov->balance, 2) }} {{ $prov->currency }}</span>
                        </div>

                        <form action="{{ route('admin.providers.syncBalance', $prov->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-sky-400 transition" title="تحديث الرصيد الآن">
                                <i class="fa-solid fa-arrows-rotate"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.providers.destroy', $prov->id) }}" method="POST" onsubmit="return confirm('حذف هذا المزود؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition" title="حذف">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">لا يوجد مزودين مضافين حتى الآن.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
