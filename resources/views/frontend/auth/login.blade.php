@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow">

        <h2 class="text-2xl font-bold mb-6 text-center">
            Đăng nhập
        </h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-600 p-3 rounded mb-4 text-sm text-center">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.login.post') }}">
    @csrf

    <input type="email" name="email" placeholder="Email"
        class="w-full border px-4 py-2 mb-3 rounded-lg">

    <input type="password" name="password" placeholder="Mật khẩu"
        class="w-full border px-4 py-2 mb-3 rounded-lg">

    <div class="flex justify-end w-full mb-3">
        <a href="{{ route('customer.forgot-password') }}" class="text-sm text-red-500 hover:underline">Quên mật khẩu?</a>
    </div>

    @error('email')
        <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <button class="w-full bg-red-500 text-white py-2 rounded-lg">
        Đăng nhập
    </button>
</form>

        <p class="text-sm text-center mt-4">
            Chưa có tài khoản?
            <a href="{{ route('customer.register') }}" class="text-red-500">Đăng ký</a>
        </p>

    </div>

</div>
@endsection
