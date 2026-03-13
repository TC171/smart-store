@extends('admin.layouts.app')

@section('content')

<div class="p-6">
    <h1 class="text-2xl font-bold text-white mb-6">Quản lý thuộc tính sản phẩm</h1>

    {{-- Tabs cho loại --}}
    <div class="mb-6">
        <div class="flex space-x-1 bg-gray-800 p-1 rounded-lg">
            @foreach($types as $key => $label)
                <a href="{{ route('product-attributes.index', ['type' => $key]) }}"
                   class="px-4 py-2 rounded-md text-sm font-medium transition
                          {{ $type == $key ? 'bg-cyan-500 text-black' : 'text-gray-300 hover:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-white">{{ $types[$type] }}</h2>

        <a href="{{ route('product-attributes.create') }}?type={{ $type }}"
           class="bg-cyan-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg font-semibold">
            + Thêm {{ strtolower($types[$type]) }}
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-gray-900 rounded-xl overflow-hidden">
        <table class="w-full text-left text-gray-300">
            <thead class="bg-gray-800 text-gray-400 text-sm">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Giá trị</th>
                    <th class="p-4">Thứ tự</th>
                    <th class="p-4">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attributes as $attribute)
                <tr class="border-t border-gray-800 hover:bg-gray-800">
                    <td class="p-4">{{ $attribute->id }}</td>
                    <td class="p-4">{{ $attribute->value }}</td>
                    <td class="p-4">{{ $attribute->sort_order }}</td>
                    <td class="p-4 flex gap-2">
                        <a href="{{ route('product-attributes.edit', $attribute) }}"
                           class="text-cyan-400 hover:text-cyan-300">Sửa</a>

                        <form action="{{ route('product-attributes.destroy', $attribute) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300"
                                    onclick="return confirm('Xóa thuộc tính này?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="p-4 text-center text-gray-400" colspan="4">
                        Chưa có {{ strtolower($types[$type]) }} nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection