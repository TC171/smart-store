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
                <a href="{{ route('shipper.returns.index') }}"
                   class="px-3 py-1.5 rounded-lg text-sm {{ request()->routeIs('shipper.returns.*') ? 'bg-orange-500/20 text-orange-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
                    🔄 Hoàn hàng
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
    <a href="{{ route('shipper.returns.index') }}"
       class="flex-1 text-center py-2 rounded-lg text-sm {{ request()->routeIs('shipper.returns.*') ? 'bg-orange-500/20 text-orange-400' : 'text-gray-400' }} transition">
        🔄 Hoàn hàng
    </a>
</div>

<main class="max-w-6xl mx-auto px-4 py-6">
    @yield('content')
</main>

{{-- ===== TOAST NOTIFICATIONS ===== --}}
@if(session('success') || session('error'))
<div id="toastContainer" class="fixed top-5 right-5 z-[9999] space-y-3 w-80 pointer-events-none">

    @if(session('success'))
    <div id="toastSuccess"
         class="pointer-events-auto relative flex items-start gap-4 bg-gray-900 border border-green-500/40 rounded-2xl px-5 py-4 shadow-2xl shadow-green-900/40 overflow-hidden
                transition-all duration-500 translate-x-0 opacity-100">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0 pt-0.5">
            <p class="text-xs font-bold text-green-400 uppercase tracking-widest mb-0.5">Thành công ✓</p>
            <p class="text-sm text-gray-200 leading-snug">{{ session('success') }}</p>
        </div>
        <button onclick="dismissToast('toastSuccess')" class="flex-shrink-0 text-gray-600 hover:text-gray-400 transition mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="absolute bottom-0 left-0 h-[3px] bg-gradient-to-r from-green-500 to-cyan-400 toast-progress-bar"></div>
    </div>
    @endif

    @if(session('error'))
    <div id="toastError"
         class="pointer-events-auto relative flex items-start gap-4 bg-gray-900 border border-red-500/40 rounded-2xl px-5 py-4 shadow-2xl shadow-red-900/40 overflow-hidden
                transition-all duration-500 translate-x-0 opacity-100">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0 pt-0.5">
            <p class="text-xs font-bold text-red-400 uppercase tracking-widest mb-0.5">Có lỗi xảy ra ✗</p>
            <p class="text-sm text-gray-200 leading-snug">{{ session('error') }}</p>
        </div>
        <button onclick="dismissToast('toastError')" class="flex-shrink-0 text-gray-600 hover:text-gray-400 transition mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="absolute bottom-0 left-0 h-[3px] bg-gradient-to-r from-red-500 to-orange-400 toast-progress-bar"></div>
    </div>
    @endif

</div>

<style>
    .toast-progress-bar {
        animation: toastShrink 4s linear forwards;
    }
    @keyframes toastShrink {
        from { width: 100%; }
        to   { width: 0%; }
    }
</style>

<script>
    function dismissToast(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.opacity = '0';
        el.style.transform = 'translateX(115%)';
        setTimeout(() => el.remove(), 450);
    }
    // Slide in on load
    document.addEventListener('DOMContentLoaded', function () {
        ['toastSuccess', 'toastError'].forEach(function(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.transform = 'translateX(115%)';
            el.style.opacity = '0';
            requestAnimationFrame(() => {
                setTimeout(() => {
                    el.style.transform = 'translateX(0)';
                    el.style.opacity = '1';
                }, 50);
            });
            // Auto dismiss
            setTimeout(() => dismissToast(id), 4500);
        });
    });
</script>
@endif

</body>
</html>