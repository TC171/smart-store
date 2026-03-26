@extends('frontend.layouts.app')

@section('content')

<h2>Thanh toán</h2>

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<form action="{{ route('checkout.store') }}" method="POST">
    @csrf

    <div>
        <label>Tên khách hàng:</label><br>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>Số điện thoại:</label><br>
        <input type="text" name="phone" required>
    </div>

    <div>
        <label>Địa chỉ:</label><br>
        <input type="text" name="address" required>
    </div>

    <br>

    <button type="submit">Đặt hàng</button>
</form>

@endsection
