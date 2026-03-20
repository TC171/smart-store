@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow">

        <h2 class="text-2xl font-bold mb-6 text-center">
            Đăng nhập
        </h2>

        <form method="POST" action="{{ route('login.post') }}">
    @csrf

    <input type="email" name="email" placeholder="Email"
        class="w-full border px-4 py-2 mb-3 rounded-lg">

    <input type="password" name="password" placeholder="Mật khẩu"
        class="w-full border px-4 py-2 mb-3 rounded-lg">

    @error('email')
        <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <button class="w-full bg-red-500 text-white py-2 rounded-lg">
        Đăng nhập
    </button>
</form>

        <p class="text-sm text-center mt-4">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="text-red-500">Đăng ký</a>
        </p>

    </div>

</div>
@endsection
