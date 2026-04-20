{{--
    ============================================================
    PARTIAL: Tracking giao hàng dành cho trang khách hàng
    File: resources/views/frontend/customer/orders/_delivery_tracking.blade.php

    Cách dùng: @include('frontend.customer.orders._delivery_tracking', ['order' => $order])
    Hiển thị khi: $order->status === 'shipping' hoặc delivery_status có giá trị
    ============================================================
--}}

@if($order->delivery_status)
<div class="card mt-3 border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h6 class="mb-0 fw-semibold">🚚 Theo dõi giao hàng</h6>
    </div>
    <div class="card-body">

        @php
            $timeline = [
                ['key' => 'assigned',   'icon' => '📋', 'label' => 'Đơn hàng đã được giao cho shipper'],
                ['key' => 'picked_up',  'icon' => '📦', 'label' => 'Shipper đã lấy hàng từ kho'],
                ['key' => 'delivering', 'icon' => '🚚', 'label' => 'Đơn hàng đang trên đường giao đến bạn'],
                ['key' => 'delivered',  'icon' => '✅', 'label' => 'Đơn hàng đã được giao thành công'],
            ];
            $normalFlow  = ['assigned', 'picked_up', 'delivering', 'delivered'];
            $current     = $order->delivery_status;
            $currentIdx  = array_search($current, $normalFlow);
            $isFailed    = $current === 'failed';
            $isReturned  = $current === 'returned';
        @endphp

        {{-- Timeline bình thường --}}
        @if(!$isFailed && !$isReturned)
        <ol class="list-unstyled">
            @foreach($timeline as $i => $step)
                @php $done = ($currentIdx !== false) && $i <= $currentIdx; @endphp
                <li class="d-flex align-items-start gap-3 mb-3">
                    <div class="mt-1" style="font-size: 1.2rem; opacity: {{ $done ? '1' : '0.3' }}">
                        {{ $step['icon'] }}
                    </div>
                    <div>
                        <span class="{{ $done ? 'fw-semibold text-dark' : 'text-muted' }}">
                            {{ $step['label'] }}
                        </span>
                        @if($done && $current === $step['key'])
                            <span class="badge bg-success ms-2">Hiện tại</span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
        @endif

        {{-- Giao thất bại --}}
        @if($isFailed)
        <div class="alert alert-danger mb-0">
            <strong>❌ Giao hàng thất bại</strong><br>
            <small>
                Chúng tôi không thể giao đơn hàng này.
                Vui lòng liên hệ shop qua hotline hoặc email để được hỗ trợ.
            </small>
        </div>
        @endif

        {{-- Đã trả về kho --}}
        @if($isReturned)
        <div class="alert alert-secondary mb-0">
            <strong>↩️ Đơn hàng đã được trả về kho</strong><br>
            <small>
                Đơn hàng chưa thể giao tới bạn và đã được hoàn lại kho.
                Shop sẽ liên hệ bạn trong thời gian sớm nhất.
            </small>
        </div>
        @endif

        {{-- Thông tin shipper (chỉ hiện khi đang giao) --}}
        @if($order->deliveryStaff && in_array($current, ['picked_up', 'delivering']))
        <hr class="my-3">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                 style="width:40px;height:40px;font-size:1.2rem;">👤</div>
            <div>
                <div class="fw-semibold small">{{ $order->deliveryStaff->name }}</div>
                <div class="text-muted small">
                    {{ $order->deliveryStaff->phone ?? 'Nhân viên giao hàng' }}
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endif