<footer class="bg-gray-900 text-gray-300 py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10">

            <!-- Cột 1 -->
            <div>
                <h3 class="text-white text-2xl font-bold mb-4">Smart<span class="text-red-600">Store</span></h3>
                <p class="text-sm leading-relaxed">Cửa hàng điện thoại, laptop và phụ kiện chính hãng hàng đầu Việt Nam.</p>
                <div class="mt-6 flex gap-4">
                    <i class="fab fa-facebook text-2xl hover:text-blue-500 cursor-pointer"></i>
                    <i class="fab fa-youtube text-2xl hover:text-red-500 cursor-pointer"></i>
                    <i class="fab fa-tiktok text-2xl hover:text-pink-500 cursor-pointer"></i>
                </div>
            </div>

            <!-- Cột 2 -->
            <div>
                <h4 class="font-semibold text-white mb-4">Sản phẩm</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('category.products', 'dien-thoai') }}" class="hover:text-white transition-colors">Điện thoại</a></li>
                    <li><a href="{{ route('category.products', 'laptop') }}" class="hover:text-white transition-colors">Laptop</a></li>
                    <li><a href="{{ route('category.products', 'may-tinh-bang') }}" class="hover:text-white transition-colors">Máy tính bảng</a></li>
                    <li><a href="{{ route('category.products', 'phu-kien') }}" class="hover:text-white transition-colors">Phụ kiện</a></li>
                </ul>
            </div>

            <!-- Cột 3 -->
            <div>
                <h4 class="font-semibold text-white mb-4">Thông tin</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('page.about') }}" class="hover:text-white">Về chúng tôi</a></li>
                    <li><a href="{{ route('page.warranty') }}" class="hover:text-white">Chính sách bảo hành</a></li>
                    <li><a href="{{ route('page.return-policy') }}" class="hover:text-white">Chính sách đổi trả</a></li>
                    <li><a href="{{ route('page.shipping') }}" class="hover:text-white">Chính sách vận chuyển</a></li>
                    <li><a href="{{ route('page.privacy') }}" class="hover:text-white">Chính sách bảo mật</a></li>
                    <li><a href="{{ route('page.terms') }}" class="hover:text-white">Điều khoản dịch vụ</a></li>
                    <li><a href="{{ route('page.contact') }}" class="hover:text-white">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Cột 4 -->
            <div>
                <h4 class="font-semibold text-white mb-4">Liên hệ</h4>
                <p class="text-sm">Hotline: <span class="text-white font-medium">1900 1234</span></p>
                <p class="text-sm mt-1">Email: chammut0009@gmail.com</p>
                <p class="text-sm mt-4">Cao đẳng FPT, Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</p>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-xs">
            © {{ date('Y') }} Smart Store. All rights reserved.
        </div>
    </div>
</footer>
