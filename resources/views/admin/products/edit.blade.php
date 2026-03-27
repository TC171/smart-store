@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold text-white mb-6">Chỉnh sửa sản phẩm</h1>

    <form action="{{ route('admin.products.update', $product->id) }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">
        @csrf
        @method('PUT')

        {{-- Tên sản phẩm --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Tên sản phẩm</label>
            <input type="text"
                name="name"
                value="{{ old('name', $product->name) }}"
                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Danh mục & Thương hiệu --}}
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Danh mục</label>
                <select name="category_id" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Thương hiệu</label>
                <select name="brand_id" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Hiển thị thumbnail cũ nếu có --}}
        @if($product->thumbnail)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-300 mb-2">Hình ảnh hiện tại</label>
            <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="thumbnail" class="w-40 h-40 object-cover rounded-lg">
        </div>
        @endif

        {{-- Upload ảnh mới --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Cập nhật hình ảnh</label>
            <input type="file" name="image" class="w-full text-gray-300 file:bg-cyan-500 file:text-black file:px-4 file:py-2 file:rounded-lg file:border-0">
            @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- ẢNH PHỤ HIỆN TẠI --}}
@if($product->images && $product->images->count())
<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">Ảnh chi tiết hiện tại</label>

    <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
        @foreach($product->images as $img)
<div class="relative">
    <img src="{{ asset('storage/'.$img->image) }}"
         class="w-full h-24 object-cover rounded-lg border border-gray-700">

    {{-- nút xoá --}}
    <button type="button"
        onclick="removeOldImage(this, {{ $img->id }})"
        class="absolute top-1 right-1 bg-red-500 text-white text-xs px-2 py-1 rounded">
        X
    </button>
 

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

{{-- UPLOAD THÊM ẢNH --}}
<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">Thêm ảnh mới</label>

    <input type="file"
        name="images[]"
        multiple
        accept="image/*"
        onchange="previewImages(event)"
        class="w-full text-gray-300 file:bg-cyan-500 file:text-black file:px-4 file:py-2 file:rounded-lg file:border-0">

    @error('images.*')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror

    {{-- Preview ảnh mới --}}
    <div id="preview-images" class="grid grid-cols-3 md:grid-cols-6 gap-4 mt-4"></div>
</div>

        {{-- Mô tả ngắn --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Mô tả ngắn</label>
            <textarea name="short_description" rows="3" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">{{ old('short_description', $product->short_description) }}</textarea>
        </div>
        

        {{-- Mô tả chi tiết --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Mô tả chi tiết</label>
               {{-- CKEDITOR --}}
        <textarea id="description" name="description">
            {{ $product->description }}
        </textarea>
        </div>

        {{-- Thông tin kỹ thuật --}}
        <div class="grid grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Bảo hành (tháng)</label>
                <input type="number" name="warranty_months" value="{{ old('warranty_months', $product->warranty_months) }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Cân nặng (kg)</label>
                <input type="number" step="0.01" name="weight" value="{{ old('weight', $product->weight) }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Chiều dài (cm)</label>
                <input type="number" step="0.01" name="length" value="{{ old('length', $product->length) }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Chiều rộng (cm)</label>
                <input type="number" step="0.01" name="width" value="{{ old('width', $product->width) }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Chiều cao (cm)</label>
                <input type="number" step="0.01" name="height" value="{{ old('height', $product->height) }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Trạng thái</label>
                <select name="status" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="1" @selected(old('status', $product->status)==1)>Đang hoạt động</option>
                    <option value="0" @selected(old('status', $product->status)==0)>Ngừng kinh doanh</option>
                </select>
            </div>
        </div>

        {{-- Meta --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Meta title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Meta description</label>
            <textarea name="meta_description" rows="3" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">{{ old('meta_description', $product->meta_description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Meta keywords</label>
            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
        </div>
        
<div id="deleted-images"></div>
        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
                Cập nhật sản phẩm
            </button>
        </div>

    </form>
</div>
{{-- CKEDITOR --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
/* ========= CKEDITOR UPLOAD ========= */
class UploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file.then(file => new Promise((resolve, reject) => {

            let data = new FormData();
            data.append('upload', file);

            fetch("{{ route('admin.upload.image') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: data
            })
            .then(res => res.json())
            .then(res => {
                resolve({
                    default: res.url
                });
            })
            .catch(err => reject(err));
        }));
    }

    abort() {}
}

function MyUploadPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = loader => {
        return new UploadAdapter(loader);
    };
}

ClassicEditor.create(document.querySelector('#description'), {
    extraPlugins: [MyUploadPlugin],
    image: {
        toolbar: [
            'imageTextAlternative',
            'imageStyle:full',
            'imageStyle:side'
        ]
    }
})
.catch(error => console.log(error));
</script>
@endsection
<script>
let selectedFiles = new DataTransfer();

function previewImages(event) {
    const preview = document.getElementById('preview-images');
    const input = event.target;

    Array.from(input.files).forEach(file => {
        selectedFiles.items.add(file);

        const reader = new FileReader();

        reader.onload = function(e) {
            const div = document.createElement('div');
            div.classList.add('relative');

            div.innerHTML = `
                <img src="${e.target.result}" 
                     class="w-full h-24 object-cover rounded-lg border border-gray-700">

                <button type="button"
                        class="absolute top-1 right-1 bg-red-500 text-white text-xs px-2 py-1 rounded"
                        onclick="removeImage(this, '${file.name}')">
                    X
                </button>
            `;

            preview.appendChild(div);
        };

        reader.readAsDataURL(file);
    });

    input.files = selectedFiles.files;
}

function removeImage(button, fileName) {
    const dt = new DataTransfer();

    Array.from(selectedFiles.files).forEach(file => {
        if (file.name !== fileName) {
            dt.items.add(file);
        }
    });

    selectedFiles = dt;

    document.querySelector('input[name="images[]"]').files = selectedFiles.files;

    button.parentElement.remove();
}
</script>
<script>
function removeOldImage(button, imageId) {
    // thêm input hidden
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'deleted_images[]';
    input.value = imageId;

    document.getElementById('deleted-images').appendChild(input);

    // xoá UI
    button.parentElement.remove();
}
</script>