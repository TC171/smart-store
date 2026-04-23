@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.shippers.index') }}" class="text-gray-400 hover:text-white transition">← Shipper</a>
            <span class="text-gray-600">/</span>
            <h1 class="text-2xl font-bold text-white">🗺️ Theo dõi giao hàng</h1>
        </div>
        <a href="{{ route('admin.shippers.assign') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-sm transition">
            📦 Phân công thêm
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Bộ lọc --}}
    <div class="mb-6 bg-gray-900 p-4 rounded-xl">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Tìm mã đơn, tên khách..." value="{{ request('search') }}"
                class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 w-72">
            <select name="shipper_id" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                <option value="">-- Tất cả shipper --</option>
                @foreach($shippers as $s)
                    <option value="{{ $s->id }}" {{ request('shipper_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
            <select name="status" class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="shipping"        {{ request('status') === 'shipping'        ? 'selected' : '' }}>🚴 Đang giao</option>
                <option value="failed_delivery" {{ request('status') === 'failed_delivery' ? 'selected' : '' }}>❌ Giao thất bại</option>
                <option value="completed"       {{ request('status') === 'completed'       ? 'selected' : '' }}>✅ Giao thành công</option>
            </select>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">Lọc</button>
            <a href="{{ route('admin.shippers.deliveries') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Xóa lọc</a>
        </form>
    </div>

    @php
        $statusColors = [
            'shipping'        => 'bg-indigo-500/20 text-indigo-400',
            'completed'       => 'bg-green-500/20 text-green-400',
            'failed_delivery' => 'bg-red-500/20 text-red-400',
        ];
        $statusLabels = [
            'shipping'        => 'Đang giao',
            'completed'       => 'Giao thành công',
            'failed_delivery' => 'Giao thất bại',
        ];
    @endphp

    @if($orders->count() > 0)
    <div class="overflow-x-auto bg-gray-900 rounded-xl shadow-lg">
        <table class="w-full">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Mã đơn</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Khách hàng</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Địa chỉ giao</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Shipper</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Trạng thái</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Thanh toán</th>
                    <th class="px-5 py-3 text-left text-sm font-semibold text-gray-300">Giao xong</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b border-gray-800 hover:bg-gray-800/50 transition">
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-cyan-400 hover:text-cyan-300 font-medium">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-white text-sm">{{ $order->shipping_name }}</div>
                        <div class="text-gray-400 text-xs">{{ $order->shipping_phone }}</div>
                    </td>
                    <td class="px-5 py-4 text-gray-300 text-sm max-w-[200px]">
                        <div class="truncate">{{ collect([$order->shipping_address, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') ?: 'N/A' }}</div>
                    </td>
                    <td class="px-5 py-4">
                        @if($order->shipper)
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-cyan-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($order->shipper->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-white text-sm">{{ $order->shipper->name }}</div>
                                <div class="text-gray-400 text-xs">{{ $order->shipper->phone }}</div>
                            </div>
                        </div>
                        @else
                        <span class="text-gray-500 text-sm">–</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if($order->payment_status === 'paid')
                            <span class="text-green-400 text-xs font-semibold">✅ Đã thanh toán</span>
                        @else
                            <span class="text-orange-400 text-xs">Chưa thanh toán</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-sm">
                        @if($order->completed_at)
                            <span class="text-green-400">{{ $order->completed_at->format('d/m H:i') }}</span>
                        @elseif($order->status === 'failed_delivery')
                            <span class="text-red-400">Thất bại</span>
                        @else
                            <span class="text-gray-500">Đang giao...</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $orders->links('pagination::tailwind') }}</div>
    @else
    <div class="text-center py-16 bg-gray-900 rounded-xl">
        <div class="text-6xl mb-4">📭</div>
        <p class="text-gray-400 text-lg">Không có dữ liệu giao hàng nào</p>
    </div>
    @endif

</div>
@endsection
