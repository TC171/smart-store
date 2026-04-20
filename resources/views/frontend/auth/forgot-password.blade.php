@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow">

        <h2 class="text-2xl font-bold mb-6 text-center">
            Quên mật khẩu
        </h2>

        @if(session('error'))
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.forgot-password.post') }}">
            @csrf
            
            <p class="text-sm text-gray-600 mb-4 text-center">
                Vui lòng nhập thư điện tử (email) bạn đã sử dụng để đăng ký. Chúng tôi sẽ cấp lại một mật khẩu mới gửi vào email của bạn.
            </p>

            <input type="email" name="email" placeholder="Nhập email của bạn" required
                class="w-full border px-4 py-2 mb-4 rounded-lg">

            @error('email')
                <p class="text-red-500 text-sm mb-3">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg">
                Gửi mật khẩu mới
            </button>
        </form>

        <p class="text-sm text-center mt-4">
            <a href="{{ route('customer.login') }}" class="text-red-500 hover:underline">Quay lại đăng nhập</a>
        </p>

    </div>

</div>
@endsection
