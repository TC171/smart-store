<header class="bg-gradient-to-r from-orange-500 to-orange-400 text-white shadow">

    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-6">

        <!-- Logo -->
        <a href="/" class="text-2xl font-bold">SmartStore</a>

        <!-- Search -->
        <div class="flex-1 relative">
            <input type="text"
                   placeholder="Tìm sản phẩm..."
                   class="w-full px-4 py-2 rounded text-black">

            <button class="absolute right-1 top-1 bottom-1 px-4 bg-orange-500 text-white rounded">
                🔍
            </button>
        </div>

        <!-- Cart -->
        <div class="relative cursor-pointer">
            🛒
            <span class="absolute -top-2 -right-2 bg-white text-orange-500 text-xs px-1 rounded-full">
                0
            </span>
        </div>
       @auth
    <div class="flex items-center gap-3">
        <span>Xin chào, {{ auth()->user()->name }}</span>

        {{-- <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="text-sm bg-white text-orange-500 px-3 py-1 rounded hover:bg-gray-100">
                Đăng xuất
            </button>
        </form> --}}
    </div>
@else
    <a href="{{ route('login') }}" class="bg-white text-orange-500 px-4 py-2 rounded">
        Đăng nhập
    </a>
@endauth
    </div>
</header>
