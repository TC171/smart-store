<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipper – Đăng nhập</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">

    <div class="text-center mb-8">
        <div class="text-5xl mb-3">🚴</div>
        <h1 class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-indigo-400 bg-clip-text text-transparent">
            Smart Store – Shipper Portal
        </h1>
        <p class="text-gray-400 text-sm mt-1">Đăng nhập để quản lý đơn giao hàng của bạn</p>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-gray-900 rounded-2xl p-8 shadow-2xl border border-gray-800">
        <form action="{{ route('shipper.login.post') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="shipper@smartstore.com"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-gray-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Mật khẩu</label>
                <input type="password" name="password" required
                    placeholder="••••••••"
                    class="w-full bg-gray-800 text-white border border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-gray-500 transition">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded border-gray-600 text-cyan-500">
                <label for="remember" class="text-gray-400 text-sm">Ghi nhớ đăng nhập</label>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-600 hover:to-indigo-700 text-white py-3 rounded-xl font-semibold transition duration-200 shadow-lg">
                Đăng nhập
            </button>
        </form>
    </div>

    <p class="text-center text-gray-500 text-xs mt-6">
        Gặp sự cố? Liên hệ quản trị viên để được hỗ trợ.
    </p>

</div>

</body>
</html>
