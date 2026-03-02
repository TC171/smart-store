<table class="w-full text-white">
    <thead class="bg-gray-800 text-gray-300 text-sm uppercase">
        <tr>
            <th class="p-3">ID</th>
            <th class="p-3">Hình ảnh</th>
            <th class="p-3">Tên sản phẩm</th>
            <th>
                <form method="GET" id="filterCategory">
                    <select name="category_id"
                        onchange="this.form.submit()"
                        class="bg-gray-900 text-white 
           border border-gray-600 
           rounded px-3 py-1 text-sm
           focus:outline-none focus:ring-2 focus:ring-cyan-400
           hover:bg-gray-800 transition">
                        <option value="">Danh mục</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>

                    {{-- Giữ các filter khác nếu có --}}
                    <input type="hidden" name="brand_id" value="{{ request('brand_id') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                </form>
            </th>
            <th>
                <form method="GET" id="filterBrand">
                    <select name="brand_id"
                        onchange="this.form.submit()"
                        class="bg-gray-900 text-white 
           border border-gray-600 
           rounded px-3 py-1 text-sm
           focus:outline-none focus:ring-2 focus:ring-cyan-400
           hover:bg-gray-800 transition">
                        <option value="">Thương hiệu</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}"
                            {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                        @endforeach
                    </select>

                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                </form>
            </th>
            <th class="p-3 cursor-pointer" id="sortPrice">
                Giá <span id="priceIcon">⇅</span>
            </th>
            <th class="p-3">Tồn kho</th>
            {{-- STATUS FILTER --}}
            <th>
                <form method="GET" id="filterStatus">
                    <select name="status"
                        onchange="this.form.submit()"
                        class="bg-gray-900 text-white 
           border border-gray-600 
           rounded px-3 py-1 text-sm
           focus:outline-none focus:ring-2 focus:ring-cyan-400
           hover:bg-gray-800 transition">
                        <option value="">Trạng thái</option>
                        <option value="0"
                            {{ request('status') == '0' ? 'selected' : '' }}>
                            Đang bán
                        </option>
                        <option value="1"
                            {{ request('status') == '1' ? 'selected' : '' }}>
                            Ngừng bán
                        </option>
                    </select>

                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    <input type="hidden" name="brand_id" value="{{ request('brand_id') }}">
                </form>
            </th>
            <th class="p-3">Hành động</th>
        </tr>
    </thead>

    <tbody>
        @foreach($products as $product)
        <tr class="border-b border-gray-700 hover:bg-gray-800 transition">

            <td class="p-3">{{ $product->id }}</td>

            <td>
                @if($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                    class="w-24 h-24 object-cover rounded-lg border">
                @else
                Không có ảnh
                @endif
            </td>

            <td class="p-3 font-semibold">
                {{ $product->name }}
            </td>

            <td class="p-3">
                {{ $product->category->name ?? '-' }}
            </td>

            <td class="p-3">
                {{ $product->brand->name ?? '-' }}
            </td>

            @php
            $variant = $product->variants->first();
            @endphp

            <td>
                @if($variant)
                {{ number_format($variant->sale_price ?? $variant->price, 0, ',', '.') }} ₫
                @else
                0 ₫
                @endif
            </td>

            <td class="text-center">
                {{ $product->variants->sum('stock') }}
            </td>

            <td class="p-3">
                @if($product->status == 'active')
                <span class="px-2 py-1 text-xs bg-green-500/20 text-green-400 rounded">
                    Đang bán
                </span>
                @else
                <span class="px-2 py-1 text-xs bg-red-500/20 text-red-400 rounded">
                    Ngừng bán
                </span>
                @endif
            </td>

            <td class="p-3 flex gap-2">
                <a href="{{ route('products.edit', $product->id) }}"
                    class="text-yellow-400 hover:underline">
                    Sửa
                </a>

                <form action="{{ route('products.destroy', $product->id) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-500 hover:underline"
                        onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?')">
                        Xoá
                    </button>
                </form>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>