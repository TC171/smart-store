@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Quản lý bài viết (Tin Tức)</h1>
        <a href="{{ route('admin.posts.create') }}" class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">+ Thêm bài viết</a>
    </div>

    <div class="bg-gray-900 rounded-xl overflow-hidden">
        <table class="w-full text-left text-gray-300">
            <thead class="bg-gray-800 text-gray-400 text-sm">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4 w-16">Ảnh</th>
                    <th class="p-4 w-1/3">Tiêu đề</th>
                    <th class="p-4">Danh mục</th>
                    <th class="p-4">Trạng thái</th>
                    <th class="p-4">Ngày đăng</th>
                    <th class="p-4">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr class="border-t border-gray-800 hover:bg-gray-800">
                    <td class="p-4">{{ $post->id }}</td>
                    <td class="p-4"><img src="{{ $post->image ? asset('storage/'.$post->image) : asset('images/no-image.jpg') }}" class="w-12 h-12 object-cover rounded"></td>
                    <td class="p-4 font-semibold text-white">
                        {{ \Illuminate\Support\Str::limit($post->title, 50) }}
                        @if($post->is_featured)<span class="text-[10px] bg-yellow-500 text-black px-2 rounded-full ml-2">HOT</span>@endif
                    </td>
                    <td class="p-4">{{ $post->category->name ?? '-' }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $post->status ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">{{ $post->status ? 'Kích hoạt' : 'Tắt' }}</span>
                    </td>
                    <td class="p-4 text-sm">{{ $post->published_at ? $post->published_at->format('d/m/Y') : '' }}</td>
                    <td class="p-4 flex gap-2">
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="text-cyan-400 hover:text-cyan-300">Sửa</a>
                        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Xóa bài này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td class="p-4 text-center text-gray-400" colspan="7">Chưa có bài viết nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $posts->links() }}</div>
</div>
@endsection
