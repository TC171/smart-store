@extends('admin.layouts.app')

@section('content')

<div class="p-6">

<div class="flex justify-between mb-6">

<h1 class="text-2xl font-bold text-white">
Quản lý biến thể
</h1>

<a href="{{ route('admin.variants.create') }}"
class="bg-cyan-500 text-black px-4 py-2 rounded-lg">
+ Thêm biến thể
</a>

</div>


<div class="bg-gray-900 rounded-xl overflow-hidden">

<table class="w-full text-left text-gray-300">

<thead class="bg-gray-800 text-gray-400 text-sm">

<tr>

<th class="p-3">ID</th>
<th class="p-3">Sản phẩm</th>
<th class="p-3">Màu</th>
<th class="p-3">Bộ nhớ</th>
<th class="p-3">RAM</th>
<th class="p-3">Giá</th>
<th class="p-3">Tồn kho</th>
<th class="p-3">SKU</th>
<th class="p-3">Action</th>

</tr>

</thead>

<tbody>

@foreach($variants as $variant)

<tr class="border-t border-gray-800 hover:bg-gray-800">

<td class="p-3">
{{ $variant->id }}
</td>

<td class="p-3">
{{ $variant->product->name ?? 'N/A' }}
</td>

<td class="p-3">
{{ $variant->color }}
</td>

<td class="p-3">
{{ $variant->storage }}
</td>

<td class="p-3">
{{ $variant->ram }}
</td>

<td class="p-3 text-green-400">
{{ number_format($variant->price) }} đ
</td>

<td class="p-3">
{{ $variant->stock }}
</td>

<td class="p-3">
{{ $variant->sku }}
</td>

<td class="p-3 flex gap-2">

<a href="{{ route('admin.variants.edit',$variant->id) }}"
class="text-blue-500 hover:underline">
Sửa
</a>

<form action="{{ route('admin.variants.destroy', $variant->id) }}" 
      method="POST">

    @csrf
    @method('DELETE')

    <button class="text-red-500 hover:underline"
        onclick="return confirm('Bạn có chắc muốn xoá biến thể này?')">

        Xoá

    </button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>


<div class="mt-4">
{{ $variants->links() }}
</div>

</div>

@endsection