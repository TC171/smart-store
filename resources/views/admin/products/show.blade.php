@extends('admin.layouts.app')

@section('content')
<div class="p-6 text-white">

    <h1 class="text-3xl font-bold text-cyan-400 mb-6">
        Chi tiết sản phẩm
    </h1>

    {{-- PRODUCT MAIN INFO --}}
    <div class="grid grid-cols-3 gap-6">

        {{-- IMAGE THUMBNAIL --}}
        <div class="bg-gray-900 border border-cyan-500/30 rounded-xl p-4">
            @if($product->thumbnail)
                <img src="{{ asset('storage/'.$product->thumbnail) }}"
                     class="rounded-lg w-full object-cover">
            @endif
        </div>

        {{-- INFO --}}
        <div class="col-span-2 bg-gray-900 border border-cyan-500/30 rounded-xl p-6 space-y-4">
            <h2 class="text-2xl font-bold">{{ $product->name }}</h2>

            <div class="grid grid-cols-2 gap-4 text-gray-300">
                <div>
                    <span class="text-gray-500">Danh mục</span>
                    <div class="font-semibold">{{ $product->category->name ?? '-' }}</div>
                </div>
                <div>
                    <span class="text-gray-500">Thương hiệu</span>
                    <div class="font-semibold">{{ $product->brand->name ?? '-' }}</div>
                </div>
                <div>
                    <span class="text-gray-500">Trạng thái</span>
                    @if($product->status == 1)
                        <span class="text-green-400 font-semibold">Đang bán</span>
                    @else
                        <span class="text-red-400 font-semibold">Ngừng bán</span>
                    @endif
                </div>
                <div>
                    <span class="text-gray-500">Tổng tồn kho</span>
                    <div class="font-semibold">{{ $totalStock }}</div>
                </div>
            </div>

            {{-- Mô tả --}}
            <div class="mt-4">
                <h3 class="text-cyan-400 font-semibold mb-2">Mô tả ngắn</h3>
                <p class="text-gray-300">{{ $product->short_description }}</p>
            </div>

            <div class="mt-2">
                <h3 class="text-cyan-400 font-semibold mb-2">Mô tả chi tiết</h3>

                <div class="text-gray-300 ck-content">
                    {!! $product->description !!}
                </div>
            </div>

            {{-- Thông số --}}
            <div class="grid grid-cols-3 gap-4 mt-4 text-gray-300">
                <div>
                    <span class="text-gray-500">Bảo hành</span>
                    <div class="font-semibold">{{ $product->warranty_months }} tháng</div>
                </div>
                <div>
                    <span class="text-gray-500">Kích thước</span>
                    <div class="font-semibold">
                        {{ $product->length }} x {{ $product->width }} x {{ $product->height }} cm
                    </div>
                </div>
                <div>
                    <span class="text-gray-500">Cân nặng</span>
                    <div class="font-semibold">{{ $product->weight }} kg</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔥 GALLERY ẢNH CHI TIẾT --}}
    @if($product->images && $product->images->count())
    <div class="mt-8">
        <h2 class="text-xl font-bold text-cyan-400 mb-4">
            Hình ảnh chi tiết sản phẩm
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($product->images as $img)
                <div class="relative group">
                    <img src="{{ asset('storage/'.$img->image) }}"
                         class="w-full h-28 object-cover rounded-lg border border-gray-700 hover:scale-105 transition">

                    {{-- badge main --}}
                    @if($img->is_main)
                        <span class="absolute top-1 left-1 bg-green-500 text-black text-xs px-2 py-1 rounded">
                            Main
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- VARIANTS --}}
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-cyan-400">Danh sách biến thể</h2>
            <a href="{{ route('admin.variants.create') }}?product_id={{ $product->id }}"
               class="bg-cyan-500 hover:bg-cyan-400 text-black px-4 py-2 rounded-lg font-semibold">
               + Thêm biến thể
            </a>
        </div>

        <table class="w-full text-sm border border-gray-700">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="p-3">SKU</th>
                    <th class="p-3">Ảnh</th>
                    <th class="p-3">Màu</th>
                    <th class="p-3">RAM</th>
                    <th class="p-3">Storage</th>
                    <th class="p-3">Giá</th>
                    <th class="p-3">Tồn kho</th>
                    <th class="p-3">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach($product->variants as $variant)
                <tr class="border-t border-gray-700 hover:bg-gray-800">
                    <td class="p-3">{{ $variant->sku }}</td>

                    <td class="p-3">
                        @if($variant->image)
                            <img src="{{ asset('storage/'.$variant->image) }}"
                                 class="w-16 h-16 object-cover rounded">
                        @endif
                    </td>

                    <td class="p-3">{{ $variant->color }}</td>
                    <td class="p-3">{{ $variant->ram }}</td>
                    <td class="p-3">{{ $variant->storage }}</td>

                    <td class="p-3">
                        @if($variant->sale_price)
                            <span class="text-red-400 font-semibold">
                                {{ number_format($variant->sale_price,0,',','.') }} ₫
                            </span>
                            <div class="line-through text-gray-500 text-xs">
                                {{ number_format($variant->price,0,',','.') }} ₫
                            </div>
                        @else
                            {{ number_format($variant->price,0,',','.') }} ₫
                        @endif
                    </td>

                    <td class="p-3 text-center">{{ $variant->stock }}</td>

                    <td class="p-3 flex gap-3">
                        <a href="{{ route('admin.variants.edit',$variant->id) }}"
                           class="text-yellow-400">Sửa</a>

                        <form action="{{ route('admin.variants.destroy',$variant->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Xoá biến thể?')" class="text-red-400">
                                Xoá
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-3 gap-4 mt-6">
        <div class="bg-gray-900 border border-cyan-500/30 p-4 rounded-xl">
            <div class="text-gray-400 text-sm">Giá thấp nhất</div>
            <div class="text-xl font-bold text-green-400">
                {{ number_format($minPrice,0,',','.') }} ₫
            </div>
        </div>

        <div class="bg-gray-900 border border-cyan-500/30 p-4 rounded-xl">
            <div class="text-gray-400 text-sm">Giá cao nhất</div>
            <div class="text-xl font-bold text-yellow-400">
                {{ number_format($maxPrice,0,',','.') }} ₫
            </div>
        </div>

        <div class="bg-gray-900 border border-cyan-500/30 p-4 rounded-xl">
            <div class="text-gray-400 text-sm">Tổng tồn kho</div>
            <div class="text-xl font-bold text-cyan-400">
                {{ $totalStock }}
            </div>
        </div>
    </div>

</div>
@endsection