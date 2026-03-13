@extends('admin.layouts.app')

@section('content')

<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">
            Quản lý Banner
        </h1>

        <a href="{{ route('banners.create') }}"
            class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
            + Thêm Banner
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-gray-900 rounded-xl overflow-hidden">
        <table class="w-full text-left text-gray-300">
            <thead class="bg-gray-800 text-gray-400 text-sm">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Hình ảnh</th>
                    <th class="p-4">Tiêu đề</th>
                    <th class="p-4">Vị trí</th>
                    <th class="p-4">Sắp xếp</th>
                    <th class="p-4">Trạng thái</th>
                    <th class="p-4">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr class="border-t border-gray-800 hover:bg-gray-800">
                    <td class="p-4">{{ $banner->id }}</td>
                    <td class="p-4">
                        @if($banner->image)
                            <img src="{{ asset('storage/' . $banner->image) }}"
                                 alt="{{ $banner->title }}"
                                 class="w-20 h-12 object-cover rounded">
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="p-4">{{ $banner->title }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 bg-gray-800 rounded text-xs">{{ $banner->position }}</span>
                    </td>
                    <td class="p-4">{{ $banner->sort_order }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $banner->status ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $banner->status ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </td>
                    <td class="p-4 flex gap-2">
                        <a href="{{ route('banners.edit', $banner->id) }}"
                            class="text-cyan-400 hover:text-cyan-300">Sửa</a>

                        <form action="{{ route('banners.destroy', $banner->id) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300"
                                    onclick="return confirm('Xóa banner này?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="p-4 text-center text-gray-400" colspan="7">Chưa có banner nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $banners->links() }}
    </div>

</div>

@endsection
