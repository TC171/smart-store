@extends('frontend.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-16">

    {{-- Header --}}
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
            Chính sách <span class="text-red-600">Bảo hành</span>
        </h1>
        <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">
            Smart Store cam kết bảo hành chính hãng cho tất cả sản phẩm.
        </p>
    </div>

    <div class="space-y-8">

        {{-- 1 --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                <span class="w-9 h-9 flex items-center justify-center bg-blue-100 text-blue-600 rounded-xl text-sm font-bold">1</span>
                Điều kiện bảo hành
            </h2>
            <ul class="space-y-3 text-gray-600 text-sm leading-relaxed list-disc list-inside">
                <li>Sản phẩm còn trong thời gian bảo hành (tính từ ngày mua).</li>
                <li>Tem bảo hành, serial number còn nguyên vẹn, không bị tẩy xóa.</li>
                <li>Sản phẩm bị lỗi do nhà sản xuất (lỗi phần cứng, phần mềm gốc).</li>
                <li>Có hóa đơn mua hàng hoặc phiếu bảo hành từ Smart Store.</li>
            </ul>
        </div>

        {{-- 2 --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                <span class="w-9 h-9 flex items-center justify-center bg-green-100 text-green-600 rounded-xl text-sm font-bold">2</span>
                Thời gian bảo hành
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">Loại sản phẩm</th>
                            <th class="px-4 py-3 rounded-tr-lg">Thời gian bảo hành</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y">
                        <tr><td class="px-4 py-3">Điện thoại</td><td class="px-4 py-3">12 – 24 tháng</td></tr>
                        <tr><td class="px-4 py-3">Laptop</td><td class="px-4 py-3">12 – 36 tháng</td></tr>
                        <tr><td class="px-4 py-3">Máy tính bảng</td><td class="px-4 py-3">12 – 24 tháng</td></tr>
                        <tr><td class="px-4 py-3">Phụ kiện</td><td class="px-4 py-3">3 – 12 tháng</td></tr>
                        <tr><td class="px-4 py-3">Tai nghe / Loa</td><td class="px-4 py-3">6 – 12 tháng</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 3 --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                <span class="w-9 h-9 flex items-center justify-center bg-red-100 text-red-600 rounded-xl text-sm font-bold">3</span>
                Trường hợp không bảo hành
            </h2>
            <ul class="space-y-3 text-gray-600 text-sm leading-relaxed list-disc list-inside">
                <li>Sản phẩm hết thời gian bảo hành.</li>
                <li>Sản phẩm bị hư hỏng do tác động bên ngoài: rơi, va đập, ngấm nước.</li>
                <li>Sản phẩm đã được sửa chữa bởi bên thứ ba ngoài Smart Store.</li>
                <li>Tem bảo hành bị rách, mờ hoặc không xác định được.</li>
                <li>Lỗi phát sinh do sử dụng sai cách hoặc cài đặt phần mềm không chính thống.</li>
            </ul>
        </div>

        {{-- 4 --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                <span class="w-9 h-9 flex items-center justify-center bg-purple-100 text-purple-600 rounded-xl text-sm font-bold">4</span>
                Quy trình bảo hành
            </h2>
            <div class="grid md:grid-cols-4 gap-6 text-center mt-6">
                <div>
                    <div class="w-12 h-12 mx-auto mb-3 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold">1</div>
                    <p class="text-sm text-gray-700 font-medium">Mang sản phẩm<br>đến cửa hàng</p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto mb-3 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold">2</div>
                    <p class="text-sm text-gray-700 font-medium">Kiểm tra &amp;<br>xác nhận lỗi</p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto mb-3 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold">3</div>
                    <p class="text-sm text-gray-700 font-medium">Tiến hành<br>sửa chữa / đổi</p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto mb-3 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold">4</div>
                    <p class="text-sm text-gray-700 font-medium">Nhận lại<br>sản phẩm</p>
                </div>
            </div>
        </div>

    </div>

    {{-- CTA --}}
    <div class="text-center mt-12">
        <p class="text-gray-500 mb-4">Cần hỗ trợ bảo hành? Liên hệ hotline <span class="text-red-600 font-bold">1900 1234</span></p>
        <a href="{{ route('page.contact') }}"
           class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3 rounded-full transition">
            Liên hệ hỗ trợ
        </a>
    </div>

</div>
@endsection
