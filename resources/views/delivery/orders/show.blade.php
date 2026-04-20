@extends('layouts.delivery')

@section('content')

<h3>
📄 Chi tiết đơn #{{ $order->order_number }}
</h3>

<div class="card mt-3 shadow-sm">

<div class="card-body">

{{-- Thông tin khách --}}
<h5>👤 Thông tin khách hàng</h5>

<p>
<b>Tên:</b>
{{ $order->user->name ?? $order->shipping_name }}
</p>

<p>
<b>Email:</b>
{{ $order->email }}
</p>

<p>
<b>Điện thoại:</b>
{{ $order->shipping_phone }}
</p>

<p>
<b>Địa chỉ:</b>
{{ $order->shipping_address }}, {{ $order->shipping_district }}, {{ $order->shipping_city }}
</p>

<hr>

{{-- Trạng thái --}}
<h5>📦 Trạng thái giao hàng</h5>

@if($order->delivery_status == 'assigned')

<span class="badge bg-info">
📥 Đơn mới nhận
</span>

@elseif($order->delivery_status == 'picked_up')

<span class="badge bg-primary">
📦 Đã nhận hàng
</span>

@elseif($order->delivery_status == 'delivering')

<span class="badge bg-warning">
🚚 Đang giao
</span>

@elseif($order->delivery_status == 'delivered')

<span class="badge bg-success">
✅ Đã giao
</span>

@elseif($order->delivery_status == 'failed')

<span class="badge bg-danger">
❌ Giao thất bại
</span>

@elseif($order->delivery_status == 'returned')

<span class="badge bg-secondary">
↩️ Đã trả về
</span>

@endif

<hr>

{{-- Form cập nhật trạng thái --}}
@if($order->delivery_status == 'assigned')
<form method="POST" action="{{ route('delivery.orders.pickup', $order) }}">
@csrf
<button class="btn btn-primary w-100 mb-2">
📦 Nhận hàng
</button>
</form>

@elseif($order->delivery_status == 'picked_up')
<form method="POST" action="{{ route('delivery.orders.delivering', $order) }}">
@csrf
<button class="btn btn-warning w-100 mb-2">
🚚 Bắt đầu giao
</button>
</form>

@elseif($order->delivery_status == 'delivering')
<div class="d-flex gap-2">
<form method="POST" action="{{ route('delivery.orders.done', $order) }}" class="flex-fill">
@csrf
<button class="btn btn-success w-100">
✅ Hoàn thành
</button>
</form>

<form method="POST" action="{{ route('delivery.orders.fail', $order) }}" class="flex-fill">
@csrf
<button class="btn btn-danger w-100">
❌ Thất bại
</button>
</form>

<form method="POST" action="{{ route('delivery.orders.returned', $order) }}" class="flex-fill">
@csrf
<button class="btn btn-secondary w-100">
↩️ Trả về
</button>
</form>
</div>

@endif

<br>

<a href="{{ route('delivery.orders.index') }}"
   class="btn btn-secondary w-100">

⬅ Quay lại danh sách

</a>

</div>

</div>

@endsection