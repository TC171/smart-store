@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">
            Chi tiết đơn hàng #{{ $order->order_number }}
        </h1>
        <a href="{{ route('admin.orders.index') }}"
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
                        <span class="ml-2">{{ $order->email ?? $order->user->email }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Điện thoại:</span>
                        <span class="ml-2">{{ $order->shipping_phone ?? $order->user->phone ?? 'N/A' }}</span>
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
    
    @if($order->status != 'completed')

<form method="POST"
      action="{{ route('admin.orders.assignShipper', $order) }}"
      style="margin-top:20px;">

    @csrf

    <label><b>Chọn nhân viên giao hàng:</b></label>

    <select name="delivery_user_id" required>

        <option value="">
            -- Chọn shipper --
        </option>

        @foreach($shippers as $shipper)

            <option value="{{ $shipper->id }}"
                {{ $order->delivery_user_id == $shipper->id ? 'selected' : '' }}>

                {{ $shipper->name }}
            </option>

        @endforeach

    </select>

    <button type="submit">
        🚚 Gán shipper
    </button>

</form>

@endif


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
                            <div class="space-y-1">
                            <div>{{ $item->product_name ?? 'N/A' }}</div>
                            @if ($item->variant)
                            <div class="text-gray-500 text-sm">
                                @if($item->variant->color)Màu sắc: {{ $item->variant->color }}@endif
                                @if($item->variant->storage) | Dung lượng: {{ $item->variant->storage }}@endif
                                @if($item->variant->ram) | RAM: {{ $item->variant->ram }}@endif
                            </div>
                            @endif
                        </div>
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
    <div class="grid grid-cols-1 gap-6 mb-6">
        <div class="bg-gray-900 p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-cyan-400 mb-4">📦 Trạng thái giao hàng</h3>

            {{-- Trạng thái delivery từ shipper --}}
            @if($order->delivery_status)
                @php
                    $deliveryStatusLabels = [
                        'assigned' => 'Đơn mới nhận',
                        'picked_up' => 'Đã nhận hàng',
                        'delivering' => 'Đang giao',
                        'delivered' => 'Đã giao',
                        'failed' => 'Giao thất bại',
                        'returned' => 'Đã trả về',
                    ];
                    $deliveryStatusColors = [
                        'assigned' => 'bg-info',
                        'picked_up' => 'bg-primary',
                        'delivering' => 'bg-warning',
                        'delivered' => 'bg-success',
                        'failed' => 'bg-danger',
                        'returned' => 'bg-secondary',
                    ];
                @endphp
                <div class="mb-4 p-4 bg-gray-800 rounded-lg border border-gray-700">
                    <p class="text-gray-300 mb-2">
                        <span class="font-medium">Nhân viên giao hàng:</span>
                        {{ $order->deliveryStaff?->name ?? 'Chưa gán' }}
                    </p>
                    <p class="text-gray-300">
                        <span class="font-medium">Trạng thái:</span>
                        <span class="ml-2 inline-block px-3 py-1 rounded-full text-white text-sm font-semibold 
                            @switch($order->delivery_status)
                                @case('assigned')
                                    bg-blue-500
                                    @break
                                @case('picked_up')
                                    bg-blue-600
                                    @break
                                @case('delivering')
                                    bg-amber-500
                                    @break
                                @case('delivered')
                                    bg-green-500
                                    @break
                                @case('failed')
                                    bg-red-500
                                    @break
                                @case('returned')
                                    bg-gray-500
                                    @break
                                @default
                                    bg-gray-400
                            @endswitch
                        ">
                            {{ $deliveryStatusLabels[$order->delivery_status] ?? $order->delivery_status }}
                        </span>
                    </p>
                </div>
            @endif

            <h3 class="text-lg font-semibold text-cyan-400 mb-4">Cập nhật trạng thái đơn hàng</h3>

            {{-- Gán shipper khi đơn đã xác nhận --}}
            @if($order->status === 'confirmed')

            <div class="mb-6 border-b border-gray-700 pb-6">

                <h3 class="text-lg font-semibold text-yellow-400 mb-4">
                    Gán nhân viên giao hàng 🚚
                </h3>

                <form method="POST"
                      action="{{ route('admin.orders.assignShipper', $order->id) }}"
                      class="space-y-4">

                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Chọn nhân viên giao hàng
                        </label>

                        <select name="delivery_user_id"
                                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-3"
                                required>

                            <option value="">
                                -- Chọn shipper --
                            </option>

                            @foreach($shippers as $shipper)

                                <option value="{{ $shipper->id }}">
                                    {{ $shipper->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-lg font-semibold">

                        Gán giao hàng

                    </button>

                </form>

            </div>

            @endif

            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Trạng thái đơn hàng</label>
                        <select name="status"
                                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500">
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                            <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Trạng thái thanh toán</label>
                        <select name="payment_status"
                                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500">
                            <option value="">-- Chọn trạng thái thanh toán --</option>
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                        </select>
                    </div>
                </div>

                <button type="submit"
                        class="w-full md:w-auto bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-3 rounded-lg font-semibold transition">
                    Cập nhật đơn hàng
                </button>
            </form>

            @if($order->note)
                <div class="mt-6 bg-gray-800 p-4 rounded-lg border border-gray-700">
                    <h4 class="text-sm font-semibold text-cyan-300 mb-2">Ghi chú / Yêu cầu</h4>
                    <p class="text-sm text-gray-300 whitespace-pre-line">{{ $order->note }}</p>
                </div>
            @endif
        </div>
    </div>

</div>

@endsection
