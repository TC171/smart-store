<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Store – Shipper Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen">

<nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">

        <div class="flex items-center gap-6">
            <a href="{{ route('shipper.dashboard') }}"
               class="text-xl font-bold bg-gradient-to-r from-cyan-400 to-indigo-400 bg-clip-text text-transparent">
                🚴 Shipper Portal
            </a>
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('shipper.dashboard') }}"
                   class="px-3 py-1.5 rounded-lg text-sm {{ request()->routeIs('shipper.dashboard') ? 'bg-cyan-500/20 text-cyan-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
                    📊 Dashboard
                </a>
                <a href="{{ route('shipper.deliveries.index') }}"
                   class="px-3 py-1.5 rounded-lg text-sm {{ request()->routeIs('shipper.deliveries.*') ? 'bg-cyan-500/20 text-cyan-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
                    📦 Đơn giao hàng
                </a>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(auth('shipper')->user()->name, 0, 1)) }}
                </div>
                <span class="text-gray-300 text-sm hidden md:block">{{ auth('shipper')->user()->name }}</span>
            </div>
            <form action="{{ route('shipper.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-400 text-sm transition px-2 py-1 rounded">
                    Đăng xuất
                </button>
            </form>
        </div>

    </div>
</nav>

{{-- Mobile nav --}}
<div class="md:hidden bg-gray-900 border-b border-gray-800 px-4 py-2 flex gap-2">
    <a href="{{ route('shipper.dashboard') }}"
       class="flex-1 text-center py-2 rounded-lg text-sm {{ request()->routeIs('shipper.dashboard') ? 'bg-cyan-500/20 text-cyan-400' : 'text-gray-400' }} transition">
        📊 Dashboard
    </a>
    <a href="{{ route('shipper.deliveries.index') }}"
       class="flex-1 text-center py-2 rounded-lg text-sm {{ request()->routeIs('shipper.deliveries.*') ? 'bg-cyan-500/20 text-cyan-400' : 'text-gray-400' }} transition">
        📦 Đơn hàng
    </a>
</div>

<main class="max-w-6xl mx-auto px-4 py-6">
    @if(session('success'))
        <div class="mb-4 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg text-sm">
            ❌ {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>
