@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold text-white mb-6">Thêm sản phẩm</h1>

    <form action="{{ route('admin.products.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">
        @csrf

        {{-- Tên sản phẩm --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Tên sản phẩm</label>
            <input type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Danh mục & Thương hiệu --}}
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Danh mục</label>
                <select name="category_id" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Thương hiệu</label>
                <select name="brand_id" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Mô tả ngắn --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Mô tả ngắn</label>
            <textarea name="short_description" rows="3" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">{{ old('short_description') }}</textarea>
        </div>

        {{-- Mô tả chi tiết --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Mô tả chi tiết</label>
                {{-- MÔ TẢ CHI TIẾT (CKEDITOR) --}}
        <textarea id="description" name="description"
            class="w-full bg-gray-800 text-white p-2 rounded"></textarea>
        </div>

        {{-- Thông tin kỹ thuật --}}
        <div class="grid grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Bảo hành (tháng)</label>
                <input type="number" name="warranty_months" value="{{ old('warranty_months', 12) }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Cân nặng (kg)</label>
                <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Chiều dài (cm)</label>
                <input type="number" step="0.01" name="length" value="{{ old('length') }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Chiều rộng (cm)</label>
                <input type="number" step="0.01" name="width" value="{{ old('width') }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Chiều cao (cm)</label>
                <input type="number" step="0.01" name="height" value="{{ old('height') }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Trạng thái</label>
                <select name="status" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
                    <option value="1" @selected(old('status',1)==1)>Đang hoạt động</option>
                    <option value="0" @selected(old('status')==0)>Ngừng kinh doanh</option>
                </select>
            </div>
        </div>

        {{-- Hình ảnh --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Hình ảnh</label>
            <input type="file" name="image" class="w-full text-gray-300 file:bg-cyan-500 file:text-black file:px-4 file:py-2 file:rounded-lg file:border-0">
            @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

{{-- Hình ảnh chi tiết --}}
<div>
    <label class="block text-sm font-medium text-gray-300 mb-2">Hình ảnh chi tiết sản phẩm</label>

    <input type="file"
        name="images[]"
        multiple
        accept="image/*"
        onchange="previewImages(event)"
        class="w-full text-gray-300 file:bg-cyan-500 file:text-black file:px-4 file:py-2 file:rounded-lg file:border-0">

    @error('images.*')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror

    {{-- Preview --}}
    <div id="preview-images" class="grid grid-cols-3 md:grid-cols-6 gap-4 mt-4"></div>
</div>

        {{-- Meta --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Meta title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Meta description</label>
            <textarea name="meta_description" rows="3" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">{{ old('meta_description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Meta keywords</label>
            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-cyan-500">
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded-lg font-semibold">
                Lưu sản phẩm
            </button>
        </div>

    </form>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</div>
<script>
class UploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file.then(file => new Promise((resolve, reject) => {

            const data = new FormData();
            data.append('upload', file);

            fetch("{{ route('admin.upload.image') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: data
            })
            .then(response => response.json())
            .then(result => {
                resolve({
                    default: result.url
                });
            })
            .catch(error => reject(error));
        }));
    }

    abort() {}
}

function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new UploadAdapter(loader);
    };
}

ClassicEditor
    .create(document.querySelector('#description'), {
        extraPlugins: [MyCustomUploadAdapterPlugin],
    })
    .catch(error => {
        console.error(error);
    });
</script>
@endsection
<script>
let selectedFiles = new DataTransfer();

function previewImages(event) {
    const preview = document.getElementById('preview-images');
    const input = event.target;

    Array.from(input.files).forEach(file => {
        // thêm file vào danh sách
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

    // cập nhật lại input files
    input.files = selectedFiles.files;
}

// xoá ảnh
function removeImage(button, fileName) {
    const dt = new DataTransfer();

    Array.from(selectedFiles.files).forEach(file => {
        if (file.name !== fileName) {
            dt.items.add(file);
        }
    });

    selectedFiles = dt;

    document.querySelector('input[name="images[]"]').files = selectedFiles.files;

    // xoá preview
    button.parentElement.remove();
}
</script>
