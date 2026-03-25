<div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden">

    {{-- TOP BAR --}}
    <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-gray-700">

        {{-- SEARCH --}}
        <form method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="🔍 Tìm sản phẩm..."
                class="bg-gray-800 text-white px-4 py-2 rounded-lg border border-gray-700 
                focus:ring-2 focus:ring-indigo-500 w-full md:w-64">

            <button class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg text-white">
                Tìm
            </button>
        </form>

        {{-- FILTER --}}
        <form method="GET" class="flex gap-2 flex-wrap">

            <select name="category_id" onchange="this.form.submit()"
                class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700">
                <option value="">Danh mục</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>

            <select name="brand_id" onchange="this.form.submit()"
                class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700">
                <option value="">Thương hiệu</option>
                @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()"
                class="bg-gray-800 text-white px-3 py-2 rounded border border-gray-700">
                <option value="">Trạng thái</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang bán</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ngừng</option>
            </select>

        </form>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-white text-sm">

            {{-- HEADER --}}
            <thead class="bg-gray-800 text-gray-300 uppercase text-xs sticky top-0 z-10">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Sản phẩm</th>
                    <th class="p-3">Danh mục</th>
                    <th class="p-3">Brand</th>
                    <th class="p-3">
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort' => 'price',
                            'direction' => request('direction') == 'asc' ? 'desc' : 'asc'
                        ]) }}">
                            Giá 💸
                        </a>
                    </th>
                    <th class="p-3 text-center">Kho</th>
                    <th class="p-3 text-center">Trạng thái</th>
                    <th class="p-3 text-center">Hành động</th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody>
                @forelse($products as $product)

                @php
                // Lấy biến thể đầu tiên (nếu có)
                $variant = $product->variants->first();
                $price = $variant?->price ?? 0;
                $sale = $variant?->sale_price ?? 0;
                $stock = $product->variants->sum('stock') ?? 0;
                @endphp

                <tr class="border-b border-gray-700 hover:bg-gray-800/70 transition">

                    <td class="p-3 text-gray-400">#{{ $product->id }}</td>

                    {{-- PRODUCT --}}
                    <td class="p-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->thumbnail ? asset('storage/'.$product->thumbnail) : 'https://via.placeholder.com/80' }}"
                                class="w-14 h-14 rounded-lg object-cover border border-gray-700">

                            <div>
                                <div class="font-semibold text-white">
                                    {{ $product->name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $product->variants->count() }} biến thể
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="p-3">{{ $product->category->name ?? '-' }}</td>
                    <td class="p-3">{{ $product->brand->name ?? '-' }}</td>

                    {{-- PRICE --}}
                    <td class="p-3">
                        @if($sale && $sale > 0)
                        <div class="text-red-400 font-semibold text-sm">
                            {{ number_format($sale, 0, ',', '.') }} ₫
                        </div>
                        <div class="text-gray-500 line-through text-xs">
                            {{ number_format($price, 0, ',', '.') }} ₫
                        </div>
                        @else
                        <div class="font-semibold text-white">
                            {{ number_format($price, 0, ',', '.') }} ₫
                        </div>
                        @endif
                    </td>

                    {{-- STOCK --}}
                    <td class="text-center font-semibold">
                        @if($stock > 0)
                        <span class="text-green-400">{{ $stock }}</span>
                        @else
                        <span class="text-red-400">Hết hàng</span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td class="text-center">
                        @if($product->status)
                        <span class="px-3 py-1 text-xs rounded-full bg-green-500/20 text-green-400">
                            ● Đang bán
                        </span>
                        @else
                        <span class="px-3 py-1 text-xs rounded-full bg-red-500/20 text-red-400">
                            ● Ngừng
                        </span>
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td class="p-3">
                        <div class="flex justify-center gap-2">

                            <button onclick="toggleStatus({{ $product->id }}, this)"
                                class="bg-gray-700 hover:bg-gray-600 p-2 rounded-lg">
                                🔄
                            </button>

                            <a href="{{ route('admin.products.show', $product->id) }}"
                                class="bg-gray-700 hover:bg-green-600 p-2 rounded-lg">
                                👁
                            </a>

                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                class="bg-gray-700 hover:bg-yellow-500 p-2 rounded-lg">
                                ✏️
                            </a>

                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Xoá sản phẩm?')"
                                    class="bg-gray-700 hover:bg-red-600 p-2 rounded-lg">
                                    🗑
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center p-6 text-gray-400">
                        Không có sản phẩm
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="p-4 border-t border-gray-700">
        {{ $products->withQueryString()->links() }}
    </div>

</div>