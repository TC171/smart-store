@extends('layouts.delivery')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">🚚 Đơn hàng của bạn</h4>
    <span class="text-muted small">Shipper #{{ auth('delivery')->id() }}</span>
</div>

{{-- Tab lọc trạng thái --}}
<ul class="nav nav-tabs mb-3">
    @foreach([
        'assigned'   => ['label' => 'Mới nhận',    'icon' => '📥'],
        'picked_up'  => ['label' => 'Đã lấy hàng', 'icon' => '📦'],
        'delivering' => ['label' => 'Đang giao',   'icon' => '🚚'],
        'delivered'  => ['label' => 'Hoàn thành',  'icon' => '✅'],
        'failed'     => ['label' => 'Thất bại',    'icon' => '❌'],
        'returned'   => ['label' => 'Trả về',      'icon' => '↩️'],
    ] as $key => $info)
    <li class="nav-item">
        <a class="nav-link {{ $status === $key ? 'active fw-bold' : '' }}"
           href="{{ route('delivery.orders.index', ['status' => $key]) }}">
            {{ $info['icon'] }} {{ $info['label'] }}
        </a>
    </li>
    @endforeach
</ul>

@if($orders->isEmpty())
    <div class="alert alert-info">
        Không có đơn hàng nào ở trạng thái này.
    </div>
@else
    @foreach($orders as $order)
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">
                        Đơn <strong>#{{ $order->order_number }}</strong>
                    </h6>
                    <p class="mb-1 text-muted small">
                        {{ $order->shipping_name ?? $order->user->name ?? '—' }}
                        &nbsp;·&nbsp;
                        {{ $order->shipping_phone ?? '—' }}
                    </p>
                    <p class="mb-0 text-muted small">
                        📍 {{ $order->shipping_address }}, {{ $order->shipping_district }}, {{ $order->shipping_city }}
                    </p>
                </div>
                <span class="badge bg-{{ $order->delivery_status_color }} ms-2">
                    {{ $order->delivery_status_label }}
                </span>
            </div>

            <div class="mt-3 d-flex gap-2 flex-wrap">
                {{-- Nút hành động theo từng bước --}}
                @if($order->delivery_status === 'assigned')
                    <form method="POST" action="{{ route('delivery.orders.pickup', $order) }}">
                        @csrf
                        <button class="btn btn-sm btn-primary">📦 Xác nhận lấy hàng</button>
                    </form>

                @elseif($order->delivery_status === 'picked_up')
                    <form method="POST" action="{{ route('delivery.orders.delivering', $order) }}">
                        @csrf
                        <button class="btn btn-sm btn-warning">🚚 Bắt đầu giao</button>
                    </form>

                @elseif($order->delivery_status === 'delivering')
                    <form method="POST" action="{{ route('delivery.orders.done', $order) }}">
                        @csrf
                        <button class="btn btn-sm btn-success">✅ Đã giao thành công</button>
                    </form>
                    <form method="POST" action="{{ route('delivery.orders.fail', $order) }}">
                        @csrf
                        <button class="btn btn-sm btn-danger">❌ Giao thất bại</button>
                    </form>
                    <form method="POST" action="{{ route('delivery.orders.returned', $order) }}">
                        @csrf
                        <button class="btn btn-sm btn-secondary">↩️ Trả về kho</button>
                    </form>
                @endif

                <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                    📄 Chi tiết
                </a>
            </div>
        </div>
    </div>
    @endforeach
@endif

@endsection