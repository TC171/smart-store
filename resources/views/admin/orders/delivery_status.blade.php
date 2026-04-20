{{--
    ============================================================
    PARTIAL: Thêm vào trang admin/orders/show.blade.php
    Vị trí: Bên trong card chi tiết đơn hàng, sau phần thông tin
            trạng thái hiện tại.

    Cách dùng: @include('admin.orders._delivery_status', ['order' => $order])
    ============================================================
--}}

<div class="card mt-3">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0">🚚 Trạng thái giao hàng</h6>
        @if($order->deliveryStaff)
            <span class="text-muted small">
                Shipper: <strong>{{ $order->deliveryStaff->name }}</strong>
                ({{ $order->deliveryStaff->phone ?? $order->deliveryStaff->email }})
            </span>
        @else
            <span class="text-muted small">Chưa gán shipper</span>
        @endif
    </div>

    <div class="card-body">
        {{-- Timeline delivery_status --}}
        @php
            $steps = [
                'assigned'   => ['icon' => '📥', 'label' => 'Đã gán shipper'],
                'picked_up'  => ['icon' => '📦', 'label' => 'Shipper đã lấy hàng'],
                'delivering' => ['icon' => '🚚', 'label' => 'Đang giao hàng'],
                'delivered'  => ['icon' => '✅', 'label' => 'Giao thành công'],
                'failed'     => ['icon' => '❌', 'label' => 'Giao thất bại'],
                'returned'   => ['icon' => '↩️', 'label' => 'Đã trả về kho'],
            ];
            $current = $order->delivery_status;
            $normalFlow = ['assigned', 'picked_up', 'delivering', 'delivered'];
            $isNormal = in_array($current, $normalFlow);
        @endphp

        @if($current)
            {{-- Timeline bình thường --}}
            @if($isNormal)
            <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                @foreach($normalFlow as $step)
                    @php
                        $stepIndex = array_search($step, $normalFlow);
                        $currentIndex = array_search($current, $normalFlow);
                        $done = $stepIndex <= $currentIndex;
                    @endphp
                    <span class="badge {{ $done ? 'bg-success' : 'bg-light text-muted border' }} py-2 px-3">
                        {{ $steps[$step]['icon'] }} {{ $steps[$step]['label'] }}
                    </span>
                    @if(!$loop->last)
                        <span class="text-muted">→</span>
                    @endif
                @endforeach
            </div>
            @endif

            {{-- Badge trạng thái hiện tại --}}
            <span class="badge bg-{{ $order->delivery_status_color }} fs-6">
                {{ $steps[$current]['icon'] ?? '' }} {{ $order->delivery_status_label }}
            </span>

            @if(in_array($current, ['failed', 'returned']))
                <div class="alert alert-warning mt-2 mb-0 py-2">
                    <small>
                        ⚠️ Đơn hàng giao không thành công.
                        Vui lòng liên hệ shipper hoặc xử lý thủ công.
                    </small>
                </div>
            @endif
        @else
            <span class="text-muted">Chưa bắt đầu quy trình giao hàng.</span>
        @endif

        {{-- Form gán / đổi shipper --}}
        <hr>
        <form method="POST" action="{{ route('admin.orders.assignShipper', $order) }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-auto flex-grow-1">
                <label class="form-label small mb-1">Gán / Đổi shipper</label>
                <select name="delivery_user_id" class="form-select form-select-sm">
                    <option value="">— Chọn shipper —</option>
                    @foreach(\App\Models\User::where('role', 'shipper')->get() as $shipper)
                        <option value="{{ $shipper->id }}"
                            {{ $order->delivery_user_id == $shipper->id ? 'selected' : '' }}>
                            {{ $shipper->name }} ({{ $shipper->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Gán shipper 🚚</button>
            </div>
        </form>
    </div>
</div>