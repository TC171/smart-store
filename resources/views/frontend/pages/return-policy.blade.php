@extends('frontend.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-16">

    {{-- Header --}}
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
            Chính sách <span class="text-red-600">Đổi trả</span>
        </h1>
        <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">
            Smart Store hỗ trợ đổi trả linh hoạt để bạn yên tâm mua sắm.
        </p>
    </div>

    <div class="space-y-8">

        {{-- 1 --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                <span class="w-9 h-9 flex items-center justify-center bg-green-100 text-green-600 rounded-xl text-sm font-bold">1</span>
                Điều kiện đổi trả
            </h2>
            <ul class="space-y-3 text-gray-600 text-sm leading-relaxed list-disc list-inside">
                <li>Sản phẩm còn nguyên seal, chưa kích hoạt bảo hành (đối với hàng mới 100%).</li>
                <li>Sản phẩm còn đầy đủ hộp, phụ kiện đi kèm và hóa đơn mua hàng.</li>
                <li>Sản phẩm không có dấu hiệu va đập, trầy xước, ngấm nước do người dùng.</li>
                <li>Yêu cầu đổi trả được thực hiện trong thời gian cho phép (xem bảng bên dưới).</li>
            </ul>
        </div>

        {{-- 2 --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                <span class="w-9 h-9 flex items-center justify-center bg-blue-100 text-blue-600 rounded-xl text-sm font-bold">2</span>
                Thời gian đổi trả
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">Trường hợp</th>
                            <th class="px-4 py-3">Thời gian</th>
                            <th class="px-4 py-3 rounded-tr-lg">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y">
                        <tr>
                            <td class="px-4 py-3">Lỗi do nhà sản xuất</td>
                            <td class="px-4 py-3 font-semibold text-red-600">30 ngày</td>
                            <td class="px-4 py-3">Đổi mới hoặc hoàn tiền 100%</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Không ưng ý sản phẩm</td>
                            <td class="px-4 py-3 font-semibold text-red-600">7 ngày</td>
                            <td class="px-4 py-3">Sản phẩm chưa kích hoạt, còn nguyên seal</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Giao sai sản phẩm</td>
                            <td class="px-4 py-3 font-semibold text-red-600">3 ngày</td>
                            <td class="px-4 py-3">Đổi đúng sản phẩm hoặc hoàn tiền</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 3 --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                <span class="w-9 h-9 flex items-center justify-center bg-red-100 text-red-600 rounded-xl text-sm font-bold">3</span>
                Trường hợp không áp dụng đổi trả
            </h2>
            <ul class="space-y-3 text-gray-600 text-sm leading-relaxed list-disc list-inside">
                <li>Sản phẩm đã qua sử dụng, có dấu hiệu trầy xước hoặc hư hỏng do người dùng.</li>
                <li>Sản phẩm đã kích hoạt bảo hành điện tử (Apple, Samsung…).</li>
                <li>Sản phẩm khuyến mãi, giảm giá đặc biệt có ghi chú "Không đổi trả".</li>
                <li>Phụ kiện đã bóc seal hoặc không còn nguyên vẹn bao bì.</li>
                <li>Quá thời gian đổi trả quy định.</li>
            </ul>
        </div>

        {{-- 4 --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                <span class="w-9 h-9 flex items-center justify-center bg-purple-100 text-purple-600 rounded-xl text-sm font-bold">4</span>
                Quy trình đổi trả
            </h2>
            <div class="grid md:grid-cols-3 gap-6 text-center mt-6">
                <div>
                    <div class="w-12 h-12 mx-auto mb-3 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold">1</div>
                    <p class="text-sm text-gray-700 font-medium">Liên hệ hotline<br>hoặc đến cửa hàng</p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto mb-3 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold">2</div>
                    <p class="text-sm text-gray-700 font-medium">Kiểm tra sản phẩm<br>&amp; xác nhận</p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto mb-3 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold">3</div>
                    <p class="text-sm text-gray-700 font-medium">Đổi sản phẩm mới<br>hoặc hoàn tiền</p>
                </div>
            </div>
        </div>

        {{-- Refund info --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8">
            <h2 class="text-xl font-bold text-yellow-800 mb-3">💡 Lưu ý về hoàn tiền</h2>
            <ul class="space-y-2 text-yellow-700 text-sm list-disc list-inside">
                <li>Hoàn tiền qua chuyển khoản ngân hàng trong vòng 3–7 ngày làm việc.</li>
                <li>Hoàn tiền mặt tại cửa hàng ngay khi xác nhận đổi trả thành công.</li>
                <li>Phí vận chuyển đổi trả do Smart Store chi trả (nếu lỗi từ nhà sản xuất).</li>
            </ul>
        </div>

    </div>

    {{-- CTA --}}
    <div class="text-center mt-12">
        <p class="text-gray-500 mb-4">Cần đổi trả? Gọi ngay hotline <span class="text-red-600 font-bold">1900 1234</span></p>
        <a href="{{ route('page.contact') }}"
           class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3 rounded-full transition">
            Liên hệ đổi trả
        </a>
    </div>

</div>
@endsection
