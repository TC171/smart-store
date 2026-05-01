@extends('shipper.layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">
        👋 Xin chào, {{ auth('shipper')->user()->name }}!
    </h1>
    <p class="text-gray-400 text-sm mt-1">{{ now()->format('l, d/m/Y') }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-indigo-500/10 border border-indigo-500/30 rounded-xl p-5 text-center">
    <div class="text-3xl font-bold text-indigo-400">{{ $stats['shipping'] }}</div>
    <div class="text-gray-400 text-sm mt-1">📋 Chờ lấy hàng</div>
</div>
<div class="bg-cyan-500/10 border border-cyan-500/30 rounded-xl p-5 text-center">
    <div class="text-3xl font-bold text-cyan-400">{{ $stats['picked_up'] }}</div>
    <div class="text-gray-400 text-sm mt-1">🚴 Đang giao hàng</div>
</div>

    <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-green-400">{{ $stats['completed'] }}</div>
        <div class="text-gray-400 text-sm mt-1">✅ Đã giao</div>
    </div>
    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-red-400">{{ $stats['failed_delivery'] }}</div>
        <div class="text-gray-400 text-sm mt-1">❌ Thất bại</div>
    </div>
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-white">{{ $stats['total'] }}</div>
        <div class="text-gray-400 text-sm mt-1">📦 Tổng đơn</div>
    </div>
</div>

{{-- Đơn đang cần giao --}}
<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-white">🔥 Đơn đang cần xử lý</h2>
        <a href="{{ route('shipper.deliveries.index', ['status' => 'shipping']) }}"
           class="text-cyan-400 hover:text-cyan-300 text-sm transition">Xem tất cả →</a>
    </div>

    @if($activeOrders->count() > 0)
    <div class="space-y-3">
        @foreach($activeOrders as $order)
        <div class="bg-gray-900 border border-gray-800 hover:border-gray-600 transition rounded-xl p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-white font-bold">{{ $order->order_number }}</span>
                        @if($order->status === 'picked_up')
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-cyan-500/20 text-cyan-400">🚴 Đang đi giao</span>
                        @else
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-400">📋 Chờ nhận hàng</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-400 space-y-1">
                        <div>👤 {{ $order->shipping_name }} – {{ $order->shipping_phone }}</div>
                        <div>📍 {{ collect([$order->shipping_address, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') }}</div>
                        <div>💰 <span class="text-cyan-400 font-medium">{{ number_format($order->total_amount) }}₫</span>
                            @if($order->payment_status === 'unpaid')
                                <span class="ml-2 text-orange-400 text-xs font-semibold">⚠️ Thu tiền COD</span>
                            @else
                                <span class="ml-2 text-green-400 text-xs">✓ Đã thanh toán</span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('shipper.deliveries.show', $order) }}"
                   class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap">
                    Nhận Đơn 
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-gray-900 border border-gray-800 rounded-xl py-12 text-center">
        <div class="text-4xl mb-3">🎉</div>
        <p class="text-gray-400">Không có đơn hàng nào đang cần giao!</p>
    </div>
    @endif
</div>

@endsection
