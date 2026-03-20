@extends('frontend.layouts.app')

@section('content')

<h2>Chi tiết sản phẩm</h2>

<div style="border:1px solid #ccc; padding:20px;">
    <h3>{{ $product->name }}</h3>

    <p>Giá: {{ number_format($product->price) }}đ</p>

    <p>Mô tả:</p>
    <div>
        {{ $product->description }}
    </div>
    <button onclick="openVariantModal()">
    Thêm vào giỏ hàng
</button>
</form>
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
                    {{ $variant->name }} - {{ number_format($variant->price) }}đ
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
