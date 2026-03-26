<table class="w-full text-left text-gray-300">

    <thead class="bg-gray-800 text-gray-200">
        <tr>
            <th class="p-3">ID</th>
            <th class="p-3">Hình ảnh</th>
            <th class="p-3">Tên danh mục</th>
            <th class="p-3">Slug</th>
            <th class="p-3">Danh mục cha</th>
            <th class="p-3">Trạng thái</th>
            <th class="p-3 text-center">Hành động</th>
        </tr>
    </thead>

    <tbody>
        @foreach($categories as $category)
        <tr class="border-b border-gray-800 hover:bg-gray-800">

            <td class="p-3">{{ $category->id }}</td>

            <td class="p-3">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}"
                         alt="{{ $category->name }}"
                         class="w-12 h-12 object-cover rounded">
                @else
                    <span class="text-gray-400 text-sm">Không có</span>
                @endif
            </td>

            <td class="p-3 font-semibold text-white">
                {{ $category->name }}
            </td>

            <td class="p-3">
                {{ $category->slug }}
            </td>

            <td class="p-3">
                {{ $category->parent->name ?? 'Danh mục gốc' }}
            </td>

            <td class="p-3">
                @if($category->status)
                    <span class="bg-green-500 text-black px-2 py-1 rounded text-sm">
                        Hiển thị
                    </span>
                @else
                    <span class="bg-red-500 text-black px-2 py-1 rounded text-sm">
                        Ẩn
                    </span>
                @endif
            </td>

            <td class="p-3 flex justify-center gap-2">

                <a href="{{ route('admin.categories.edit',$category->id) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-black px-3 py-1 rounded">
                    Sửa
                </a>

                <form action="{{ route('admin.categories.destroy',$category->id) }}"
                      method="POST"
                      onsubmit="return confirm('Xóa danh mục này?')">

                    @csrf
                    @method('DELETE')

                    <button class="bg-red-500 hover:bg-red-600 text-black px-3 py-1 rounded">
                        Xóa
                    </button>

                </form>

            </td>

        </tr>
        @endforeach
    </tbody>

</table>