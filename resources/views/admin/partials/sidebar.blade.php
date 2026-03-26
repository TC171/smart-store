<div class="w-72 min-h-screen bg-gradient-to-b from-indigo-950 via-indigo-900 to-indigo-800 text-white flex flex-col shadow-2xl">

    <!-- HEADER -->
    <div class="p-6 text-center border-b border-white/10">
        <a href="{{ route('admin.dashboard') }}"
            class="text-2xl font-bold tracking-widest bg-gradient-to-r from-purple-400 to-cyan-400 bg-clip-text text-transparent hover:scale-105 transition">
            🛍 SMART STORE
        </a>
    </div>

    <!-- MENU -->
    <nav class="flex-1 p-4 space-y-1 text-sm">

        @php
            $menu = [
                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '📊'],
                ['route' => 'admin.products.index', 'label' => 'Products', 'icon' => '📱'],
                ['route' => 'admin.categories.index', 'label' => 'Danh mục', 'icon' => '🗂'],
                ['route' => 'admin.brands.index', 'label' => 'Thương hiệu', 'icon' => '🏷'],
                ['route' => 'admin.variants.index', 'label' => 'Biến thể', 'icon' => '🔧'],
                ['route' => 'admin.product-attributes.index', 'label' => 'Thuộc tính', 'icon' => '🎨'],
                ['route' => 'admin.banners.index', 'label' => 'Banner', 'icon' => '🎬'],
                ['route' => 'admin.coupons.index', 'label' => 'Mã giảm giá', 'icon' => '🏷️'],
                ['route' => 'admin.orders.index', 'label' => 'Đơn hàng', 'icon' => '📦'],
                ['route' => 'admin.inventory-history.index', 'label' => 'Lịch sử kho', 'icon' => '📊'],
                ['route' => 'admin.reviews.index', 'label' => 'Đánh giá', 'icon' => '⭐'],
                ['route' => 'admin.users.index', 'label' => 'Tài khoản', 'icon' => '👥'],
                ['route' => 'admin.admins.index', 'label' => 'Quản trị viên', 'icon' => '👑'],
                ['route' => 'admin.customers.index', 'label' => 'Khách hàng', 'icon' => '👤'],
            ];
        @endphp

        @foreach($menu as $item)
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl transition-all duration-200
                {{ request()->routeIs($item['route']) ? 'bg-cyan-500/20 text-cyan-300 shadow-md' : 'hover:bg-white/10' }}">

                <span class="text-lg">{{ $item['icon'] }}</span>
                <span class="font-medium">{{ $item['label'] }}</span>
            </a>
        @endforeach

    </nav>

    <!-- FOOTER -->
    <div class="p-4 border-t border-white/10 text-xs text-center text-gray-300">
        © {{ date('Y') }} Smart Store
    </div>

</div>
