@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6"><h1 class="text-2xl font-bold text-white">Tạo Bài Viết (Tin tức mới)</h1></div>
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 rounded-xl p-6">
        @csrf
        
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div>
                <label class="block text-gray-400 mb-2">Tiêu đề bài viết (*)</label>
                <input type="text" name="title" class="w-full bg-gray-800 text-white rounded p-3" required>
            </div>
            <div>
                <label class="block text-gray-400 mb-2">Chuyên mục</label>
                <select name="category_id" class="w-full bg-gray-800 text-white rounded p-3">
                    <option value="">Chọn danh mục...</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-400 mb-2">Hình đại diện (Thumbnail)</label>
            <input type="file" name="image" class="w-full bg-gray-800 text-white rounded p-3" accept="image/*">
        </div>

        <div class="mb-4">
            <label class="block text-gray-400 mb-2">Tóm tắt (Summary)</label>
            <textarea name="summary" class="w-full bg-gray-800 text-white rounded p-3" rows="3"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-400 mb-2">Nội dung chi tiết (HTML) (*)</label>
            <textarea id="content" name="content" class="w-full bg-gray-800 text-white rounded p-3 h-64"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-400 mb-2">Trạng thái (*)</label>
                <select name="status" class="w-full bg-gray-800 text-white rounded p-3" required>
                    <option value="1">Xuất bản</option>
                    <option value="0">Bản nháp</option>
                </select>
            </div>
            <div class="flex items-center mt-8">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" class="w-5 h-5">
                <label for="is_featured" class="text-white ml-2">Đánh dấu Tin nổi bật (Ghim lên trang chủ)</label>
            </div>
        </div>

        <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-black px-6 py-2 rounded font-bold">Lưu Bài Viết</button>
    </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
class UploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file.then(file => new Promise((resolve, reject) => {
            const data = new FormData();
            data.append('upload', file);

            fetch("{{ route('admin.posts.upload.image') }}", {
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
    .create(document.querySelector('#content'), {
        extraPlugins: [MyCustomUploadAdapterPlugin],
    })
    .catch(error => {
        console.error(error);
    });
</script>
<style>
    /* Chỉnh màu CKEditor phù hợp với giao diện tối */
    .ck-editor__editable {
        min-height: 400px;
        color: #000; /* ckeditor mặc định nền trắng chữ đen */
    }
</style>
@endsection
