@extends('frontend.layouts.app')

@section('content')
<div class="max-w-md mx-auto py-10">

    <h2 class="text-2xl font-bold mb-6 text-center">Đăng ký</h2>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <input type="text" name="name" placeholder="Tên"
            class="w-full border p-3 mb-3 rounded">

        <input type="email" name="email" placeholder="Email"
            class="w-full border p-3 mb-3 rounded">

        <input type="password" name="password" placeholder="Mật khẩu"
            class="w-full border p-3 mb-3 rounded">

        <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu"
            class="w-full border p-3 mb-3 rounded">

        <button class="w-full bg-red-500 text-white py-3 rounded">
            Đăng ký
        </button>
    </form>

</div>
@endsection
