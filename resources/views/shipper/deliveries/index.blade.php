@extends('shipper.layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">📦 Đơn hàng của tôi</h1>
</div>

{{-- Bộ lọc --}}
<div class="bg-gray-900 rounded-xl p-4 mb-6 border border-gray-800">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" placeholder="Tìm mã đơn, tên, SĐT..." value="{{ request('search') }}"
            class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-cyan-500 focus:outline-none flex-1 min-w-[200px]">
        <select name="status" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            <option value="">-- Tất cả --</option>
            <option value="shipping"        {{ request('status') === 'shipping'        ? 'selected' : '' }}>📋 Chờ nhận hàng</option>
            <option value="picked_up"       {{ request('status') === 'picked_up'       ? 'selected' : '' }}>🚴 Đang đi giao</option>
            <option value="completed"       {{ request('status') === 'completed'       ? 'selected' : '' }}>✅ Đã giao</option>
            <option value="failed_delivery" {{ request('status') === 'failed_delivery' ? 'selected' : '' }}>❌ Thất bại</option>
        </select>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm transition">Lọc</button>
        <a href="{{ route('shipper.deliveries.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">Xóa lọc</a>
    </form>
</div>

@php
    $statusColors = [
        'shipping'        => 'bg-indigo-500/20 text-indigo-400',
        'picked_up'       => 'bg-cyan-500/20 text-cyan-400',
        'completed'       => 'bg-green-500/20 text-green-400',
        'failed_delivery' => 'bg-red-500/20 text-red-400',
    ];
    $statusLabels = [
        'shipping'        => '📋 Chờ nhận hàng',
        'picked_up'       => '🚴 Đang đi giao',
        'completed'       => '✅ Đã giao thành công',
        'failed_delivery' => '❌ Giao thất bại',
    ];
@endphp

@if($orders->count() > 0)
<div class="space-y-3">
    @foreach($orders as $order)
    <div class="bg-gray-900 border border-gray-800 hover:border-gray-600 rounded-xl p-5 transition">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2 flex-wrap">
                    <span class="text-white font-bold">{{ $order->order_number ? $order->order_number : '#' . $order->id }}</span>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                    @if($order->payment_status === 'unpaid' && $order->status === 'shipping')
                        <span class="text-orange-400 text-xs font-semibold">⚠️ Thu tiền COD</span>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1.5 text-sm text-gray-400">
                    <div>👤 <span class="text-gray-200">{{ $order->shipping_name }}</span> – {{ $order->shipping_phone }}</div>
                    <div>💰 <span class="text-cyan-400 font-medium">{{ number_format($order->total_amount) }}₫</span></div>
                    <div class="md:col-span-2">📍 {{ collect([$order->shipping_address, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') ?: 'N/A' }}</div>
                    @if($order->completed_at)
                        <div class="text-green-400 text-xs">✅ Giao lúc: {{ $order->completed_at->format('d/m/Y H:i') }}</div>
                    @endif
                </div>
            </div>
            <div>
                <a href="{{ route('shipper.deliveries.show', $order) }}"
                   class="inline-block {{ in_array($order->status, ['shipping','picked_up']) ? 'bg-cyan-500 hover:bg-cyan-600' : 'bg-gray-700 hover:bg-gray-600' }} text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                    {{ in_array($order->status, ['shipping','picked_up']) ? 'Cập nhật' : 'Xem chi tiết' }}
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="mt-6">{{ $orders->links('pagination::tailwind') }}</div>
@else
<div class="text-center py-16 bg-gray-900 rounded-xl border border-gray-800">
    <div class="text-5xl mb-4">📭</div>
    <p class="text-gray-400">Chưa có đơn hàng nào.</p>
</div>
@endif

@endsection
