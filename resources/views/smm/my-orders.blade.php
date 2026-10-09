@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white">سجل طلبات المتابعين والتفاعل</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">تتبع حالة تنفيذ طلباتك بدقة لحظة بلحظة</p>
        </div>
        <a href="{{ route('smm.index') }}" class="px-5 py-2.5 rounded-xl insta-gradient text-white text-xs font-bold shadow-md hover:opacity-95 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> طلب جديد
        </a>
    </div>

    <div class="glass-card rounded-3xl border border-white/10 overflow-hidden">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-black/50 text-slate-400 border-b border-white/10">
                        <tr>
                            <th class="py-4 px-6 font-bold">رقم الطلب</th>
                            <th class="py-4 px-4 font-bold">الخدمة المطلوبة</th>
                            <th class="py-4 px-4 font-bold">الرابط المستهدف</th>
                            <th class="py-4 px-4 font-bold text-center">الكمية</th>
                            <th class="py-4 px-4 font-bold text-center">التكلفة</th>
                            <th class="py-4 px-4 font-bold text-center">الحالة</th>
                            <th class="py-4 px-6 font-bold text-left">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($orders as $order)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="py-4 px-6 font-mono font-bold text-white">
                                    #{{ $order->order_number }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-white max-w-xs truncate">{{ $order->service->name_ar ?? 'خدمة غير متوفرة' }}</div>
                                </td>
                                <td class="py-4 px-4 max-w-[200px] truncate">
                                    <a href="{{ $order->link }}" target="_blank" class="text-pink-400 hover:underline font-mono text-[11px] dir-ltr inline-block">
                                        {{ Str::limit($order->link, 35) }} <i class="fa-solid fa-arrow-up-right-from-square text-[9px] mr-1"></i>
                                    </a>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-bold text-white">
                                    {{ number_format($order->quantity) }}
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-bold text-emerald-400">
                                    ${{ number_format($order->charge, 4) }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    {!! $order->status_badge !!}
                                </td>
                                <td class="py-4 px-6 text-left text-slate-400 font-mono text-[11px]">
                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-white/10">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-16 p-6">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-white/5 text-slate-500 flex items-center justify-center text-3xl mb-4">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">لا توجد لديك طلبات سابقة</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mb-6">ابدأ الآن بتزويد حسابك بالمتابعين، اللايكات، أو مشاهدات الريلز عبر نظامنا الفوري.</p>
                <a href="{{ route('smm.index') }}" class="px-6 py-3 rounded-xl insta-gradient text-white text-xs font-bold shadow-md hover:opacity-95 transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-cart-plus"></i> تقديم أول طلب
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
