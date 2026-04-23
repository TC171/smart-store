@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.shippers.index') }}" class="text-gray-400 hover:text-white transition">← Shipper</a>
            <span class="text-gray-600">/</span>
            <h1 class="text-2xl font-bold text-white">📦 Phân công đơn hàng cho Shipper</h1>
        </div>
        <a href="{{ route('admin.shippers.deliveries') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm transition">
            🗺️ Theo dõi giao hàng
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    @if($shippers->isEmpty())
        <div class="bg-yellow-500/10 border border-yellow-500/50 text-yellow-400 px-4 py-4 rounded-lg mb-6">
            ⚠️ Chưa có shipper nào đang hoạt động. <a href="{{ route('admin.shippers.create') }}" class="underline font-medium">Thêm shipper ngay</a>.
        </div>
    @endif

    {{-- Bộ lọc --}}
    <div class="mb-6 bg-gray-900 p-4 rounded-xl">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Tìm mã đơn, tên, SĐT..." value="{{ request('search') }}"
                class="bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 w-80">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">Tìm kiếm</button>
            <a href="{{ route('admin.shippers.assign') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Xóa lọc</a>
        </form>
    </div>

    <div class="bg-gray-900/50 border border-gray-700 rounded-xl p-4 mb-4">
        <p class="text-gray-400 text-sm">
            📋 Hiển thị các đơn hàng đã <span class="text-blue-400 font-medium">xác nhận</span> và chưa được phân công shipper.
            Sau khi phân công, đơn sẽ tự chuyển sang trạng thái <span class="text-indigo-400 font-medium">Đang giao hàng</span>.
        </p>
    </div>

    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-gray-900 rounded-xl border border-gray-800 hover:border-gray-600 transition p-5">
            <div class="flex flex-col lg:flex-row gap-4">

                {{-- Thông tin đơn --}}
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-white font-bold text-lg">{{ $order->order_number }}</span>
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-blue-500/20 text-blue-400 font-medium">Đã xác nhận</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div><span class="text-gray-400">👤</span> <span class="text-white">{{ $order->shipping_name ?? $order->user?->name }}</span></div>
                        <div><span class="text-gray-400">📱</span> <span class="text-white">{{ $order->shipping_phone ?? 'N/A' }}</span></div>
                        <div class="col-span-2 text-gray-300">📍 {{ collect([$order->shipping_address, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') ?: 'N/A' }}</div>
                        <div>
                            <span class="text-gray-400">💰</span>
                            <span class="text-cyan-400 font-bold">{{ number_format($order->total_amount) }}₫</span>
                            @if($order->payment_status === 'unpaid')
                                <span class="ml-1 text-orange-400 text-xs">COD</span>
                            @endif
                        </div>
                        <div class="text-gray-400">📅 {{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                {{-- Form phân công --}}
                <div class="lg:w-72 bg-gray-800 rounded-lg p-4">
                    <p class="text-gray-300 text-sm font-medium mb-3">🚴 Chọn Shipper</p>
                    <form action="{{ route('admin.shippers.assign.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <select name="shipper_id" required
                            class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                            <option value="">-- Chọn shipper --</option>
                            @foreach($shippers as $shipper)
                                <option value="{{ $shipper->id }}">
                                    {{ $shipper->name }}@if($shipper->phone) ({{ $shipper->phone }})@endif
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="w-full bg-cyan-500 hover:bg-cyan-600 text-white py-2 rounded-lg text-sm font-medium transition">
                            ✅ Phân công & chuyển sang Đang giao
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $orders->links('pagination::tailwind') }}</div>
    @else
    <div class="text-center py-16 bg-gray-900 rounded-xl">
        <div class="text-6xl mb-4">🎉</div>
        <p class="text-gray-400 text-lg font-medium">Không còn đơn nào cần phân công!</p>
        <p class="text-gray-500 text-sm mt-1">Tất cả đơn đã xác nhận đều đã có shipper.</p>
    </div>
    @endif

</div>
@endsection
