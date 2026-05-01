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
    'pending'          => 'Chờ xác nhận',
    'confirmed'        => 'Đã xác nhận',
    'shipping'         => 'Đã nhận hàng',
    'picked_up'        => 'Đang giao hàng',
    'failed_delivery'  => 'Giao hàng không thành công',
    'completed'        => 'Hoàn thành',
    'cancelled'        => 'Đã huỷ',
    'refunded'         => 'Đã hoàn hàng',
];
$statusColors = [
    'pending'          => 'bg-yellow-100 text-yellow-800',
    'confirmed'        => 'bg-blue-100 text-blue-800',
    'shipping'         => 'bg-indigo-100 text-indigo-800',
    'picked_up'        => 'bg-cyan-100 text-cyan-800',
    'failed_delivery'  => 'bg-red-100 text-red-800',
    'completed'        => 'bg-green-100 text-green-800',
    'cancelled'        => 'bg-red-100 text-red-800',
    'refunded'         => 'bg-orange-100 text-orange-800',
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
                        @if($item->variant)
                            <p class="text-sm text-gray-500">
                                @if($item->variant->color)Màu sắc: {{ $item->variant->color }}@endif
                                @if($item->variant->storage) | Dung lượng: {{ $item->variant->storage }}@endif
                                @if($item->variant->ram) | RAM: {{ $item->variant->ram }}@endif
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

        {{-- Chỉ cho phép đánh giá nếu đơn hoàn thành và KHÔNG có yêu cầu hoàn hàng/hoàn tiền đang xử lý hoặc đã duyệt --}}
        @if($order->status === 'completed' && !$order->refundRequests->whereIn('status', ['pending', 'approved_return', 'refunded'])->count())
        <div class="mb-6 bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Đánh giá đơn hàng</h3>
            @php
                $pendingItems = $order->items->filter(function($item) use ($reviewCounts, $completedOrderCounts) {
                    $reviewed = $reviewCounts[$item->product_id] ?? 0;
                    $allowed = $completedOrderCounts[$item->product_id] ?? 0;
                    return $reviewed < $allowed;
                });
            @endphp

            @if($pendingItems->isEmpty())
                <p class="text-gray-600">Bạn đã gửi đánh giá cho tất cả sản phẩm trong đơn hàng này.</p>
            @else
                <p class="text-gray-600 mb-4">Chọn sản phẩm và gửi đánh giá để admin duyệt.</p>

                @foreach($pendingItems as $item)
                    @php
                        $reviewed = $reviewCounts[$item->product_id] ?? 0;
                        $allowed = $completedOrderCounts[$item->product_id] ?? 0;
                        $remaining = $allowed - $reviewed;
                    @endphp
                    <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                        <h4 class="font-semibold">{{ $item->variant->product->name ?? 'Sản phẩm' }}</h4>
                        <p class="text-sm text-gray-500 mt-1">Còn {{ $remaining }} lượt đánh giá (đã dùng {{ $reviewed }}/{{ $allowed }})</p>
                        <form action="{{ route('customer.orders.reviews.store', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Đánh giá</label>
                                <select name="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Chọn số sao</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} sao</option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tiêu đề</label>
                                <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Tiêu đề đánh giá (tùy chọn)">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nhận xét</label>
                                <textarea name="comment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Viết cảm nhận của bạn..."></textarea>
                                @error('comment')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ảnh đánh giá <span class="text-gray-400 text-xs">(Tùy chọn, tối đa 5 ảnh)</span></label>
                                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                                       class="mt-1 block w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                <p class="text-xs text-gray-400 mt-1">Định dạng: JPG, PNG, WebP — Tối đa 2MB mỗi ảnh</p>
                                @error('images')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                @error('images.*')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Gửi đánh giá</button>
                        </form>
                    </div>
                @endforeach
            @endif
        </div>
        @endif

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
                        <div class="flex justify-between text-sm">
                            <span>Phí vận chuyển:</span>
                            @if($order->shipping_fee > 0)
                                <span>{{ number_format($order->shipping_fee) }}₫</span>
                            @else
                                <span class="text-green-600 font-bold">Miễn phí</span>
                            @endif
                        </div>
                        @if($order->discount_amount)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->discount_amount) }}₫</span>
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

    @if($order->status === 'cancelled')
