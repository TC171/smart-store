@extends('frontend.layouts.app')

@section('content')

<h2>Giỏ hàng</h2>

@php
    $cart = session('cart', []);
    $total = 0;
@endphp

@if(count($cart) > 0)

    @foreach($cart as $id => $item)

        @php
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        @endphp

        <div style="border:1px solid #ccc; margin:10px; padding:10px;">

            <h3>{{ $item['name'] }}</h3>

            <p>Giá: {{ number_format($item['price']) }}đ</p>

            <form action="/cart/update/{{ $id }}" method="POST">
                @csrf
                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1">
                <button type="submit">Cập nhật</button>
            </form>

            <p>Tạm tính: {{ number_format($subtotal) }}đ</p>

            <a href="/cart/remove/{{ $id }}" style="color:red;">
                Xóa
            </a>

        </div>

    @endforeach

    <h3>Tổng tiền: {{ number_format($total) }}đ</h3>
    <a href="/checkout">
    <button>Thanh toán</button>
</a>

@else
    <p>Giỏ hàng trống</p>
@endif

@endsection
