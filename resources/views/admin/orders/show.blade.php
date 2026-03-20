@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">
            Chi tiết đơn hàng #{{ $order->order_number }}
        </h1>
        <a href="{{ route('orders.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            Quay lại
        </a>
    </div>

    @if (session('success'))
    <div class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Thông tin đơn hàng --}}
    <div class="bg-gray-900 p-6 rounded-xl shadow-lg mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-cyan-400 mb-4">Thông tin khách hàng</h3>
                <div class="space-y-2 text-gray-300">
                    <div>
                        <span class="text-gray-400">Tên:</span>
                        <span class="ml-2">{{ $order->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Email:</span>
                        <span class="ml-2">{{ $order->user->email }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Điện thoại:</span>
                        <span class="ml-2">{{ $order->user->phone ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-cyan-400 mb-4">Thông tin đơn hàng</h3>
                <div class="space-y-2 text-gray-300">
                    <div>
                        <span class="text-gray-400">Mã đơn:</span>
                        <span class="ml-2 font-medium">{{ $order->order_number }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Ngày đặt:</span>
                        <span class="ml-2">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Cập nhật lần cuối:</span>
                        <span class="ml-2">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Địa chỉ giao hàng --}}
    <div class="bg-gray-900 p-6 rounded-xl shadow-lg mb-6">
        <h3 class="text-lg font-semibold text-cyan-400 mb-4">Địa chỉ giao hàng</h3>
        <div class="text-gray-300 space-y-1">
            <p>{{ $order->shipping_address }}</p>
            <p>{{ $order->shipping_city ?? '' }} {{ $order->shipping_postal_code ?? '' }}</p>
            <p>{{ $order->shipping_country ?? '' }}</p>
        </div>
    </div>

    {{-- Sản phẩm --}}
    <div class="bg-gray-900 p-6 rounded-xl shadow-lg mb-6">
        <h3 class="text-lg font-semibold text-cyan-400 mb-4">Sản phẩm đã đặt</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800 border-b border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Sản phẩm</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">SKU</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-300">Đơn giá</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-300">Số lượng</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-300">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                    <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                        <td class="px-4 py-4 text-gray-300">
                            {{ $item->product_name ?? 'N/A' }}
                            @if ($item->variant && $item->variant->color)
                            <span class="text-gray-500 ml-2">({{ $item->variant->color }})</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-gray-300">{{ $item->sku }}</td>
                        <td class="px-4 py-4 text-right text-gray-300">{{ number_format($item->price) }}₫</td>
                        <td class="px-4 py-4 text-right text-gray-300">{{ $item->quantity }}</td>
                        <td class="px-4 py-4 text-right text-white font-semibold">
                            {{ number_format($item->price * $item->quantity) }}₫
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tổng tiền --}}
    <div class="bg-gray-900 p-6 rounded-xl shadow-lg mb-6">
        <div class="flex justify-end max-w-md ml-auto space-y-3">
            <div class="w-full flex justify-between text-gray-300">
                <span>Tạm tính:</span>
                <span>{{ number_format($order->subtotal ?? $order->total_amount) }}₫</span>
            </div>

            @if ($order->tax_amount)
            <div class="w-full flex justify-between text-gray-300">
                <span>Thuế:</span>
                <span>{{ number_format($order->tax_amount) }}₫</span>
            </div>
            @endif

            @if ($order->shipping_cost)
            <div class="w-full flex justify-between text-gray-300">
                <span>Phí vận chuyển:</span>
                <span>{{ number_format($order->shipping_cost) }}₫</span>
            </div>
            @endif

            @if ($order->discount_amount)
            <div class="w-full flex justify-between text-green-400">
                <span>Giảm giá:</span>
                <span>-{{ number_format($order->discount_amount) }}₫</span>
            </div>
            @endif

            <div class="w-full flex justify-between border-t border-gray-700 pt-3">
                <span class="font-semibold text-white">Tổng cộng:</span>
                <span class="font-semibold text-cyan-400 text-lg">{{ number_format($order->total_amount) }}₫</span>
            </div>
        </div>
    </div>

    {{-- Trạng thái --}}
    <div class="grid grid-cols-2 gap-6 mb-6">
        {{-- Trạng thái đơn hàng --}}
        <div class="bg-gray-900 p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-cyan-400 mb-4">Trạng thái đơn hàng</h3>

            @php
            $statusLabels = [
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'shipping' => 'Đang giao hàng',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã huỷ',
                'refunded' => 'Đã hoàn tiền'
            ];
            @endphp

            <div class="mb-4">
                <p class="text-gray-400 text-sm mb-2">Trạng thái hiện tại:</p>
                <p class="text-2xl font-bold text-white">
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </p>
            </div>

            <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')

                <select name="status"
                        class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                    <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>

                <button type="submit"
                        class="w-full bg-cyan-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg font-semibold">
                    Cập nhật trạng thái
                </button>
            </form>
        </div>

        {{-- Trạng thái thanh toán --}}
        <div class="bg-gray-900 p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-cyan-400 mb-4">Trạng thái thanh toán</h3>

            @php
            $paymentLabels = [
                'unpaid' => 'Chưa thanh toán',
                'paid' => 'Đã thanh toán',
                'refunded' => 'Đã hoàn tiền'
            ];
            @endphp

            <div class="mb-4">
                <p class="text-gray-400 text-sm mb-2">Trạng thái hiện tại:</p>
                <p class="text-2xl font-bold text-white">
                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                </p>
            </div>

            <form action="{{ route('orders.updatePaymentStatus', $order) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')

                <select name="payment_status"
                        class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="">-- Chọn trạng thái thanh toán --</option>
                    <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>

                <button type="submit"
                        class="w-full bg-cyan-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg font-semibold">
                    Cập nhật thanh toán
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
