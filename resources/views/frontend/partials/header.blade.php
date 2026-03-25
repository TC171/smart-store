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

            <div class="w-full relative group">
                <form action="/tim-kiem" method="GET" class="w-full relative">
                    <!-- Search Input Container -->
                    <div class="relative">
                        <input type="text"
                               name="q"
                               x-model="query"
                               @input.debounce.300ms="search()"
                               @focus="open = true"
                               @keydown.escape="open = false"
                               placeholder="Tìm kiếm sản phẩm..."
                               class="w-full pl-12 pr-12 py-3 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:ring-4 focus:ring-orange-100 outline-none shadow-sm transition-all duration-200 text-gray-700 placeholder-gray-400">

                        <!-- Search Icon -->
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <!-- Loading Spinner -->
                        <div x-show="loading" class="absolute right-12 top-1/2 -translate-y-1/2">
                            <div class="animate-spin rounded-full h-4 w-4 border-2 border-orange-500 border-t-transparent"></div>
                        </div>

                        <!-- Clear Button -->
                        <button type="button"
                                x-show="query.length > 0"
                                @click="query = ''; results = []; open = false"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Enhanced Search Dropdown -->
                <div x-show="open && (results.length > 0 || query.length >= 2)"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 max-h-96 overflow-hidden">



                    <!-- Search Results -->
                    <div x-show="results.length > 0" class="max-h-72 overflow-y-auto">
                        <div class="px-4 py-2 border-b border-gray-100 text-xs font-bold uppercase tracking-wide text-gray-500">Sản phẩm đề xuất</div>

                        <template x-for="item in results" :key="item.id">
                            <a :href="'/san-pham/' + item.slug"
                               class="grid grid-cols-[60px_1fr_auto] gap-3 px-4 py-3 border-b border-gray-100 hover:bg-orange-50 transition">

                                <img :src="item.image || '/images/no-image.jpg'" class="w-14 h-14 object-cover rounded-lg border" />

                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="item.name"></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <p class="font-bold text-orange-500 text-base" x-text="item.sale_price ? Number(item.sale_price).toLocaleString() + 'đ' : Number(item.price).toLocaleString() + 'đ'"></p>
                                        <p x-show="item.sale_price && item.sale_price < item.price" class="text-xs text-gray-400 line-through" x-text="Number(item.price).toLocaleString() + 'đ'"></p>
                                        <span x-show="item.sale_price && item.sale_price < item.price" class="text-xs text-red-500">-<span x-text="Math.round((1-(item.sale_price/item.price))*100)"></span>%</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1" x-text="item.category || 'Sản phẩm' "></p>
                                </div>

                                <div class="text-orange-500 self-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                        </template>
                    </div>

                    <!-- No Results -->
                    <div x-show="query.length >= 2 && results.length === 0 && !loading"
                         class="p-8 text-center">
                        <div class="text-gray-400 mb-2">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">Không tìm thấy sản phẩm nào</p>
                        <p class="text-gray-400 text-xs mt-1">Thử tìm với từ khóa khác</p>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="p-8 text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-4 border-orange-500 border-t-transparent mx-auto mb-3"></div>
                        <p class="text-gray-500 text-sm">Đang tìm kiếm...</p>
                    </div>

                    <!-- View All Results -->
                    <div x-show="results.length > 0" class="border-t border-gray-100 p-3 bg-gray-50">
                        <a href="#"
                           @click="document.querySelector('form').submit()"
                           class="flex items-center justify-center gap-2 text-orange-600 hover:text-orange-700 font-medium text-sm transition-colors">
                            <span>Xem tất cả kết quả</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MOBILE SEARCH -->
        <div class="lg:hidden flex-1 max-w-xs mx-4"
             x-data="mobileSearchBox()">

            <div class="w-full relative">
                <form action="/tim-kiem" method="GET" class="w-full relative">
                    <div class="relative">
                        <input type="text"
                               name="q"
                               x-model="query"
                               @input.debounce.300ms="search()"
                               @focus="open = true"
                               @keydown.escape="open = false"
                               placeholder="Tìm kiếm..."
                               class="w-full pl-10 pr-10 py-2 rounded-xl border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none text-sm">

                        <!-- Search Icon -->
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <!-- Clear Button -->
                        <button type="button"
                                x-show="query.length > 0"
                                @click="query = ''; results = []; open = false"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Mobile Search Dropdown -->
                <div x-show="open && (results.length > 0 || query.length >= 2)"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border z-50 max-h-64 overflow-hidden">

                    <div class="border-b border-gray-100 px-3 py-2 bg-neutral-50">
                        <div class="text-xs text-gray-500 font-semibold">Xu hướng tìm kiếm</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button type="button" @click="query='iPhone 17'; search()" class="rounded-full border border-gray-200 px-2 py-1 text-xs text-gray-700">iPhone 17</button>
                            <button type="button" @click="query='Laptop'; search()" class="rounded-full border border-gray-200 px-2 py-1 text-xs text-gray-700">Laptop</button>
                            <button type="button" @click="query='Samsung'; search()" class="rounded-full border border-gray-200 px-2 py-1 text-xs text-gray-700">Samsung</button>
                        </div>
                    </div>

                    <div x-show="results.length > 0" class="max-h-56 overflow-y-auto">
                        <template x-for="item in results.slice(0, 5)" :key="item.id">
                            <a :href="'/san-pham/' + item.slug"
                               class="flex items-center gap-3 p-3 hover:bg-orange-50 active:bg-orange-100">

                                <img :src="item.image || '/images/no-image.jpg'"
                                     class="w-10 h-10 rounded-lg object-cover border">

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate" x-text="item.name"></p>
                                    <p class="text-orange-600 font-bold text-sm"
                                       x-text="Number(item.price).toLocaleString() + 'đ'"></p>
                                </div>
                            </a>
                        </template>
                    </div>

                    <!-- View All Results -->
                    <div x-show="results.length > 0" class="border-t p-3 bg-gray-50">
                        <a href="#"
                           @click="document.querySelector('form').submit()"
                           class="flex items-center justify-center text-orange-600 font-medium text-sm">
                            Xem tất cả
                        </a>
                    </div>

                    <!-- No Results -->
                    <div x-show="query.length >= 2 && results.length === 0"
                         class="p-6 text-center text-gray-500 text-sm">
                        Không tìm thấy sản phẩm
                    </div>
                </div>
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
            suggestions: [],
            open: false,
            loading: false,

            async search() {
                if (this.query.length < 2) {
                    this.results = [];
                    this.suggestions = [];
                    this.loading = false;
                    return;
                }

                this.loading = true;
                try {
                    const res = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
                    if (res.ok) {
                        this.results = await res.json();
                        this.suggestions = this.results.slice(0, 5).map(item => item.name);
                    } else {
                        this.results = [];
                        this.suggestions = [];
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    this.results = [];
                    this.suggestions = [];
                } finally {
                    this.loading = false;
                }
            }
        }
    }

    function mobileSearchBox() {
        return {
            query: '',
            results: [],
            suggestions: [],
            open: false,
            loading: false,

            async search() {
                if (this.query.length < 2) {
                    this.results = [];
                    this.suggestions = [];
                    this.loading = false;
                    return;
                }

                this.loading = true;
                try {
                    const res = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
                    if (res.ok) {
                        this.results = await res.json();
                        this.suggestions = this.results.slice(0, 5).map(item => item.name);
                    } else {
                        this.results = [];
                        this.suggestions = [];
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    this.results = [];
                    this.suggestions = [];
                } finally {
                    this.loading = false;
                }
            }
        }
    }
    </script>

</header>