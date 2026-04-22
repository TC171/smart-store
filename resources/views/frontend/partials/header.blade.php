<header x-data="{ scrolled: false }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)"
        :class="scrolled ? 'h-14 shadow-lg bg-white/95 backdrop-blur-xl' : 'h-20 bg-white/70 backdrop-blur-md'"
        class="fixed top-0 w-full z-50 border-b border-gray-200 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 lg:px-8 h-full flex items-center justify-between">

        <a href="/" class="text-2xl font-black tracking-tight flex items-center gap-1">
            Smart<span class="text-orange-500">Store</span>
        </a>

        <div class="hidden lg:flex flex-1 max-w-2xl mx-10"
             x-data="searchBox()">

            <div class="w-full relative group" @click.outside="open = false">
                <form action="/tim-kiem" method="GET" class="w-full relative" @submit.prevent="submitSearch()">
                    <div class="relative">
                        <input type="text"
                               name="q"
                               x-model="query"
                               @input.debounce.300ms="search()"
                               @focus="handleFocus()"
                               @keydown.escape="open = false"
                               placeholder="Tìm kiếm sản phẩm, thương hiệu..."
                               autocomplete="off"
                               class="w-full pl-12 pr-12 py-3 rounded-2xl border-2 border-gray-200 focus:border-orange-400 focus:ring-4 focus:ring-orange-100 outline-none shadow-sm transition-all duration-200 text-gray-700 placeholder-gray-400">

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-orange-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <div x-show="loading" class="absolute right-12 top-1/2 -translate-y-1/2">
                            <div class="animate-spin rounded-full h-4 w-4 border-2 border-orange-500 border-t-transparent"></div>
                        </div>

                        <button type="button"
                                x-show="query.length > 0"
                                @click="query = ''; results = []; open = true"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Dropdown: Gợi ý khi chưa gõ -->
                <div x-show="open && query.length < 2 && (suggestedProducts.length > 0 || categories.length > 0 || brands.length > 0)"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     x-cloak
                     class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 max-h-[480px] flex flex-col overflow-hidden">

                    <!-- Gợi ý tìm kiếm (Danh mục + Thương hiệu) -->
                    <div x-show="categories.length > 0 || brands.length > 0" class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-amber-50">
                        <div class="flex items-center gap-2 mb-2.5">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider text-orange-600">Gợi ý tìm kiếm</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="cat in categories" :key="'cat-' + cat.slug">
                                <a :href="'/tim-kiem?q=' + encodeURIComponent(cat.name) + '&category=' + cat.slug"
                                   class="inline-flex items-center gap-1.5 rounded-full border border-orange-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-all duration-200 shadow-sm">
                                    <span x-show="cat.icon" x-text="cat.icon"></span>
                                    <span x-text="cat.name"></span>
                                </a>
                            </template>
                            <template x-for="b in brands" :key="'brand-' + b.slug">
                                <a :href="'/tim-kiem?q=' + encodeURIComponent(b.name) + '&brand=' + b.slug"
                                   class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-blue-500 hover:text-white hover:border-blue-500 transition-all duration-200 shadow-sm">
                                    <template x-if="b.logo">
                                        <img :src="b.logo" class="h-3.5 w-auto object-contain">
                                    </template>
                                    <span x-text="b.name"></span>
                                </a>
                            </template>
                        </div>
                    </div>

                    <!-- Sản phẩm gợi ý -->
                    <div x-show="suggestedProducts.length > 0">
                        <div class="px-4 py-2 border-b border-gray-100 text-xs font-bold uppercase tracking-wide text-gray-500 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            Sản phẩm nổi bật
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            <template x-for="item in suggestedProducts" :key="item.id">
                                <a :href="'/' + item.slug"
                                   class="grid grid-cols-[60px_1fr_auto] gap-3 px-4 py-3 border-b border-gray-50 hover:bg-orange-50 transition-colors">
                                    <img :src="item.image || '/images/no-image.jpg'" class="w-14 h-14 object-cover rounded-lg border" />
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="item.name"></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="font-bold text-orange-500 text-base" x-text="item.sale_price ? Number(item.sale_price).toLocaleString() + 'đ' : Number(item.price).toLocaleString() + 'đ'"></p>
                                            <p x-show="item.sale_price && item.sale_price < item.price" class="text-xs text-gray-400 line-through" x-text="Number(item.price).toLocaleString() + 'đ'"></p>
                                            <span x-show="item.sale_price && item.sale_price < item.price" class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full font-bold">-<span x-text="Math.round((1-(item.sale_price/item.price))*100)"></span>%</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-0.5" x-text="item.category"></p>
                                    </div>
                                    <div class="text-orange-400 self-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>

                    <!-- Loading suggestions -->
                    <div x-show="loadingSuggestions" class="p-6 text-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-2 border-orange-500 border-t-transparent mx-auto mb-2"></div>
                        <p class="text-gray-400 text-xs">Đang tải gợi ý...</p>
                    </div>
                </div>

                <!-- Dropdown: Kết quả tìm kiếm khi đã gõ -->
                <div x-show="open && query.length >= 2 && (results.products.length > 0 || results.categories.length > 0 || results.brands.length > 0 || loading || (!loading && results.products.length === 0))"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     x-cloak
                     class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 max-h-96 overflow-hidden">

                    <div x-show="results.products.length > 0 || results.categories.length > 0 || results.brands.length > 0" class="max-h-72 overflow-y-auto">
                        <!-- Gợi ý Danh mục/Thương hiệu khớp -->
                        <div x-show="results.categories.length > 0 || results.brands.length > 0" class="bg-gray-50/80 p-3 border-b flex flex-wrap gap-2">
                             <template x-for="cat in results.categories" :key="'res-cat-'+cat.slug">
                                <a :href="'/tim-kiem?q=' + encodeURIComponent(cat.name) + '&category=' + cat.slug"
                                   class="inline-flex items-center gap-1.5 rounded-full border border-orange-200 bg-white px-2.5 py-1 text-[11px] font-bold text-gray-700 hover:bg-orange-500 hover:text-white transition-all shadow-sm">
                                    <span x-show="cat.icon" x-text="cat.icon"></span>
                                    <span x-text="cat.name"></span>
                                </a>
                            </template>
                            <template x-for="b in results.brands" :key="'res-b-'+b.slug">
                                <a :href="'/tim-kiem?q=' + encodeURIComponent(b.name) + '&brand=' + b.slug"
                                   class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-white px-2.5 py-1 text-[11px] font-bold text-gray-700 hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                                    <template x-if="b.logo">
                                        <img :src="b.logo" class="h-3 w-auto object-contain">
                                    </template>
                                    <span x-text="b.name"></span>
                                </a>
                            </template>
                        </div>

                        <div class="px-4 py-2 border-b border-gray-100 text-xs font-bold uppercase tracking-wide text-gray-400">Sản phẩm đề xuất</div>

                        <template x-for="item in results.products" :key="item.id">
                            <a :href="'/' + item.slug"
                               class="grid grid-cols-[60px_1fr_auto] gap-3 px-4 py-3 border-b border-gray-100 hover:bg-orange-50 transition">

                                <img :src="item.image || '/images/no-image.jpg'" class="w-14 h-14 object-cover rounded-lg border" />

                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="item.name"></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <p class="font-bold text-orange-500 text-base" x-text="item.sale_price ? Number(item.sale_price).toLocaleString() + 'đ' : Number(item.price).toLocaleString() + 'đ'"></p>
                                        <p x-show="item.sale_price && item.sale_price < item.price" class="text-xs text-gray-400 line-through" x-text="Number(item.price).toLocaleString() + 'đ'"></p>
                                        <span x-show="item.sale_price && item.sale_price < item.price" class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full font-bold">-<span x-text="Math.round((1-(item.sale_price/item.price))*100)"></span>%</span>
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

                    <div x-show="query.length >= 2 && results.products.length === 0 && results.categories.length === 0 && results.brands.length === 0 && !loading"
                         class="p-8 text-center">
                        <div class="text-gray-400 mb-2">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">Không tìm thấy sản phẩm nào</p>
                        <p class="text-gray-400 text-xs mt-1">Thử tìm với tên sản phẩm, danh mục hoặc thương hiệu.</p>
                    </div>

                    <div x-show="loading" class="p-8 text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-4 border-orange-500 border-t-transparent mx-auto mb-3"></div>
                        <p class="text-gray-500 text-sm">Đang tìm kiếm...</p>
                    </div>

                    <div x-show="results.products.length > 0" class="border-t border-gray-100 p-3 bg-gray-50">
                        <a href="#"
                           @click.prevent="$el.closest('.group').querySelector('form').submit()"
                           class="flex items-center justify-center gap-2 text-orange-600 hover:text-orange-700 font-medium text-sm transition-colors">
                            <span>Xem tất cả kết quả cho "<span class="font-bold" x-text="query"></span>"</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:hidden flex-1 max-w-xs mx-4"
             x-data="mobileSearchBox()">

            <div class="w-full relative" @click.outside="open = false">
                <form action="/tim-kiem" method="GET" class="w-full relative" @submit.prevent="submitSearch()">
                    <div class="relative">
                        <input type="text"
                               name="q"
                               x-model="query"
                               @input.debounce.300ms="search()"
                               @focus="handleFocus()"
                               @keydown.escape="open = false"
                               placeholder="Tìm kiếm... (ip17, ss)"
                               autocomplete="off"
                               class="w-full pl-10 pr-10 py-2 rounded-xl border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none text-sm">

                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <button type="button"
                                x-show="query.length > 0"
                                @click="query = ''; results = []; open = true"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Dropdown: Gợi ý khi chưa gõ (Mobile) -->
                <div x-show="open && query.length < 2 && (suggestedProducts.length > 0 || categories.length > 0 || brands.length > 0)"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     x-cloak
                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border z-50 max-h-80 flex flex-col overflow-hidden">

                    <!-- Gợi ý tìm kiếm (Danh mục + Thương hiệu) -->
                    <div x-show="categories.length > 0 || brands.length > 0" class="border-b border-gray-100 px-3 py-2.5 bg-gradient-to-r from-orange-50 to-amber-50 overflow-y-auto">
                        <div class="flex items-center gap-1.5 mb-2">
                            <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-orange-600">Gợi ý tìm kiếm</span>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="cat in categories" :key="'cat-' + cat.slug">
                                <a :href="'/tim-kiem?q=' + encodeURIComponent(cat.name) + '&category=' + cat.slug"
                                   class="inline-flex items-center gap-1 rounded-full border border-orange-200 bg-white px-2 py-1 text-[11px] text-gray-700 hover:bg-orange-500 hover:text-white transition-all">
                                    <span x-show="cat.icon" x-text="cat.icon"></span>
                                    <span x-text="cat.name"></span>
                                </a>
                            </template>
                            <template x-for="b in brands" :key="'brand-' + b.slug">
                                <a :href="'/tim-kiem?q=' + encodeURIComponent(b.name) + '&brand=' + b.slug"
                                   class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-white px-2 py-1 text-[11px] text-gray-700 hover:bg-blue-500 hover:text-white transition-all">
                                    <template x-if="b.logo">
                                        <img :src="b.logo" class="h-2.5 w-auto object-contain">
                                    </template>
                                    <span x-text="b.name"></span>
                                </a>
                            </template>
                        </div>
                    </div>

                    <!-- Sản phẩm gợi ý -->
                    <div x-show="suggestedProducts.length > 0" class="max-h-56 overflow-y-auto">
                        <template x-for="item in suggestedProducts.slice(0, 4)" :key="item.id">
                            <a :href="'/' + item.slug"
                               class="flex items-center gap-3 p-3 hover:bg-orange-50 active:bg-orange-100 border-b border-gray-50">
                                <img :src="item.image || '/images/no-image.jpg'" class="w-10 h-10 rounded-lg object-cover border">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate" x-text="item.name"></p>
                                    <p class="text-orange-600 font-bold text-sm" x-text="item.sale_price ? Number(item.sale_price).toLocaleString() + 'đ' : Number(item.price).toLocaleString() + 'đ'"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- Dropdown: Kết quả tìm kiếm (Mobile) -->
                <div x-show="open && query.length >= 2 && (results.products.length > 0 || results.categories.length > 0 || results.brands.length > 0 || loading || (!loading && results.products.length === 0))"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     x-cloak
                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border z-50 max-h-64 overflow-hidden">

                    <div x-show="results.products.length > 0 || results.categories.length > 0 || results.brands.length > 0" class="max-h-56 overflow-y-auto">
                        <!-- Mobile: Gợi ý Danh mục/Thương hiệu khớp -->
                        <div x-show="results.categories.length > 0 || results.brands.length > 0" class="bg-gray-50/80 p-2 border-b flex flex-wrap gap-1.5">
                            <template x-for="cat in results.categories" :key="'m-res-cat-'+cat.slug">
                                <a :href="'/tim-kiem?q=' + encodeURIComponent(cat.name) + '&category=' + cat.slug"
                                   class="inline-flex items-center gap-1 rounded-full border border-orange-200 bg-white px-2 py-0.5 text-[10px] text-gray-700">
                                    <span x-text="cat.name"></span>
                                </a>
                            </template>
                            <template x-for="b in results.brands" :key="'m-res-b-'+b.slug">
                                <a :href="'/tim-kiem?q=' + encodeURIComponent(b.name) + '&brand=' + b.slug"
                                   class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-white px-2 py-0.5 text-[10px] text-gray-700">
                                    <span x-text="b.name"></span>
                                </a>
                            </template>
                        </div>

                        <template x-for="item in results.products.slice(0, 5)" :key="item.id">
                            <a :href="'/' + item.slug"
                               class="flex items-center gap-3 p-3 hover:bg-orange-50 active:bg-orange-100">
                                <img :src="item.image || '/images/no-image.jpg'" class="w-10 h-10 rounded-lg object-cover border">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate" x-text="item.name"></p>
                                    <p class="text-orange-600 font-bold text-sm" x-text="item.sale_price ? Number(item.sale_price).toLocaleString() + 'đ' : Number(item.price).toLocaleString() + 'đ'"></p>
                                </div>
                            </a>
                        </template>
                    </div>

                    <div x-show="results.products.length > 0" class="border-t p-3 bg-gray-50">
                        <a href="#"
                           @click.prevent="$el.closest('.relative').querySelector('form').submit()"
                           class="flex items-center justify-center text-orange-600 font-medium text-sm">
                            Xem tất cả kết quả
                        </a>
                    </div>

                    <div x-show="query.length >= 2 && results.products.length === 0 && results.categories.length === 0 && results.brands.length === 0 && !loading"
                         class="p-6 text-center text-gray-500 text-sm">
                        Không tìm thấy sản phẩm
                    </div>

                    <div x-show="loading" class="p-4 text-center">
                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-orange-500 border-t-transparent mx-auto"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">

            <div x-data="{ open:false }" class="relative">
                <button @click="open = !open"
                        class="relative text-gray-700 hover:text-orange-500 transition">
                    🛒
                    <span class="cart-badge-count absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full animate-pulse">
                        {{ count(session('cart', [])) }}
                    </span>
                </button>

                <div x-show="open" @click.away="open=false" x-cloak
                     class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border overflow-hidden z-50">

                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold text-gray-800">🛒 Giỏ hàng</h3>
                    </div>

                    <div id="mini-cart-items" class="max-h-64 overflow-y-auto p-3 space-y-3">
                        @forelse(session('cart', []) as $id => $item)
                            <div class="flex gap-3 items-center">

                                <img src="{{ $item['image'] ?? asset('images/no-image.jpg') }}"
                                     class="w-12 h-12 object-cover rounded-lg border">

                                <div class="flex-1 text-sm">
                                    <p class="line-clamp-1 font-medium">{{ $item['name'] }}</p>
                                    <p class="text-orange-500 font-bold">
                                        {{ number_format($item['price'], 0, ',', '.') }}đ
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

            @auth('web')
            {{-- 🔔 NOTIFICATION BELL --}}
            <div x-data="notificationBell()" x-init="init()" class="relative">
                <button @click="toggle()" id="notif-bell-btn"
                        class="relative text-gray-700 hover:text-orange-500 transition p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span x-show="unread > 0" x-text="unread > 9 ? '9+' : unread"
                          class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full leading-none" x-cloak></span>
                </button>

                <div x-show="open" @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     x-cloak
                     class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">

                    {{-- Header --}}
                    <div class="flex items-center justify-between px-4 py-3 border-b bg-gradient-to-r from-orange-500 to-amber-500">
                        <div class="flex items-center gap-2">
                            <span class="text-white font-bold text-sm">🔔 Thông báo</span>
                            <span x-show="unread > 0" x-text="unread + ' mới'"
                                  class="bg-white/30 text-white text-[10px] font-bold px-2 py-0.5 rounded-full" x-cloak></span>
                        </div>
                        <button x-show="unread > 0" @click="readAll()"
                                class="text-white/80 hover:text-white text-xs underline" x-cloak>Đọc tất cả</button>
                    </div>

                    {{-- List --}}
                    <div class="max-h-80 overflow-y-auto">
                        <template x-if="loading">
                            <div class="p-6 text-center">
                                <div class="animate-spin w-6 h-6 border-2 border-orange-500 border-t-transparent rounded-full mx-auto"></div>
                            </div>
                        </template>
                        <template x-if="!loading && notifications.length === 0">
                            <div class="p-8 text-center">
                                <div class="text-4xl mb-2">🔕</div>
                                <p class="text-gray-400 text-sm">Chưa có thông báo nào</p>
                            </div>
                        </template>
                        <template x-for="n in notifications" :key="n.id">
                            <a :href="n.url" @click="markRead(n)"
                               :class="n.read ? 'bg-white' : 'bg-orange-50'"
                               class="flex items-start gap-3 px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition cursor-pointer block">
                                <div :class="{
                                        'bg-green-100 text-green-600': n.color === 'green',
                                        'bg-blue-100 text-blue-600':  n.color === 'blue',
                                        'bg-red-100 text-red-600':    n.color === 'red',
                                        'bg-orange-100 text-orange-600': n.color === 'orange',
                                        'bg-gray-100 text-gray-600':  n.color === 'gray',
                                     }"
                                     class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-lg">
                                    <span x-text="n.icon"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 leading-tight" x-text="n.title"></p>
                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="n.body"></p>
                                    <p class="text-[10px] text-gray-400 mt-1" x-text="n.time"></p>
                                </div>
                                <div x-show="!n.read" class="w-2 h-2 bg-orange-500 rounded-full flex-shrink-0 mt-1.5" x-cloak></div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            @endauth

            @auth('web')
            <div x-data="{ open:false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 hover:text-orange-500 transition">
                    <svg class="w-7 h-7 text-gray-700 hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-sm font-bold text-gray-700">{{ auth('web')->user()->name }}</span>
                </button>

                <div x-show="open" @click.away="open=false" x-cloak
                     class="absolute right-0 mt-3 w-48 bg-white border rounded-xl shadow-lg py-2 text-sm z-50">

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
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="flex items-center gap-1 text-sm font-medium hover:text-orange-500 transition text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Đăng nhập
                </a>
                <a href="{{ route('register') }}"
                   class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-600 transition shadow-sm font-medium">
                    Đăng ký
                </a>
            </div>
            @endauth

        </div>
    </div>

    <script>
    function createSearchBase() {
        return {
            query: (new URLSearchParams(window.location.search)).get('q') || '',
            results: { products: [], categories: [], brands: [] },
            suggestedProducts: [],
            categories: [],
            brands: [],
            open: false,
            loading: false,
            loadingSuggestions: false,
            suggestionsLoaded: false,

            async handleFocus() {
                this.open = true;
                if (!this.suggestionsLoaded) {
                    await this.loadSuggestions();
                }
            },

            async loadSuggestions() {
                this.loadingSuggestions = true;
                try {
                    const res = await fetch('/api/search/suggestions');
                    if (res.ok) {
                        const data = await res.json();
                        this.suggestedProducts = data.products || [];
                        this.categories = data.categories || [];
                        this.brands = data.brands || [];
                        this.suggestionsLoaded = true;
                    }
                } catch (e) {
                    console.error('Suggestions error:', e);
                } finally {
                    this.loadingSuggestions = false;
                }
            },

            async search() {
                if (this.query.length < 2) {
                    this.results = { products: [], categories: [], brands: [] };
                    this.loading = false;
                    return;
                }

                this.loading = true;
                this.open = true;
                try {
                    const res = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
                    if (res.ok) {
                        this.results = await res.json();
                    } else {
                        this.results = { products: [], categories: [], brands: [] };
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    this.results = { products: [], categories: [], brands: [] };
                } finally {
                    this.loading = false;
                }
            },

            submitSearch() {
                if (this.query.length > 0) {
                    window.location.href = '/tim-kiem?q=' + encodeURIComponent(this.query);
                }
            }
        }
    }

    function searchBox() {
        return createSearchBase();
    }

    function mobileSearchBox() {
        return createSearchBase();
    }

    // 🔔 NOTIFICATION BELL COMPONENT
    function notificationBell() {
        return {
            open: false,
            loading: false,
            notifications: [],
            unread: 0,

            init() {
                this.fetchNotifications();
                // Poll every 60s for new notifications
                setInterval(() => this.fetchNotifications(), 60000);
            },

            async fetchNotifications() {
                try {
                    const res = await fetch('/customer/notifications', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.notifications = data.notifications;
                        this.unread = data.unread;
                    }
                } catch (e) {}
            },

            async toggle() {
                this.open = !this.open;
                if (this.open && this.notifications.length === 0) {
                    this.loading = true;
                    await this.fetchNotifications();
                    this.loading = false;
                }
            },

            async readAll() {
                await fetch('/customer/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                this.notifications = this.notifications.map(n => ({ ...n, read: true }));
                this.unread = 0;
            },

            async markRead(n) {
                if (!n.read) {
                    fetch('/customer/notifications/' + n.id + '/read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    n.read = true;
                    this.unread = Math.max(0, this.unread - 1);
                }
            }
        };
    }


    // --- TOAST TRUNG TÂM (đăng ký, đặt hàng, lỗi) ---
    function showToast(message, type = 'success', subtitle = '') {
        const existingToast = document.getElementById('custom-toast');
        if (existingToast) {
            existingToast.classList.add('opacity-0', 'scale-95');
            setTimeout(() => existingToast.remove(), 200);
        }

        const configs = {
            success: {
                bg: 'from-white to-green-50',
                border: 'border-green-200',
                iconBg: 'bg-gradient-to-br from-green-400 to-emerald-500',
                iconSvg: '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>',
                bar: 'bg-gradient-to-r from-green-400 to-emerald-500'
            },
            error: {
                bg: 'from-white to-red-50',
                border: 'border-red-200',
                iconBg: 'bg-gradient-to-br from-red-400 to-rose-500',
                iconSvg: '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>',
                bar: 'bg-gradient-to-r from-red-400 to-rose-500'
            },
            cart: {
                bg: 'from-white to-orange-50',
                border: 'border-orange-200',
                iconBg: 'bg-gradient-to-br from-orange-400 to-amber-500',
                iconSvg: '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>',
                bar: 'bg-gradient-to-r from-orange-400 to-amber-500'
            }
        };

        const c = configs[type] || configs.success;
        const toast = document.createElement('div');
        toast.id = 'custom-toast';
        toast.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%) scale(0.85);z-index:99999;opacity:0;transition:all 0.35s cubic-bezier(0.34,1.56,0.64,1);min-width:320px;max-width:400px;';
        toast.innerHTML = `
            <div style="background:linear-gradient(135deg,#fff,#f9f9f9);border:1px solid #e5e7eb;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,0.15),0 4px 16px rgba(0,0,0,0.08);overflow:hidden;">
                <div style="padding:24px 24px 20px;display:flex;align-items:center;gap:16px;">
                    <div style="width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:linear-gradient(135deg,${type === 'error' ? '#f87171,#f43f5e' : type === 'cart' ? '#fb923c,#f59e0b' : '#4ade80,#10b981'});box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                        ${c.iconSvg}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-weight:800;font-size:15px;color:#1f2937;margin:0 0 2px;line-height:1.3;">${message}</p>
                        ${subtitle ? `<p style="font-size:12px;color:#9ca3af;margin:0;">${subtitle}</p>` : ''}
                    </div>
                    <button onclick="this.closest('#custom-toast').remove()" style="width:28px;height:28px;border-radius:50%;background:#f3f4f6;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#6b7280;font-size:14px;">✕</button>
                </div>
                <div id="toast-progress-bar" style="height:3px;width:100%;background:linear-gradient(90deg,${type === 'error' ? '#f87171,#f43f5e' : type === 'cart' ? '#fb923c,#f59e0b' : '#4ade80,#10b981'});transition:width linear 2.3s;"></div>
            </div>
        `;

        document.body.appendChild(toast);
        requestAnimationFrame(() => {
            toast.style.transform = 'translate(-50%,-50%) scale(1)';
            toast.style.opacity = '1';
            setTimeout(() => {
                const bar = toast.querySelector('#toast-progress-bar');
                if (bar) bar.style.width = '0%';
            }, 50);
        });

        setTimeout(() => {
            toast.style.transform = 'translate(-50%,-50%) scale(0.85)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 350);
        }, 2500);
    }

    // --- CART TOAST (góc trên bên phải) với preview sản phẩm ---
    function showCartToast(itemName, itemImage, itemPrice, cartCount) {
        const existing = document.getElementById('cart-toast');
        if (existing) {
            existing.style.transform = 'translateX(120%)';
            setTimeout(() => existing.remove(), 300);
        }

        const toast = document.createElement('div');
        toast.id = 'cart-toast';
        toast.style.cssText = 'position:fixed;top:88px;right:20px;z-index:99998;transform:translateX(120%);transition:all 0.4s cubic-bezier(0.34,1.56,0.64,1);max-width:320px;min-width:280px;';
        toast.innerHTML = `
            <div style="background:white;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,0.12),0 2px 8px rgba(0,0,0,0.06);border:1px solid #fed7aa;overflow:hidden;">
                <div style="background:linear-gradient(135deg,#ff6b35,#f59e0b);padding:10px 16px;display:flex;align-items:center;gap:8px;">
                    <span style="font-size:16px;">🛒</span>
                    <span style="color:white;font-weight:800;font-size:13px;letter-spacing:0.5px;">ĐÃ THÊM VÀO GIỎ HÀNG</span>
                    <span style="background:rgba(255,255,255,0.3);color:white;font-size:11px;font-weight:800;padding:2px 8px;border-radius:20px;margin-left:auto;">${cartCount} SP</span>
                </div>
                <div style="padding:12px 16px;display:flex;align-items:center;gap:12px;">
                    ${itemImage ? `<img src="${itemImage}" style="width:52px;height:52px;object-fit:cover;border-radius:10px;border:2px solid #fed7aa;flex-shrink:0;">` : '<div style="width:52px;height:52px;background:#fff7ed;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">📦</div>'}
                    <div style="flex:1;min-width:0;">
                        <p style="font-weight:700;font-size:13px;color:#1f2937;margin:0 0 3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${itemName}</p>
                        <p style="font-size:13px;font-weight:800;color:#f97316;margin:0;">${itemPrice}</p>
                    </div>
                </div>
                <div style="padding:0 16px 12px;display:flex;gap:8px;">
                    <a href="/gio-hang" style="flex:1;text-align:center;padding:8px;background:#fff7ed;color:#f97316;font-weight:700;font-size:12px;border-radius:8px;text-decoration:none;border:1px solid #fed7aa;">Xem giỏ hàng</a>
                    <a href="/checkout" style="flex:1;text-align:center;padding:8px;background:linear-gradient(135deg,#f97316,#f59e0b);color:white;font-weight:700;font-size:12px;border-radius:8px;text-decoration:none;">Mua ngay</a>
                </div>
                <div id="cart-toast-bar" style="height:3px;background:linear-gradient(90deg,#f97316,#f59e0b);transition:width linear 3.5s;"></div>
            </div>
        `;

        document.body.appendChild(toast);
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            setTimeout(() => {
                const bar = toast.querySelector('#cart-toast-bar');
                if (bar) bar.style.width = '0%';
            }, 100);
        });

        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, 3800);
    }

    // --- REVIEW POPUP (góc dưới bên phải) - Nhắc nhở đánh giá sau khi đơn hoàn thành ---
    function showReviewPrompt(orderNumber, orderUrl, orderId) {
        if (orderId && localStorage.getItem('review_dismissed_' + orderId)) return;
        const existing = document.getElementById('review-prompt');
        if (existing) return;

        const prompt = document.createElement('div');
        prompt.id = 'review-prompt';
        prompt.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:99997;transform:translateY(120%) scale(0.9);opacity:0;transition:all 0.5s cubic-bezier(0.34,1.56,0.64,1);max-width:320px;';
        prompt.innerHTML = `
            <div style="background:white;border-radius:20px;box-shadow:0 16px 48px rgba(0,0,0,0.14),0 4px 16px rgba(0,0,0,0.08);border:1px solid #e5e7eb;overflow:hidden;">
                <div style="background:linear-gradient(135deg,#1f2937,#374151);padding:14px 16px;display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#f97316,#f59e0b);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;">✅</div>
                        <div>
                            <p style="color:white;font-weight:800;font-size:13px;margin:0;">Đơn hàng đã hoàn thành</p>
                            <p style="color:#9ca3af;font-size:11px;margin:0;">Đơn #${orderNumber}</p>
                        </div>
                    </div>
                    <button onclick="dismissReviewPrompt('${orderId}')" style="color:#6b7280;background:rgba(255,255,255,0.1);border:none;cursor:pointer;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;">✕</button>
                </div>
                <div style="padding:16px;">
                    <div style="display:flex;gap:2px;margin-bottom:10px;">
                        ${[1,2,3,4,5].map(i => `<span style="font-size:22px;cursor:pointer;transition:transform 0.1s;" onmouseover="this.style.transform='scale(1.3)'" onmouseout="this.style.transform='scale(1)'">⭐</span>`).join('')}
                    </div>
                    <p style="font-size:13px;color:#374151;font-weight:600;margin:0 0 6px;">Sản phẩm đến tay bạn rồi?</p>
                    <p style="font-size:12px;color:#9ca3af;margin:0 0 14px;line-height:1.5;">Chia sẻ cảm nhận giúp người mua khác nhé! 💬</p>
                    <div style="display:flex;gap:8px;">
                        <button onclick="dismissReviewPrompt('${orderId}')" style="flex:1;padding:9px;background:#f9fafb;color:#6b7280;font-size:12px;font-weight:600;border:1px solid #e5e7eb;border-radius:10px;cursor:pointer;">Để sau</button>
                        <a href="${orderUrl}" onclick="dismissReviewPrompt('${orderId}')" style="flex:2;text-align:center;padding:9px;background:linear-gradient(135deg,#f97316,#f59e0b);color:white;font-size:12px;font-weight:800;border-radius:10px;text-decoration:none;letter-spacing:0.3px;">⭐ Đánh giá</a>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(prompt);
        requestAnimationFrame(() => {
            setTimeout(() => {
                prompt.style.transform = 'translateY(0) scale(1)';
                prompt.style.opacity = '1';
            }, 500);
        });
    }

    function dismissReviewPrompt(orderId) {
        if(orderId) {
            localStorage.setItem('review_dismissed_' + orderId, 'true');
        }
        const prompt = document.getElementById('review-prompt');
        if (prompt) {
            prompt.style.transform = 'translateY(120%) scale(0.9)';
            prompt.style.opacity = '0';
            setTimeout(() => prompt.remove(), 500);
        }
    }

    // SCRIPT AJAX THÊM VÀO GIỎ HÀNG
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartForms = Array.from(document.querySelectorAll('form[action*="/cart/add"]'))
            .filter(form => !(form.getAttribute('onsubmit') || '').includes('setVariant'));

        addToCartForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (e.submitter && e.submitter.name === 'buy_now') {
                    return; 
                }

                e.preventDefault(); 

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        const badge = document.querySelector('.cart-badge-count');
                        if(badge) {
                            badge.innerText = data.cart_count;
                            badge.classList.remove('animate-pulse');
                            void badge.offsetWidth; 
                            badge.classList.add('animate-pulse');
                        }

                        updateMiniCartUI(data.cart);

                        // 🛒 Tìm thông tin sản phẩm vừa thêm để hiện cart toast đẹp
                        const variantIdInput = this.querySelector('[name="variant_id"]');
                        const variantId = variantIdInput ? variantIdInput.value : null;
                        let itemName = data.message || 'Sản phẩm';
                        let itemImage = null;
                        let itemPrice = '';

                        if (variantId && data.cart && data.cart[variantId]) {
                            const item = data.cart[variantId];
                            itemName = item.name || itemName;
                            itemImage = item.image || null;
                            itemPrice = new Intl.NumberFormat('vi-VN').format(item.price) + 'đ';
                        }

                        showCartToast(itemName, itemImage, itemPrice, data.cart_count);
                    } else {
                        showToast(data.message || 'Có lỗi xảy ra!', 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        window.updateMiniCartUI = function(cartData) {
            const container = document.getElementById('mini-cart-items');
            if(!container) return;

            container.innerHTML = ''; 
            let html = '';

            Object.values(cartData).forEach(item => {
                const priceFormatted = new Intl.NumberFormat('vi-VN').format(item.price) + 'đ';
                
                html += `
                    <div class="flex gap-3 items-center">
                        <img src="${item.image}" class="w-12 h-12 object-cover rounded-lg border">
                        <div class="flex-1 text-sm">
                            <p class="line-clamp-1 font-medium">${item.name}</p>
                            <p class="text-orange-500 font-bold">${priceFormatted}</p>
                            <p class="text-xs text-gray-500">x${item.quantity}</p>
                        </div>
                    </div>
                `;
            });

            if(html === '') {
                html = '<p class="text-center text-gray-400 py-6">Giỏ hàng trống</p>';
            }

            container.innerHTML = html;
        }

        // 🔔 SESSION FLASH NOTIFICATIONS (từ Laravel redirect->with())
        @if(session('success'))
        setTimeout(() => {
            @php $msg = session('success'); @endphp
            const successMsg = @json($msg);

            // Kiểm tra xem có phải đăng ký thành công không
            if (successMsg.includes('Đăng ký') || successMsg.includes('thành công')) {
                showToast(successMsg, 'success', 'Chào mừng bạn đến với Smart Store!');
            } else {
                showToast(successMsg, 'success');
            }
        }, 300);
        @endif

        @if(session('error'))
        setTimeout(() => {
            showToast(@json(session('error')), 'error');
        }, 300);
        @endif

        // 🌟 REVIEW PROMPT (Nhắc nhở khách hàng đánh giá khi đơn hàng đã THÀNH CÔNG)
        @auth('web')
        @php
            // Lấy ra đơn hàng gần nhất có trạng thái "hoàn thành" 
            $latestCompletedOrder = auth('web')->user()->orders()
                ->where('status', 'completed')
                ->latest('updated_at')
                ->first();
                
            $showReview = false;
            if ($latestCompletedOrder && $latestCompletedOrder->updated_at->diffInDays(now()) <= 14) {
                // Kiểm tra xem đơn này đã review hết các sản phẩm chưa
                // 1 đơn có nhiều item, ta đếm review theo item trong đơn đó...
                // (Đơn giản hóa: hiện nếu có đơn trong 14 ngày qua mà chưa bị tắt thủ công)
                $showReview = true;
            }
        @endphp
        
        @if($showReview)
        setTimeout(() => {
            const orderId = @json($latestCompletedOrder->id);
            if(!localStorage.getItem('review_dismissed_' + orderId)) {
                showReviewPrompt(
                    @json($latestCompletedOrder->order_number),
                    @json(route('customer.order.detail', $latestCompletedOrder->id)),
                    orderId
                );
            }
        }, 2000);
        @endif
        @endauth
    });
    </script>

</header>