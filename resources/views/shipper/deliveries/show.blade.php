@extends('shipper.layouts.app')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('shipper.deliveries.index') }}" class="text-gray-400 hover:text-white transition">← Đơn hàng</a>
    <span class="text-gray-600">/</span>
    <h1 class="text-xl font-bold text-white">{{ $order->order_number }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Thông tin đơn --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Trạng thái --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-white">Trạng thái đơn hàng</h2>
                @php
                    $statusColors = [
                        'shipping'        => 'bg-indigo-500/20 text-indigo-400',
                        'picked_up'       => 'bg-cyan-500/20 text-cyan-400',
                        'completed'       => 'bg-green-500/20 text-green-400',
                        'failed_delivery' => 'bg-red-500/20 text-red-400',
                    ];
                    $statusLabels = [
                        'shipping'        => '📋 Chờ nhận hàng từ kho',
                        'picked_up'       => '🚴 Đang trên đường giao',
                        'completed'       => '✅ Giao thành công',
                        'failed_delivery' => '❌ Giao thất bại',
                    ];
                @endphp
                <span class="inline-block px-3 py-1 rounded-full text-sm font-bold {{ $statusColors[$order->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </span>
            </div>

            {{-- Timeline 4 bước --}}
            <div class="flex items-center gap-2 mt-4">
                <div class="flex-1 text-center">
                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-sm
                        {{ in_array($order->status, ['shipping','picked_up','completed','failed_delivery']) ? 'bg-cyan-500 text-white' : 'bg-gray-700 text-gray-400' }}">✓</div>
                    <div class="text-xs text-gray-400 mt-1">Đã xác nhận</div>
                </div>
                <div class="flex-1 h-0.5 {{ in_array($order->status, ['shipping','picked_up','completed','failed_delivery']) ? 'bg-cyan-500' : 'bg-gray-700' }}"></div>
                <div class="flex-1 text-center">
                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-sm
                        {{ in_array($order->status, ['shipping','picked_up','completed','failed_delivery']) ? 'bg-indigo-500 text-white' : 'bg-gray-700 text-gray-400' }}">📋</div>
                    <div class="text-xs text-gray-400 mt-1">Chờ nhận</div>
                </div>
                <div class="flex-1 h-0.5 {{ in_array($order->status, ['picked_up','completed','failed_delivery']) ? 'bg-cyan-500' : 'bg-gray-700' }}"></div>
                <div class="flex-1 text-center">
                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-sm
                        {{ in_array($order->status, ['picked_up','completed','failed_delivery']) ? 'bg-cyan-500 text-white' : 'bg-gray-700 text-gray-400' }}">🚴</div>
                    <div class="text-xs text-gray-400 mt-1">Đang giao</div>
                </div>
                <div class="flex-1 h-0.5 {{ $order->status === 'completed' ? 'bg-green-500' : ($order->status === 'failed_delivery' ? 'bg-red-500' : 'bg-gray-700') }}"></div>
                <div class="flex-1 text-center">
                    @if($order->status === 'failed_delivery')
                        <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-sm bg-red-500 text-white">✗</div>
                        <div class="text-xs text-red-400 mt-1">Thất bại</div>
                    @else
                        <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-sm {{ $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-700 text-gray-400' }}">✓</div>
                        <div class="text-xs text-gray-400 mt-1">Hoàn thành</div>
                    @endif
                </div>
            </div>

            @if($order->completed_at)
                <p class="text-center text-xs text-green-400 mt-3">Giao thành công lúc {{ $order->completed_at->format('H:i, d/m/Y') }}</p>
            @endif
        </div>

        {{-- Thông tin giao hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">📍 Thông tin giao hàng</h2>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <div class="text-gray-400 mb-0.5">Người nhận</div>
                    <div class="text-white font-medium">{{ $order->shipping_name }}</div>
                </div>
                <div>
                    <div class="text-gray-400 mb-0.5">Số điện thoại</div>
                    <a href="tel:{{ $order->shipping_phone }}" class="text-cyan-400 font-medium hover:underline">
                        {{ $order->shipping_phone }}
                    </a>
                </div>
                <div class="col-span-2">
                    <div class="text-gray-400 mb-0.5">Địa chỉ</div>
                    <div class="text-white">{{ collect([$order->shipping_address, $order->shipping_district, $order->shipping_city, $order->shipping_country])->filter()->implode(', ') }}</div>
                </div>
                @if($order->note)
                <div class="col-span-2">
                    <div class="text-gray-400 mb-0.5">Ghi chú</div>
                    <div class="text-yellow-300 bg-yellow-500/10 border border-yellow-500/30 rounded-lg px-3 py-2 text-sm whitespace-pre-line">{{ $order->note }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Sản phẩm --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="font-semibold text-white mb-4">🛍️ Sản phẩm</h2>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg">
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium truncate">{{ $item->product_name }}</div>
                        @if($item->variant)
                            <div class="text-gray-400 text-xs">{{ $item->variant->name ?? '' }}</div>
                        @endif
                        <div class="text-gray-500 text-xs">SKU: {{ $item->sku }}</div>
                    </div>
                    <div class="text-right text-sm shrink-0">
                        <div class="text-gray-300">x{{ $item->quantity }}</div>
                        <div class="text-cyan-400 font-medium">{{ number_format($item->price * $item->quantity) }}₫</div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Tổng --}}
            <div class="mt-4 pt-4 border-t border-gray-700 space-y-2">
                <div class="flex justify-between text-sm text-gray-400">
                    <span>Phí vận chuyển</span>
                    <span>{{ number_format($order->shipping_fee ?? 0) }}₫</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-medium">Tổng cộng</span>
                    <span class="text-cyan-400 text-xl font-bold">{{ number_format($order->total_amount) }}₫</span>
                </div>
                <div class="flex justify-between text-sm pt-1 border-t border-gray-700">
                    <span class="text-gray-400">Thanh toán</span>
                    @if($order->payment_status === 'paid')
                        <span class="text-green-400 font-semibold">✅ Đã thanh toán</span>
                    @else
                        <span class="text-orange-400 font-semibold">⚠️ Thu tiền khi giao (COD)</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT: Cập nhật trạng thái --}}
    <div>
        @if($order->status === 'shipping')
        {{-- Bước 1: Xác nhận nhận hàng từ kho --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 sticky top-20">
            <h2 class="font-semibold text-white mb-1">📋 Bước 1: Nhận hàng từ kho</h2>
            <p class="text-gray-400 text-sm mb-4">Xác nhận bạn đã lấy đơn hàng này từ kho và sẵn sàng đi giao.</p>

            <form action="{{ route('shipper.deliveries.pickup', $order) }}" method="POST">
                @csrf
                <button type="submit"
                    onclick="return confirm('Xác nhận đã nhận hàng từ kho?')"
                    class="w-full bg-cyan-500 hover:bg-cyan-600 text-white py-3 rounded-xl font-bold transition text-sm">
                    📦 Xác nhận đã nhận hàng
                </button>
            </form>
        </div>

        @elseif($order->status === 'picked_up')
        {{-- Bước 2: Cập nhật kết quả giao hàng --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 sticky top-20">
            <h2 class="font-semibold text-white mb-4">🔄 Bước 2: Kết quả giao hàng</h2>

            {{-- Giao thành công --}}
            <form action="{{ route('shipper.deliveries.updateStatus', $order) }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="status" value="completed">
                <textarea name="note" rows="2" placeholder="Ghi chú (tuỳ chọn)..."
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none resize-none mb-3"></textarea>
                <button type="submit"
                    onclick="return confirm('Xác nhận đã giao hàng thành công?')"
                    class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-bold transition text-sm">
                    ✅ Giao thành công
                    @if($order->payment_status === 'unpaid')
                        <span class="block text-xs font-normal opacity-80 mt-0.5">Đã thu tiền COD – tự động đổi "Đã thanh toán"</span>
                    @endif
                </button>
            </form>

            <div class="border-t border-gray-700 pt-4">
                {{-- Giao thất bại --}}
                <form action="{{ route('shipper.deliveries.updateStatus', $order) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="failed_delivery">
                    <textarea name="note" rows="2" placeholder="Lý do thất bại (vắng nhà, sai địa chỉ...)" required
                        class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none resize-none mb-3"></textarea>
                    <button type="submit"
                        onclick="return confirm('Xác nhận giao hàng thất bại?')"
                        class="w-full bg-red-500/20 hover:bg-red-500/30 border border-red-500/50 text-red-400 py-3 rounded-xl font-semibold transition text-sm">
                        ❌ Giao thất bại
                    </button>
                </form>
            </div>
        </div>

        @elseif($order->status === 'completed')
        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-6 text-center">
            <div class="text-4xl mb-2">🎉</div>
            <div class="font-bold text-green-400 text-lg">Giao thành công!</div>
            <div class="text-gray-400 text-sm mt-1">{{ $order->completed_at?->format('H:i, d/m/Y') }}</div>
            <div class="mt-3 text-xs text-green-400/80 bg-green-500/10 rounded-lg px-3 py-2">
                ✅ Thanh toán đã được ghi nhận
            </div>
        </div>

        @elseif($order->status === 'failed_delivery')
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 text-center">
            <div class="text-4xl mb-2">❌</div>
            <div class="font-bold text-red-400 text-lg">Giao thất bại</div>
            <div class="text-gray-400 text-sm mt-2">Admin đang xử lý đơn hàng này.</div>
        </div>
        @endif
    </div>

</div>

@endsection
