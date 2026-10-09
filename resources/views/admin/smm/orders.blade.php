@extends('layouts.admin')

@section('page_title', 'إدارة ومتابعة طلبات المتابعين والتفاعل')

@section('admin_content')
<div class="space-y-6">

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4 border border-white/10">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">بحث برقم الطلب أو الرابط أو العميل</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث هنا..." class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">الحالة</label>
                <select name="status" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق (Pending)</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ (In Progress)</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل (Completed)</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>ملغي (Canceled)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">نوع التنفيذ</label>
                <select name="execution_type" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                    <option value="">الكل</option>
                    <option value="api" {{ request('execution_type') == 'api' ? 'selected' : '' }}>تلقائي API</option>
                    <option value="manual" {{ request('execution_type') == 'manual' ? 'selected' : '' }}>يدوي</option>
                </select>
            </div>
            <button type="submit" class="py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                <i class="fa-solid fa-filter ml-1"></i> تصفية
            </button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="glass-card rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-black/50 text-slate-400 border-b border-white/10">
                    <tr>
                        <th class="py-3 px-6">رقم الطلب</th>
                        <th class="py-3 px-4">العميل</th>
                        <th class="py-3 px-4">الخدمة</th>
                        <th class="py-3 px-4">الرابط</th>
                        <th class="py-3 px-4 text-center">الكمية</th>
                        <th class="py-3 px-4 text-center">التكلفة</th>
                        <th class="py-3 px-4 text-center">نوع التنفيذ</th>
                        <th class="py-3 px-4 text-center">الحالة</th>
                        <th class="py-3 px-6 text-left">تحديث الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="py-4 px-6 font-mono font-bold text-white">
                                #{{ $ord->order_number }}
                                @if($ord->provider_order_id)
                                    <span class="block text-[10px] text-sky-400">API ID: {{ $ord->provider_order_id }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-white">{{ $ord->user->name ?? 'مستخدم' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $ord->user->email ?? '' }}</div>
                            </td>
                            <td class="py-4 px-4 max-w-xs">
                                <div class="text-white truncate font-medium">{{ $ord->service->name_ar ?? 'محذوفة' }}</div>
                            </td>
                            <td class="py-4 px-4 max-w-[160px] truncate">
                                <a href="{{ $ord->link }}" target="_blank" class="text-pink-400 hover:underline font-mono text-[11px] dir-ltr inline-block">
                                    {{ Str::limit($ord->link, 25) }}
                                </a>
                            </td>
                            <td class="py-4 px-4 text-center font-mono font-bold text-white">
                                {{ number_format($ord->quantity) }}
                            </td>
                            <td class="py-4 px-4 text-center font-mono font-bold text-emerald-400">
                                ${{ number_format($ord->charge, 4) }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($ord->execution_type === 'api')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-sky-500/10 text-sky-400 border border-sky-500/20 font-mono">API</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-500/10 text-amber-400 border border-amber-500/20 font-mono">يدوي</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                {!! $ord->status_badge !!}
                            </td>
                            <td class="py-4 px-6 text-left">
                                <div class="flex items-center justify-end gap-2" x-data="{ openEdit: false }">
                                    <button @click="openEdit = !openEdit" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white transition font-bold text-[11px] flex items-center gap-1">
                                        <i class="fa-solid fa-sliders"></i> تعديل
                                    </button>

                                    @if($ord->execution_type === 'api' && !$ord->provider_order_id)
                                        <form action="{{ route('admin.orders.retry', $ord->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 transition" title="إعادة إرسال للـ API">
                                                <i class="fa-solid fa-rotate-right"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Status Update Modal -->
                                    <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                                        <div @click.away="openEdit = false" class="bg-dark-900 border border-white/10 rounded-2xl p-6 max-w-md w-full shadow-2xl text-right">
                                            <h4 class="text-sm font-bold text-white mb-3">تحديث حالة الطلب #{{ $ord->order_number }}</h4>
                                            <form action="{{ route('admin.orders.updateStatus', $ord->id) }}" method="POST" class="space-y-4">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-300 mb-1">الحالة الجديدة</label>
                                                    <select name="status" class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white">
                                                        <option value="pending" {{ $ord->status == 'pending' ? 'selected' : '' }}>قيد الانتظار (Pending)</option>
                                                        <option value="in_progress" {{ $ord->status == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ (In Progress)</option>
                                                        <option value="completed" {{ $ord->status == 'completed' ? 'selected' : '' }}>مكتمل (Completed)</option>
                                                        <option value="partial" {{ $ord->status == 'partial' ? 'selected' : '' }}>مكتمل جزئياً (Partial)</option>
                                                        <option value="canceled" {{ $ord->status == 'canceled' ? 'selected' : '' }}>إلغاء الطلب (Canceled)</option>
                                                    </select>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-300 mb-1">العداد المبدئي</label>
                                                        <input type="number" name="start_count" value="{{ $ord->start_count }}" placeholder="Start Count"
                                                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-300 mb-1">المتبقي</label>
                                                        <input type="number" name="remains" value="{{ $ord->remains }}" placeholder="Remains"
                                                            class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs text-white font-mono dir-ltr text-left">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-300 mb-1">ملاحظات الإدارة</label>
                                                    <textarea name="admin_notes" rows="2" class="w-full bg-black/50 border border-white/10 rounded-xl p-2.5 text-xs text-white">{{ $ord->admin_notes }}</textarea>
                                                </div>

                                                <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                                                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300">
                                                        <input type="checkbox" name="refund_balance" value="1" class="rounded bg-black text-pink-600 focus:ring-0">
                                                        <span>استرجاع التكلفة (${{ number_format($ord->charge, 4) }}) لمحفظة العميل عند الإلغاء</span>
                                                    </label>
                                                </div>

                                                <div class="flex items-center justify-end gap-2 pt-2">
                                                    <button type="button" @click="openEdit = false" class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs">إلغاء</button>
                                                    <button type="submit" class="px-4 py-1.5 rounded-lg bg-pink-600 text-white font-bold text-xs">تحديث</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-500">لا توجد طلبات تطابق معايير البحث.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-white/10">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
