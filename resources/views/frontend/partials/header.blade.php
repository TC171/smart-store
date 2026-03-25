<header x-data="{ scrolled: false }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)"
        :class="scrolled ? 'h-14 shadow-lg bg-white/95 backdrop-blur-xl' : 'h-20 bg-white/70 backdrop-blur-md'"
        class="fixed top-0 w-full z-50 border-b border-gray-200 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 lg:px-8 h-full flex items-center justify-between">

        <!-- LOGO -->
        <a href="/" class="text-2xl font-black tracking-tight flex items-center gap-1">
            Smart<span class="text-orange-500">Store</span>
        </a>

        <!-- SEARCH -->
        <div class="hidden lg:flex flex-1 max-w-2xl mx-10"
             x-data="searchBox()">

            <form action="/tim-kiem" method="GET" class="w-full relative">
                
                <input type="text"
                       name="q"
                       x-model="query"
                       @input.debounce.300ms="search()"
                       @focus="open = true"
                       placeholder="🔍 Tìm sản phẩm..."
                       class="w-full pl-4 pr-12 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-300 outline-none shadow-sm">

                <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-orange-500 text-white px-3 py-1.5 rounded-lg hover:bg-orange-600">
                    🔍
                </button>
            </form>

            <!-- SEARCH DROPDOWN -->
            <div x-show="open && results.length"
                 @click.away="open = false"
                 class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border z-50 max-h-80 overflow-y-auto">

                <template x-for="item in results" :key="item.id">
                    <a :href="'/san-pham/' + item.slug"
                       class="flex items-center gap-3 p-3 hover:bg-gray-50">

                        <img :src="item.image" class="w-10 h-10 rounded object-cover">

                        <div class="flex-1">
                            <p class="text-sm font-medium" x-text="item.name"></p>
                            <p class="text-orange-500 font-bold text-sm"
                               x-text="Number(item.price).toLocaleString() + 'đ'"></p>
                        </div>
                    </a>
                </template>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-4">

            <!-- CART -->
            <div x-data="{ open:false }" class="relative">
                <button @click="open = !open"
                        class="relative text-gray-700 hover:text-orange-500 transition">
                    🛒
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full animate-pulse">
                        {{ count(session('cart', [])) }}
                    </span>
                </button>

                <!-- CART DROPDOWN -->
                <div x-show="open" @click.away="open=false"
                     class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border overflow-hidden z-50">

                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold text-gray-800">🛒 Giỏ hàng</h3>
                    </div>

                    <div class="max-h-64 overflow-y-auto p-3 space-y-3">
                        @forelse(session('cart', []) as $id => $item)
                            <div class="flex gap-3 items-center">

                                <img src="{{ $item['image'] ?? asset('images/no-image.jpg') }}"
                                     class="w-12 h-12 object-cover rounded-lg border">

                                <div class="flex-1 text-sm">
                                    <p class="line-clamp-1 font-medium">{{ $item['name'] }}</p>
                                    <p class="text-orange-500 font-bold">
                                        {{ number_format($item['price']) }}đ
                                    </p>
                                    <p class="text-xs text-gray-500">x{{ $item['quantity'] }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 py-6">Giỏ hàng trống</p>
                        @endforelse
                    </div>

                    <div class="p-4 border-t bg-gray-50">
                        <a href="{{ route('cart.index') }}"
                           class="block text-center bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">
                           Xem giỏ hàng
                        </a>
                    </div>
                </div>
            </div>

            <!-- USER -->
            @auth
            <div x-data="{ open:false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name,0,2)) }}
                    </div>
                </button>

                <div x-show="open" @click.away="open=false"
                     class="absolute right-0 mt-3 w-40 bg-white border rounded-xl shadow-lg py-2 text-sm z-50">

                    <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Tài khoản</a>
                    <a href="{{ route('customer.orders') }}" class="block px-4 py-2 hover:bg-gray-100">Đơn hàng</a>

                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="text-sm px-4 py-2 hover:text-orange-500">
                Đăng nhập
            </a>
            <a href="{{ route('register') }}"
               class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-600">
                Đăng ký
            </a>
            @endauth

        </div>
    </div>

    <!-- SEARCH SCRIPT -->
    <script>
    function searchBox() {
        return {
            query: '',
            results: [],
            open: false,

            async search() {
                if (this.query.length < 2) {
                    this.results = [];
                    return;
                }

                const res = await fetch(`/api/search?q=${this.query}`);
                this.results = await res.json();
            }
        }
    }
    </script>

</header>