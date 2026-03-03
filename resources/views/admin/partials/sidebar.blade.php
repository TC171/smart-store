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

        <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/20 transition">
            🗂 <span>Categories</span>
        </a>

        <a href="{{ route('orders.index') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-cyan-500/20
   {{ request()->routeIs('orders.*') ? 'bg-cyan-500/20 text-cyan-400' : '' }}">
            🛒 Orders
        </a>

        <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/20 transition">
            👥 <span>Users</span>
        </a>

    </nav>

    <div class="p-4 border-t border-indigo-500 text-xs text-center opacity-70">
        © {{ date('Y') }} Smart Store
    </div>

</div>
