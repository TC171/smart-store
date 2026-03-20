@extends('admin.layouts.app')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">
            Quản lý thương hiệu
        </h1>

        <a href="{{ route('brands.create') }}"
            class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
            + Thêm thương hiệu
        </a>
    </div>

    {{-- Filter Status --}}
    <div class="mb-6 bg-gray-900 p-4 rounded-lg">
        <form method="GET" class="flex gap-4">
            <select name="status" class="bg-gray-800 text-white rounded px-4 py-2">
                <option value="">Tất cả</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tắt</option>
            </select>
            <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-black px-4 py-2 rounded">
                Lọc
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-gray-900 rounded-xl overflow-hidden">
        <table class="w-full text-left text-gray-300">
            <thead class="bg-gray-800 text-gray-400 text-sm">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Logo</th>
                    <th class="p-4">Tên</th>
                    <th class="p-4">Quốc gia</th>
                    <th class="p-4">Website</th>
                    <th class="p-4">Trạng thái</th>
                    <th class="p-4">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr class="border-t border-gray-800 hover:bg-gray-800">
                    <td class="p-4">{{ $brand->id }}</td>
                    <td class="p-4">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                 alt="{{ $brand->name }}"
                                 class="w-10 h-10 object-cover rounded">
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="p-4">{{ $brand->name }}</td>
                    <td class="p-4">{{ $brand->country ?? '-' }}</td>
                    <td class="p-4">
                        @if($brand->website)
                            <a href="{{ $brand->website }}" target="_blank" class="text-cyan-400 hover:underline">
                                {{ $brand->website }}
                            </a>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $brand->status ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $brand->status ? 'Kích hoạt' : 'Tắt' }}
                        </span>
                    </td>
                    <td class="p-4 flex gap-2">
                        <a href="{{ route('brands.edit', $brand->id) }}"
                            class="text-cyan-400 hover:text-cyan-300">Sửa</a>

                        <form action="{{ route('brands.destroy', $brand->id) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300"
                                    onclick="return confirm('Xóa thương hiệu này?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="p-4 text-center text-gray-400" colspan="7">Chưa có thương hiệu nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $brands->links() }}
    </div>

</div>

@endsection
