<div class="w-72 min-h-screen bg-gradient-to-b from-indigo-950 via-indigo-900 to-indigo-800 text-white flex flex-col shadow-2xl">

    <!-- HEADER -->
    <div class="p-6 text-center border-b border-white/10">
        <a href="{{ route('admin.dashboard') }}"
            class="text-2xl font-bold tracking-widest bg-gradient-to-r from-purple-400 to-cyan-400 bg-clip-text text-transparent hover:scale-105 transition">
            🛍 SMART STORE
        </a>
    </div>

    <!-- MENU -->
    <nav class="flex-1 p-4 space-y-1 text-sm overflow-y-auto">

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
                ['route' => 'admin.users.index', 'label' => 'Tài khoản', 'icon' => '👥'],
                ['route' => 'admin.admins.index', 'label' => 'Quản trị viên', 'icon' => '👑'],
                ['route' => 'admin.customers.index', 'label' => 'Khách hàng', 'icon' => '👤'],
            ];

            $pendingReviews = \App\Models\Review::where('is_approved', false)->count();
            $pendingRefunds = \App\Models\RefundRequest::where('status', 'pending')->count();
        @endphp

        @foreach($menu as $item)
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl transition-all duration-200
                {{ request()->routeIs($item['route']) ? 'bg-cyan-500/20 text-cyan-300 shadow-md' : 'hover:bg-white/10' }}">
                <span class="text-lg">{{ $item['icon'] }}</span>
                <span class="font-medium">{{ $item['label'] }}</span>
            </a>
        @endforeach

        {{-- Đánh giá với badge số đỏ --}}
        <a href="{{ route('admin.reviews.index') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin.reviews.index') ? 'bg-cyan-500/20 text-cyan-300 shadow-md' : 'hover:bg-white/10' }}">
            <span class="text-lg">⭐</span>
            <span class="font-medium flex-1">Đánh giá</span>
            @if($pendingReviews > 0)
                <span class="bg-red-500 text-white text-[10px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 leading-none">
                    {{ $pendingReviews > 99 ? '99+' : $pendingReviews }}
                </span>
            @endif
        </a>
        
        {{-- Hoàn hàng với badge số đỏ --}}
        <a href="{{ route('admin.refunds.index') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin.refunds.index') ? 'bg-cyan-500/20 text-cyan-300 shadow-md' : 'hover:bg-white/10' }}">
            <span class="text-lg">🔄</span>
            <span class="font-medium flex-1">Danh sách yêu cầu</span>
            @if($pendingRefunds > 0)
                <span class="bg-red-500 text-white text-[10px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 leading-none">
                    {{ $pendingRefunds > 99 ? '99+' : $pendingRefunds }}
                </span>
            @endif
        </a>

    </nav>

    <!-- FOOTER -->
    <div class="p-4 border-t border-white/10 text-xs text-center text-gray-300">
        © {{ date('Y') }} Smart Store
    </div>

</div>
