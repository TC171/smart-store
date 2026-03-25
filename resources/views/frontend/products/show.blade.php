@extends('frontend.layouts.app')

@section('content')

<div class="max-w-4xl mx-auto p-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Ảnh sản phẩm -->
        <div>
            @if($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                     class="w-full h-96 object-cover rounded-lg shadow-lg">
            @else
                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">Không có ảnh</span>
                </div>
            @endif
        </div>

        <!-- Thông tin sản phẩm -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

            <div class="mb-4">
                <span class="text-2xl font-bold text-red-600">
                    {{ number_format($product->variants->min('price')) }}đ
                </span>
                @if($product->variants->min('price') != $product->variants->max('price'))
                    <span class="text-lg text-gray-500 ml-2">
                        - {{ number_format($product->variants->max('price')) }}đ
                    </span>
                @endif
            </div>

            <div class="mb-6">
                <h3 class="font-semibold mb-2">Mô tả:</h3>
                <div class="text-gray-700">
                    {{ $product->description ?? 'Chưa có mô tả' }}
                </div>
            </div>

            <button onclick="openVariantModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                Thêm vào giỏ hàng
            </button>
        </div>

    </div>

</div>

@endsection


<div id="variantModal" style="display:none; position:fixed; top:20%; left:35%; background:#fff; padding:20px; border:1px solid #ccc;">

    <h3>Chọn biến thể</h3>

    <form method="POST" action="/cart/add">
        @csrf

        <select name="variant_id" required>
            <option value="">-- Chọn --</option>
            @foreach($product->variants as $variant)
                <option value="{{ $variant->id }}">
                    {{ trim(($variant->color ?? '') . ' ' . ($variant->storage ?? '') . ' ' . ($variant->ram ?? '')) }} - {{ number_format($variant->price) }}đ
                </option>
            @endforeach
        </select>

        <br><br>

        <button type="submit">Xác nhận</button>
        <button type="button" onclick="closeVariantModal()">Đóng</button>
    </form>
</div>

<script>
function openVariantModal() {
    document.getElementById('variantModal').style.display = 'block';
}

function closeVariantModal() {
    document.getElementById('variantModal').style.display = 'none';
}
</script>