<div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
    <h4 class="font-medium text-red-700 mb-1">Lý do hủy đơn:</h4>
    @if($order->cancellation_reason)
        <p class="text-red-600 text-sm whitespace-pre-line">{{ $order->cancellation_reason }}</p>
    @else
        <p class="text-gray-400 text-sm italic">Không có lý do được ghi nhận.</p>
    @endif
</div>
@endif


    {{-- ===== HOÀN HÀNG ===== --}}
    @if($order->status === 'completed')
        @php
            $pendingRefund   = $order->refundRequests->whereIn('status', ['pending'])->first();
            $approvedRefund  = $order->refundRequests->whereIn('status', ['approved_return'])->first();
            $completedRefund = $order->refundRequests->where('status', 'refunded')->first();
            $rejectedRefund  = $order->refundRequests->where('status', 'rejected')->first();
        @endphp

        @if($pendingRefund)
        {{-- Đang chờ duyệt --}}
        <div class="mt-6 p-5 bg-yellow-50 border border-yellow-200 rounded-xl flex items-start gap-4">
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="font-bold text-yellow-800">Yêu cầu hoàn hàng đang chờ xét duyệt</p>
                <p class="text-sm text-yellow-700 mt-1">Loại: <strong>{{ $pendingRefund->type_label }}</strong> — Gửi lúc {{ $pendingRefund->created_at->format('H:i d/m/Y') }}</p>
                <p class="text-xs text-yellow-600 mt-1">Lý do: {{ \Illuminate\Support\Str::limit($pendingRefund->reason, 100) }}</p>
            </div>
        </div>

        @elseif($approvedRefund)
        {{-- Đã duyệt - chờ gửi hàng --}}
        <div class="mt-6 p-5 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-4">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="font-bold text-blue-800">Yêu cầu hoàn hàng đã được duyệt!</p>
                <p class="text-sm text-blue-700 mt-1">Vui lòng gửi hàng với mã: <span class="font-mono font-bold bg-blue-100 px-2 py-0.5 rounded text-blue-900">{{ $approvedRefund->return_code }}</span></p>
                @if($approvedRefund->admin_note)
                <p class="text-xs text-blue-600 mt-1">Ghi chú: {{ $approvedRefund->admin_note }}</p>
                @endif
            </div>
        </div>

        @elseif($completedRefund)
        {{-- Đã hoàn hàng --}}
        <div class="mt-6 p-5 bg-green-50 border border-green-200 rounded-xl flex items-start gap-4">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="font-bold text-green-800">✅ Đã hoàn hàng thành công</p>
                <p class="text-sm text-green-700 mt-1">Yêu cầu hoàn hàng của bạn đã được xử lý xong.</p>
            </div>
        </div>

        @elseif($rejectedRefund)
        {{-- Bị từ chối - có thể gửi lại --}}
        <div class="mt-6 p-5 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p class="font-bold text-red-800">Yêu cầu hoàn hàng đã bị từ chối</p>
                    @if($rejectedRefund->admin_note)
                    <p class="text-sm text-red-700 mt-1"><span class="font-semibold text-red-800">Lý do từ Admin:</span> {{ $rejectedRefund->admin_note }}</p>
                    @else
                    <p class="text-sm text-red-700 mt-1">Vui lòng liên hệ hỗ trợ để biết thêm chi tiết.</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('customer.orders.refund.create', $order) }}"
               class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-5 rounded-lg text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Gửi yêu cầu mới
            </a>
        </div>

        @else
        {{-- Chưa có yêu cầu nào — hiện nút hoàn hàng --}}
        <div class="mt-6 p-5 bg-orange-50 border border-orange-200 rounded-xl flex items-center justify-between gap-4">
            <div>
                <p class="font-bold text-orange-800">Có vấn đề với đơn hàng?</p>
                <p class="text-sm text-orange-700 mt-1">Bạn có thể yêu cầu hoàn hàng nếu sản phẩm không đúng mô tả.</p>
            </div>
            <a href="{{ route('customer.orders.refund.create', $order) }}"
               class="flex-shrink-0 inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl text-sm transition-colors shadow-md shadow-orange-200 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Yêu cầu hoàn hàng
            </a>
        </div>
        @endif
    @endif
    {{-- ===== /HOÀN HÀNG ===== --}}

        <div class="flex justify-center flex-wrap gap-4 mt-6 mb-8 text-center">
        <a href="{{ route('customer.orders') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium inline-block shadow-sm">
            ← Quay lại danh sách đơn hàng
        </a>

        @if(in_array($order->status, ['shipping', 'picked_up']) && $order->shipper_id)
            <a href="{{ route('customer.orders.track', $order) }}"
               class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium inline-block shadow-sm shadow-orange-200 transition">
                🗺️ Theo dõi đơn hàng
            </a>
        @endif

    @if(!in_array($order->status, ['completed', 'cancelled', 'refunded', 'shipping', 'picked_up', 'failed_delivery']))
        <button type="button" onclick="openCancelModal()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium inline-block transition-colors shadow-sm shadow-red-200">
            Hủy đơn hàng
        </button>
    @endif
    </div>

    </div>

    @if(!in_array($order->status, ['completed', 'cancelled', 'refunded', 'shipping', 'failed_delivery']))
    {{-- Pure Tailwind Modal --}}
    <div id="cancelOrderModal" class="fixed inset-0 z-[9999] hidden bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0" onclick="closeCancelModalOnBackdrop(event)">
                <div id="cancelModalContent" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg scale-95 opacity-0 duration-300">
                    <div class="bg-gray-50/80 border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Hủy đơn hàng #{{ $order->order_number }}</h3>
                        <button type="button" onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-500 hover:bg-gray-200 rounded-full p-1.5 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST">
                        @csrf
                        <div class="bg-white px-6 py-5">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Vui lòng chọn hoặc nhập lý do hủy đơn <span class="text-red-500">*</span></label>
                            <select class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 bg-gray-50 text-gray-700 transition" name="cancel_reason" required onchange="checkOtherReasonDetail(this)">
                                <option value="">-- Chọn lý do --</option>
                                <option value="Tôi muốn thay đổi địa chỉ giao hàng">Tôi muốn thay đổi địa chỉ giao hàng</option>
                                <option value="Tôi muốn thay đổi sản phẩm/số lượng">Tôi muốn thay đổi sản phẩm/số lượng</option>
                                <option value="Tôi tìm thấy giá rẻ hơn ở nơi khác">Tôi tìm thấy giá rẻ hơn ở nơi khác</option>
                                <option value="Tôi không có nhu cầu mua nữa">Tôi không có nhu cầu mua nữa</option>
                                <option value="other">Lý do khác...</option>
                            </select>
                            
                            <input type="text" class="hidden mt-3 w-full border border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 bg-gray-50 p-2.5 text-gray-700 transition" id="cancel_reason_text_detail" placeholder="Nhập lý do khác của bạn..." disabled>
                        </div>
                        
                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl border-t border-gray-100">
                            <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-red-700 sm:w-auto transition-colors focus:ring-4 focus:ring-red-100">
                                {{ $order->status === 'pending' ? 'Xác nhận Hủy Đơn' : 'Gửi Yêu Cầu Hủy Đơn' }}
                            </button>
                            <button type="button" onclick="closeCancelModal()" class="inline-flex w-full justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto transition-colors">
                                Đóng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCancelModal() {
            var m = document.getElementById('cancelOrderModal');
            var mc = document.getElementById('cancelModalContent');
            if(m && mc) {
                m.classList.remove('hidden');
                // Trigger reflow
                void m.offsetWidth;
                
                m.classList.remove('opacity-0');
                mc.classList.remove('scale-95', 'opacity-0');
                mc.classList.add('scale-100', 'opacity-100');
            }
        }

        function closeCancelModal() {
            var m = document.getElementById('cancelOrderModal');
            var mc = document.getElementById('cancelModalContent');
            if(m && mc) {
                m.classList.add('opacity-0');
                mc.classList.remove('scale-100', 'opacity-100');
                mc.classList.add('scale-95', 'opacity-0');
                
                setTimeout(() => {
                    m.classList.add('hidden');
                }, 300);
            }
        }

        function closeCancelModalOnBackdrop(e) {
            if (e.target === e.currentTarget) {
                closeCancelModal();
            }
        }

        function checkOtherReasonDetail(select) {
            var inputList = document.getElementById('cancel_reason_text_detail');
            if (select.value === 'other') {
                inputList.classList.remove('hidden'); 
                inputList.classList.add('block');
                inputList.disabled = false;
                inputList.name = 'cancel_reason'; 
                select.name = ''; 
            } else {
                inputList.classList.remove('block'); 
                inputList.classList.add('hidden'); 
                inputList.disabled = true;
                inputList.name = ''; 
                select.name = 'cancel_reason';
            }
        }
    </script>
    @endif
</div>
@endsection