@extends('frontend.layouts.app')

@section('content')

<h2>Trang chủ</h2>

@foreach($products as $product)
    <div style="border:1px solid #ccc; margin:10px; padding:10px;">

        <h3>
            <a href="/products/{{ $product->id }}">
                {{ $product->name }}
            </a>
        </h3>

        <p>Giá: {{ number_format($product->price) }}đ</p>

        <form action="/cart/add/{{ $product->id }}" method="POST">
    @csrf
    <button type="submit">Thêm vào giỏ</button>

    </div>

</form>
@endforeach

@endsection
