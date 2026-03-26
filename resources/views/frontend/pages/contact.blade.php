@extends('frontend.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-16">

    {{-- Header --}}
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
            <span class="text-red-600">Liên hệ</span> với chúng tôi
        </h1>
        <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">
            Đội ngũ Smart Store luôn sẵn sàng hỗ trợ bạn 24/7.
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-10">

        {{-- Contact Info --}}
        <div class="space-y-6">

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Thông tin liên hệ</h2>
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-xl flex items-center justify-center flex-shrink-0">📞</div>
                        <div>
                            <p class="font-semibold text-gray-800">Hotline</p>
                            <p class="text-gray-500 text-sm">1900 1234 (8:00 – 22:00 hằng ngày)</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">📧</div>
                        <div>
                            <p class="font-semibold text-gray-800">Email</p>
                            <p class="text-gray-500 text-sm">support@smartstore.vn</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center flex-shrink-0">💬</div>
                        <div>
                            <p class="font-semibold text-gray-800">Zalo / Messenger</p>
                            <p class="text-gray-500 text-sm">Chat trực tuyến trên Zalo hoặc Facebook Messenger</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stores --}}
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Hệ thống cửa hàng</h2>
                <div class="space-y-4 text-sm text-gray-600">
                    <div class="flex items-start gap-3">
                        <span class="text-red-500 mt-0.5">📍</span>
                        <div>
                            <p class="font-semibold text-gray-800">Hà Nội</p>
                            <p>Cao đẳng FPT, Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Contact Form --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Gửi tin nhắn cho chúng tôi</h2>
            <form action="#" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                    <input type="text" name="name" placeholder="Nhập họ và tên"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" placeholder="Nhập email"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                    <input type="tel" name="phone" placeholder="Nhập số điện thoại"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nội dung</label>
                    <textarea name="message" rows="5" placeholder="Nhập nội dung tin nhắn..."
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition resize-none"></textarea>
                </div>
                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition">
                    Gửi tin nhắn
                </button>
            </form>
        </div>

    </div>

    {{-- Map --}}
    <div class="mt-12">
        <div class="bg-white rounded-2xl shadow-sm p-4 overflow-hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.863!2d105.7413863!3d21.0381!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313454b991d14cc5%3A0x7e1f56e1635419f!2zQ2FvIMSR4bqzbmcgRlBUIFBvbHl0ZWNobmlj!5e0!3m2!1svi!2svn!4v1711234567890"
                width="100%" height="350" style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

</div>
@endsection
