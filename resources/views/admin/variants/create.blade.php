@extends('admin.layouts.app')

@section('content')

<div class="p-6 max-w-3xl mx-auto">

<h1 class="text-2xl font-bold text-white mb-6">
Thêm biến thể sản phẩm
</h1>

<form action="{{ route('admin.variants.store') }}" method="POST"
class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

@csrf

<!-- Product -->

<div>

<label class="block text-gray-300 mb-2">
Sản phẩm
</label>

<select name="product_id"
class="w-full bg-gray-800 border border-gray-700
text-white rounded-lg px-4 py-2">

@foreach($products as $product)

<option value="{{ $product->id }}"
    {{ isset($selectedProduct) && $selectedProduct->id == $product->id ? 'selected' : '' }}>
{{ $product->name }}
</option>

@endforeach

</select>

</div>

<!-- Color -->

<div>

<label class="block text-gray-300 mb-2">
Màu sắc <span class="text-red-500">*</span>
<a href="{{ route('admin.product-attributes.index', ['type' => 'color']) }}"
   class="text-xs text-cyan-400 hover:text-cyan-300 underline ml-2">
   Quản lý
</a>
</label>

<select name="colors[]"
multiple
id="colors"
class="w-full bg-gray-800 border border-gray-700
text-white rounded-lg px-4 py-2 min-h-[100px]">

@foreach($colors as $color)
<option value="{{ $color }}">{{ $color }}</option>
@endforeach

</select>

<p class="text-gray-400 text-sm mt-1">
Chọn nhiều màu sắc (Ctrl+click)
</p>

</div>

<!-- Storage -->

<div>

<label class="block text-gray-300 mb-2">
Bộ nhớ <span class="text-red-500">*</span>
<a href="{{ route('admin.product-attributes.index', ['type' => 'storage']) }}"
   class="text-xs text-cyan-400 hover:text-cyan-300 underline ml-2">
   Quản lý
</a>
</label>

<select name="storages[]"
multiple
id="storages"
class="w-full bg-gray-800 border border-gray-700
text-white rounded-lg px-4 py-2 min-h-[100px]">

@foreach($storages as $storage)
<option value="{{ $storage }}">{{ $storage }}</option>
@endforeach

</select>

<p class="text-gray-400 text-sm mt-1">
Chọn nhiều dung lượng bộ nhớ (Ctrl+click)
</p>

</div>

<!-- RAM -->

<div>

<label class="block text-gray-300 mb-2">
RAM <span class="text-red-500">*</span>
<a href="{{ route('admin.product-attributes.index', ['type' => 'ram']) }}"
   class="text-xs text-cyan-400 hover:text-cyan-300 underline ml-2">
   Quản lý
</a>
</label>

<select name="rams[]"
multiple
id="rams"
class="w-full bg-gray-800 border border-gray-700
text-white rounded-lg px-4 py-2 min-h-[100px]">

@foreach($rams as $ram)
<option value="{{ $ram }}">{{ $ram }}</option>
@endforeach

</select>

<p class="text-gray-400 text-sm mt-1">
Chọn nhiều dung lượng RAM (Ctrl+click)
</p>

</div>

<!-- Generate Combinations Button -->
<div>
    <button type="button"
            id="generateBtn"
            class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
        Tạo bảng biến thể
    </button>
</div>

<!-- Variants Table (hidden initially) -->
<div id="variantsTable" class="hidden mt-6">
    <h3 class="text-lg font-semibold text-white mb-4">Thiết lập giá và tồn kho cho từng biến thể</h3>
    <div class="overflow-x-auto">
        <table class="w-full bg-gray-800 rounded-lg overflow-hidden">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-300">Biến thể</th>
                    <th class="px-4 py-3 text-left text-gray-300">Giá</th>
                    <th class="px-4 py-3 text-left text-gray-300">Tồn kho</th>
                    <th class="px-4 py-3 text-left text-gray-300">Hình ảnh</th>
                </tr>
            </thead>
            <tbody id="variantsBody" class="text-white">
                <!-- Variants will be generated here -->
            </tbody>
        </table>
    </div>
</div>



<!-- Button -->

<div class="flex justify-end">

<button type="submit"
class="bg-cyan-500 hover:bg-cyan-600
text-black px-6 py-2 rounded-lg font-semibold">

Tạo tất cả biến thể

</button>

</div>

</form>

</div>

<script>
document.getElementById('generateBtn').addEventListener('click', function() {
    const colors = Array.from(document.getElementById('colors').selectedOptions).map(option => option.value);
    const storages = Array.from(document.getElementById('storages').selectedOptions).map(option => option.value);
    const rams = Array.from(document.getElementById('rams').selectedOptions).map(option => option.value);

    if (colors.length === 0 || storages.length === 0 || rams.length === 0) {
        alert('Vui lòng chọn ít nhất một màu sắc, bộ nhớ và RAM');
        return;
    }

    const variantsBody = document.getElementById('variantsBody');
    variantsBody.innerHTML = '';

    let index = 0;
    colors.forEach(color => {
        storages.forEach(storage => {
            rams.forEach(ram => {
                const row = document.createElement('tr');
                row.className = 'border-t border-gray-700';

                row.innerHTML = `
                    <td class="px-4 py-3">
                        ${color} - ${storage} - ${ram}
                        <input type="hidden" name="variants[${index}][color]" value="${color}">
                        <input type="hidden" name="variants[${index}][storage]" value="${storage}">
                        <input type="hidden" name="variants[${index}][ram]" value="${ram}">
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="variants[${index}][price]" min="0" step="0.01"
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded px-3 py-2"
                               placeholder="0" required>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="variants[${index}][stock]" min="0"
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded px-3 py-2"
                               placeholder="0" required>
                    </td>
                    <td class="px-4 py-3">
                        <input type="file" name="variants[${index}][image]" accept="image/*"
                               class="w-full text-gray-300 file:bg-gray-600 file:text-white file:px-3 file:py-1 file:rounded file:border-0">
                    </td>
                `;

                variantsBody.appendChild(row);
                index++;
            });
        });
    });

    document.getElementById('variantsTable').classList.remove('hidden');
});
</script>

@endsection