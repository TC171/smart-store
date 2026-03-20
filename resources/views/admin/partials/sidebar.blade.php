<div class="w-72 bg-gradient-to-b from-indigo-900 to-indigo-700 text-white flex flex-col shadow-2xl">

    <div class="p-6 text-center border-b border-purple-500/30">
        <a href="{{ route('admin.dashboard') }}"
            class="text-2xl font-bold tracking-widest 
              bg-gradient-to-r from-purple-400 to-blue-500
              bg-clip-text text-transparent
              hover:scale-105 transition">
            SMART STORE
        </a>
    </div>

    <nav class="flex-1 p-4 space-y-2 text-sm">

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/20 transition">
            📊 <span>Dashboard</span>
        </a>


        <a href="{{ route('products.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20 
            {{ request()->routeIs('products.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            📱 Products
        </a>

        <a href="{{ route('categories.index') }}"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/20 transition">
            🗂 <span>Danh mục</span>
        </a>
        
        <a href="{{ route('brands.index') }}"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/20 transition">
            🏷 <span>Thương hiệu</span>
        </a>

        <a href="{{ route('variants.index') }}"
            class="flex items-center p-2 text-gray-300 hover:text-cyan-400">
            🗂<span>Biến thể</span>
        </a>

        <a href="{{ route('product-attributes.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
            {{ request()->routeIs('product-attributes.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            🎨 Thuộc tính
        </a>

        <a href="{{ route('banners.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
            {{ request()->routeIs('banners.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            🎬 Banner
        </a>

        <a href="{{ route('coupons.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
            {{ request()->routeIs('coupons.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            🏷️ Mã giảm giá
        </a>

        <a href="{{ route('orders.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
            {{ request()->routeIs('orders.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            📦 Đơn hàng
        </a>

        <a href="{{ route('inventory-history.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
            {{ request()->routeIs('inventory-history.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            📊 Lịch sử kho
        </a>

        <a href="{{ route('admins.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
            {{ request()->routeIs('admins.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            👑 Quản trị viên
        </a>

        <a href="{{ route('customers.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
            {{ request()->routeIs('customers.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            👥 Khách hàng
        </a>

        <a href="{{ route('reviews.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
            {{ request()->routeIs('reviews.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            ⭐ Đánh giá
        </a>

        <a href="{{ route('users.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
            {{ request()->routeIs('users.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            👥 Tài khoản
        </a>

    </nav>

    <div class="p-4 border-t border-indigo-500 text-xs text-center opacity-70">
        © {{ date('Y') }} Smart Store
    </div>

</div>