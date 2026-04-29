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
                    <div>
                        <span class="text-gray-400">Phương thức thanh toán:</span>
                        <span class="ml-2 font-bold {{ $order->payment_method === 'vnpay' ? 'text-blue-400' : 'text-orange-400' }}">
                            {{ $order->payment_method === 'vnpay' ? 'VNPay' : 'Thanh toán khi nhận hàng (COD)' }}
                        </span>
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
        <div class="flex flex-col items-end max-w-md ml-auto space-y-3">
            <div class="w-full flex justify-between text-gray-300">
                <span>Tạm tính:</span>
                <span class="font-medium">{{ number_format($order->subtotal ?? $order->total_amount) }}₫</span>
            </div>
            <div class="w-full flex justify-between text-gray-300">
                <span>Phí vận chuyển:</span>
                <span class="font-medium">
                    @if (($order->shipping_cost ?? $order->shipping_fee) > 0)
                        {{ number_format($order->shipping_cost ?? $order->shipping_fee) }}₫
                    @else
                        <span class="text-green-400 font-bold">Miễn phí</span>
                    @endif
                </span>
            </div>
            @if ($order->discount_amount > 0)
            <div class="w-full flex flex-col items-end text-green-400 text-sm">
                <div class="w-full flex justify-between">
                    <span>
                        Mã giảm giá:
                        @if($order->coupon_code)
                            <code class="bg-gray-800 px-1 rounded text-green-400">{{ $order->coupon_code }}</code>
                        @else
                            <span class="italic text-gray-500">(Áp dụng)</span>
                        @endif
                    </span>
                    <span>-{{ number_format($order->discount_amount) }}₫</span>
                </div>
            </div>
            @endif
            <div class="w-full flex justify-between border-t border-gray-700 pt-3 mt-2">
                <span class="font-bold text-white text-lg">Tổng cộng:</span>
                <span class="font-bold text-cyan-400 text-2xl">
                    {{ number_format($order->grand_total ?? ($order->subtotal ?? $order->total_amount) + ($order->shipping_cost ?? $order->shipping_fee ?? 0) - $order->discount_amount) }}₫
                </span>
            </div>
        </div>
    </div>

    {{-- Trạng thái --}}
    <div class="grid grid-cols-1 gap-6 mb-6">
        <div class="bg-gray-900 p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-cyan-400 mb-4">Cập nhật trạng thái đơn hàng</h3>

            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Trạng thái đơn hàng</label>
                        @php
                            $allowedNext = [
                                'pending'         => ['confirmed'],
                                'confirmed'       => ['shipping', 'cancelled'],
                                'shipping'        => ['completed', 'failed_delivery'],
                                'picked_up'       => ['completed', 'failed_delivery'],
                                'completed'       => ['refunded'],
                                'failed_delivery' => [],
                                'cancelled'       => [],
                                'refunded'        => [],
                            ][$order->status] ?? [];
                        @endphp
                        <select name="status"
                                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500 {{ empty($allowedNext) ? 'opacity-70 cursor-not-allowed' : '' }}"
                                {{ empty($allowedNext) ? 'disabled' : '' }}>
                            <option value="pending"         {{ $order->status === 'pending'         ? 'selected' : '' }} {{ !in_array('pending',         $allowedNext) && $order->status !== 'pending'         ? 'disabled' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed"       {{ $order->status === 'confirmed'       ? 'selected' : '' }} {{ !in_array('confirmed',       $allowedNext) && $order->status !== 'confirmed'       ? 'disabled' : '' }}>Đã xác nhận</option>
                            <option value="shipping"        {{ $order->status === 'shipping'        ? 'selected' : '' }} {{ !in_array('shipping',        $allowedNext) && $order->status !== 'shipping'        ? 'disabled' : '' }}>Đang giao hàng</option>
                            <option value="picked_up"       {{ $order->status === 'picked_up'       ? 'selected' : '' }} {{ !in_array('picked_up',       $allowedNext) && $order->status !== 'picked_up'       ? 'disabled' : '' }}>Shipper đã nhận hàng</option>
                            <option value="failed_delivery" {{ $order->status === 'failed_delivery' ? 'selected' : '' }} {{ !in_array('failed_delivery', $allowedNext) && $order->status !== 'failed_delivery' ? 'disabled' : '' }}>Giao không thành công</option>
                            <option value="completed"       {{ $order->status === 'completed'       ? 'selected' : '' }} {{ !in_array('completed',       $allowedNext) && $order->status !== 'completed'       ? 'disabled' : '' }}>Hoàn thành</option>
                            <option value="refunded"        {{ $order->status === 'refunded'        ? 'selected' : '' }} {{ !in_array('refunded',        $allowedNext) && $order->status !== 'refunded'        ? 'disabled' : '' }}>Đã hoàn hàng</option>
                            <option value="cancelled"       {{ $order->status === 'cancelled'       ? 'selected' : '' }} {{ !in_array('cancelled',       $allowedNext) && $order->status !== 'cancelled'       ? 'disabled' : '' }}>Đã huỷ</option>
                        </select>
                        @if(empty($allowedNext))
                            <input type="hidden" name="status" value="{{ $order->status }}">
                            <p class="text-xs text-yellow-400 mt-1">* Đơn hàng đã ở trạng thái cuối cùng, không thể thay đổi.</p>
                        @else
                            <p class="text-xs text-cyan-400 mt-1">* Chỉ các trạng thái hợp lệ mới có thể chọn.</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Trạng thái thanh toán</label>
                        <select name="payment_status"
                                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500">
                            <option value="">-- Chọn trạng thái thanh toán --</option>
                            <option value="unpaid"   {{ $order->payment_status === 'unpaid'   ? 'selected' : '' }} {{ in_array($order->payment_status, ['paid', 'refunded']) ? 'disabled' : '' }}>Chưa thanh toán</option>
                            <option value="paid"     {{ $order->payment_status === 'paid'     ? 'selected' : '' }}
                                {{ ($order->payment_status === 'refunded') || ($order->payment_method === 'cod' && $order->status !== 'completed' && $order->payment_status !== 'paid') ? 'disabled' : '' }}>
                                Đã thanh toán
                            </option>
                            @php
                                $canRefundUI = ($order->status === 'refunded') || ($order->payment_method === 'vnpay' && in_array($order->status, ['failed_delivery', 'cancelled']));
                            @endphp
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}
                                {{ ($order->payment_status !== 'refunded' && !$canRefundUI) || ($order->payment_status !== 'paid' && $order->payment_status !== 'refunded') ? 'disabled' : '' }}>
                                Đã hoàn tiền
                            </option>
                        </select>
                    </div>
                </div>

                <button type="submit"
                        class="w-full md:w-auto bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-3 rounded-lg font-semibold transition">
                    Cập nhật đơn hàng
                </button>
            </form>

            {{-- Yêu cầu hoàn hàng --}}
            @if($order->refundRequests && $order->refundRequests->isNotEmpty())
            <div class="mt-6 border-t border-gray-700 pt-6">
                <h3 class="text-lg font-semibold text-orange-400 mb-4">Quản lý Yêu cầu</h3>
                <div class="space-y-3">
                    @foreach($order->refundRequests as $refund)
                    <div class="bg-gray-800 p-4 rounded-lg flex items-center justify-between border border-gray-700">
                        <div>
                            <p class="text-white font-medium">Yêu cầu #{{ $refund->id }}</p>
                            @php
                                $rfMap = [
                                    'pending'         => 'Đang chờ duyệt',
                                    'approved_return' => 'Khách đang trả hàng',
                                    'refunded'        => 'Đã hoàn hàng',
                                    'rejected'        => 'Đã từ chối',
                                ];
                                $rfColors = [
                                    'pending'         => 'text-yellow-400',
                                    'approved_return' => 'text-blue-400',
                                    'refunded'        => 'text-green-400',
                                    'rejected'        => 'text-red-400',
                                ];
                            @endphp
                            <p class="text-sm {{ $rfColors[$refund->status] ?? 'text-gray-400' }}">
                                Trạng thái: {{ $rfMap[$refund->status] ?? $refund->status }}
                            </p>
                        </div>
                        <a href="{{ route('admin.refunds.show', $refund) }}"
                           class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-md whitespace-nowrap">
                            Xem / Xử lý yêu cầu
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Thông tin Shipper --}}
            <div class="mt-6 border-t border-gray-700 pt-6">
                <h3 class="text-lg font-semibold text-cyan-400 mb-4">🚴 Thông tin Shipper</h3>

                @if($order->shipper)
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                    <div class="flex items-center justify-between flex-wrap gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($order->shipper->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-white font-semibold">{{ $order->shipper->name }}</div>
                                <div class="text-gray-400 text-sm">{{ $order->shipper->phone ?? '–' }}</div>
                            </div>
                        </div>
                        @php
                            $shipColors = [
                                'shipping'        => 'bg-indigo-500/20 text-indigo-400',
                                'picked_up'       => 'bg-cyan-500/20 text-cyan-400',
                                'completed'       => 'bg-green-500/20 text-green-400',
                                'failed_delivery' => 'bg-red-500/20 text-red-400',
                            ];
                            $shipLabels = [
                                'shipping'        => '📋 Chờ nhận hàng',
                                'picked_up'       => '🚴 Đang giao hàng',
                                'completed'       => '✅ Giao thành công',
                                'failed_delivery' => '❌ Giao thất bại',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $shipColors[$order->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                            {{ $shipLabels[$order->status] ?? $order->status }}
                        </span>
                    </div>
                    @if($order->status === 'completed' && $order->completed_at)
                    <div class="text-green-400 text-sm">✅ Giao xong lúc: {{ $order->completed_at->format('H:i, d/m/Y') }}</div>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('admin.shippers.deliveries', ['search' => $order->order_number]) }}"
                           class="text-cyan-400 hover:text-cyan-300 text-sm transition">
                            Xem trong trang theo dõi →
                        </a>
                    </div>
                </div>

                @elseif($order->status === 'confirmed')
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-5">
                    <p class="text-yellow-400 text-sm mb-4">⚠️ Đơn hàng đã xác nhận nhưng chưa được phân công shipper.</p>
                    <a href="{{ route('admin.shippers.assign', ['search' => $order->order_number]) }}"
                       class="inline-block bg-cyan-500 hover:bg-cyan-600 text-white px-5 py-2 rounded-lg text-sm font-semibold transition">
                        📦 Phân công Shipper ngay
                    </a>
                </div>

                @else
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-4">
                    <p class="text-gray-400 text-sm">Đơn hàng này chưa được phân công shipper.</p>
                </div>
                @endif
            </div>

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