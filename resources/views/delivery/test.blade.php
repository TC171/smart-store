@extends('layouts.delivery')

@section('content')

<h3>🚚 Test Delivery View</h3>

<div class="alert alert-warning">
    Auth ID: {{ auth()->id() }} <br>
    Auth Guard: {{ auth('delivery')->check() ? 'delivery-authenticated' : 'not-authenticated' }}
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<h4>Test 0: Simple POST to /delivery/test-post (NEW TEST)</h4>

<form method="POST" action="{{ route('delivery.test-post') }}">
    @csrf
    <button type="submit" class="btn btn-warning">
        🔥 TEST 0: POST to /delivery/test-post
    </button>
</form>

<hr>

<h4>Test 1: Form - Method POST to /delivery/orders/30/pickup</h4>

<form method="POST" action="/delivery/orders/30/pickup">
    @csrf
    <button type="submit" class="btn btn-success">
        TEST 1: Submit Pickup Form (Direct Path)
    </button>
</form>

<hr>

@php
    // Simulate order data
    $order = new stdClass();
    $order->id = 30;
    $order->delivery_status = 'assigned';
    $order->order_number = 'TEST-001';
    $order->delivery_user_id = auth()->id();
@endphp

<h4>Test 2: Conditional Button with route() helper</h4>

@if($order->delivery_status === 'assigned')
    <div class="alert alert-info">✓ Condition TRUE: delivery_status === 'assigned'</div>
    <form method="POST" action="{{ route('delivery.orders.pickup', $order->id) }}">
        @csrf
        <button type="submit" class="btn btn-primary">
            📦 TEST 2: Nhận hàng (Route Helper)
        </button>
    </form>
@else
    <div class="alert alert-danger">✗ Condition FALSE: delivery_status = {{ $order->delivery_status }}</div>
@endif

@endsection
