@extends('frontend.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-16">

    {{-- Hero --}}
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
            Về <span class="text-red-600">Smart Store</span>
        </h1>
        <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">
            Hệ thống bán lẻ điện thoại, laptop &amp; phụ kiện chính hãng hàng đầu Việt Nam.
        </p>
    </div>

    {{-- Story --}}
    <section class="mb-16">
        <div class="bg-white rounded-2xl shadow-sm p-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                <span class="w-10 h-10 flex items-center justify-center bg-red-100 text-red-600 rounded-xl text-lg">📖</span>
                Câu chuyện của chúng tôi
            </h2>
            <div class="text-gray-600 leading-relaxed space-y-4">
                <p>
                    Smart Store được thành lập vào năm 2020 với sứ mệnh mang đến cho khách hàng Việt Nam những sản phẩm
                    công nghệ chính hãng với giá cả hợp lý nhất cùng dịch vụ hậu mãi tận tâm.
                </p>
                <p>
                    Trải qua hơn 5 năm phát triển, Smart Store đã trở thành đối tác phân phối chính thức của các thương
                    hiệu hàng đầu như Apple, Samsung, Xiaomi, Dell, Lenovo, ASUS và nhiều thương hiệu khác.
                </p>
                <p>
                    Với hệ thống cửa hàng trải dài tại Hà Nội, TP.HCM và Đà Nẵng, chúng tôi tự hào phục vụ
                    hàng triệu khách hàng trên cả nước.
                </p>
            </div>
        </div>
    </section>

    {{-- Core Values --}}
    <section class="mb-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">Giá trị cốt lõi</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm p-8 text-center hover:shadow-md transition">
                <div class="w-14 h-14 mx-auto mb-4 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl">✅</div>
                <h3 class="font-bold text-gray-800 mb-2">100% Chính hãng</h3>
                <p class="text-gray-500 text-sm">Tất cả sản phẩm đều có nguồn gốc rõ ràng, nhập khẩu chính ngạch và được bảo hành chính hãng.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-8 text-center hover:shadow-md transition">
                <div class="w-14 h-14 mx-auto mb-4 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-2xl">💰</div>
                <h3 class="font-bold text-gray-800 mb-2">Giá tốt nhất</h3>
                <p class="text-gray-500 text-sm">Cam kết mang lại mức giá cạnh tranh nhất thị trường cùng hàng ngàn ưu đãi hấp dẫn mỗi ngày.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-8 text-center hover:shadow-md transition">
                <div class="w-14 h-14 mx-auto mb-4 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-2xl">🛡️</div>
                <h3 class="font-bold text-gray-800 mb-2">Hậu mãi tận tâm</h3>
                <p class="text-gray-500 text-sm">Đội ngũ CSKH chuyên nghiệp hỗ trợ 24/7, bảo hành nhanh chóng và đổi trả dễ dàng.</p>
            </div>
        </div>
    </section>

    <section class="text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Bạn cần hỗ trợ?</h2>
        <p class="text-gray-500 mb-6">Liên hệ với đội ngũ Smart Store ngay hôm nay để được tư vấn miễn phí.</p>
        <a href="{{ route('page.contact') }}"
           class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3 rounded-full transition">
            Liên hệ ngay
        </a>
    </section>

</div>
@endsection
