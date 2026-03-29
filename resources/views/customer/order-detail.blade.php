@extends('customer.layout')

@section('customer-content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Đơn hàng #{{ $order->order_number }}</h2>
                <p class="text-gray-600 mt-1">Đặt ngày {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="text-right">
                @php
                $statusLabels = [
                    'pending' => 'Chờ xác nhận',
                    'confirmed' => 'Đã xác nhận',
                    'shipping' => 'Đang giao hàng',
                    'completed' => 'Hoàn thành',
                    'cancelled' => 'Đã huỷ',
                    'refunded' => 'Đã hoàn tiền'
                ];
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'confirmed' => 'bg-blue-100 text-blue-800',
                    'shipping' => 'bg-indigo-100 text-indigo-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    'refunded' => 'bg-orange-100 text-orange-800'
                ];
                @endphp
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </span>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Sản phẩm đã đặt</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $item->variant->product->name ?? 'N/A' }}</h4>
                        <p class="text-sm text-gray-500">SKU: {{ $item->variant->sku }}</p>
                        @if($item->variant->color || $item->variant->storage)
                            <p class="text-sm text-gray-500">
                                @if($item->variant->color) Màu: {{ $item->variant->color }} @endif
                                @if($item->variant->storage) - Dung lượng: {{ $item->variant->storage }} @endif
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Số lượng: {{ $item->quantity }}</p>
                        <p class="font-medium text-gray-900">{{ number_format($item->price) }}₫</p>
                        <p class="text-sm font-medium text-gray-700">Thành tiền: {{ number_format($item->price * $item->quantity) }}₫</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="border-t pt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin giao hàng</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Người nhận:</span> {{ $order->shipping_name }}</p>
                        <p><span class="font-medium">Điện thoại:</span> {{ $order->shipping_phone }}</p>
                        <p><span class="font-medium">Địa chỉ:</span> {{ $order->shipping_address }}</p>
                        @if($order->shipping_city)
                            <p><span class="font-medium">Thành phố:</span> {{ $order->shipping_city }}</p>
                        @endif
                        @if($order->shipping_country)
                            <p><span class="font-medium">Quốc gia:</span> {{ $order->shipping_country }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tóm tắt đơn hàng</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($order->total_amount) }}₫</span>
                        </div>
                        @if($order->shipping_fee)
                        <div class="flex justify-between text-sm">
                            <span>Phí vận chuyển:</span>
                            <span>{{ number_format($order->shipping_fee) }}₫</span>
                        </div>
                        @endif
                        @if($order->discount_amount)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->discount_amount) }}₫</span>
                        </div>
                        @endif
                        @if($order->tax_amount)
                        <div class="flex justify-between text-sm">
                            <span>Thuế:</span>
                            <span>{{ number_format($order->tax_amount) }}₫</span>
                        </div>
                        @endif
                        <div class="border-t pt-2 flex justify-between font-semibold text-lg">
                            <span>Tổng cộng:</span>
                            <span class="text-blue-600">{{ number_format($order->grand_total ?? $order->total_amount) }}₫</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Trạng thái thanh toán:</p>
                        @php
                        $paymentLabels = [
                            'unpaid' => 'Chưa thanh toán',
                            'paid' => 'Đã thanh toán',
                            'refunded' => 'Đã hoàn tiền'
                        ];
                        $paymentColors = [
                            'unpaid' => 'bg-red-100 text-red-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'refunded' => 'bg-orange-100 text-orange-800'
                        ];
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($order->note)
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-medium text-gray-800 mb-2">Ghi chú:</h4>
            <p class="text-gray-600">{{ $order->note }}</p>
        </div>
        @endif
    </div>

    <div class="text-center">
        <a href="{{ route('customer.orders') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium inline-block mb-8">
            ← Quay lại danh sách đơn hàng
        </a>
    </div>

    @if(in_array($order->status, ['pending', 'confirmed']))
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog text-start">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-bold text-lg">Hủy đơn hàng #{{ $order->order_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label font-medium">Vui lòng chọn hoặc nhập lý do hủy đơn <span class="text-red-500">*</span></label>
                            <select class="form-select mb-2 mt-1 w-full border-gray-300 rounded-md shadow-sm" name="cancel_reason" required onchange="checkOtherReasonDetail(this)">
                                <option value="">-- Chọn lý do --</option>
                                <option value="Tôi muốn thay đổi địa chỉ giao hàng">Tôi muốn thay đổi địa chỉ giao hàng</option>
                                <option value="Tôi muốn thay đổi sản phẩm/số lượng">Tôi muốn thay đổi sản phẩm/số lượng</option>
                                <option value="Tôi tìm thấy giá rẻ hơn ở nơi khác">Tôi tìm thấy giá rẻ hơn ở nơi khác</option>
                                <option value="Tôi không có nhu cầu mua nữa">Tôi không có nhu cầu mua nữa</option>
                                <option value="other">Lý do khác...</option>
                            </select>
                            <input type="text" class="form-control d-none mt-2 w-full border-gray-300 rounded-md shadow-sm p-2 border" id="cancel_reason_text_detail" placeholder="Nhập lý do khác của bạn..." disabled>
                        </div>
                    </div>
                    <div class="modal-footer bg-gray-50 border-t px-4 py-3 flex justify-end gap-2">
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md font-medium" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium shadow-sm">Xác nhận Hủy Đơn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function checkOtherReasonDetail(select) {
            var inputList = document.getElementById('cancel_reason_text_detail');
            if (select.value === 'other') {
                inputList.classList.remove('d-none'); 
                inputList.disabled = false;
                inputList.name = 'cancel_reason'; 
                select.name = ''; 
            } else {
                inputList.classList.add('d-none'); 
                inputList.disabled = true;
                inputList.name = ''; 
                select.name = 'cancel_reason';
            }
        }
    </script>
    @endif
</div>
@endsection