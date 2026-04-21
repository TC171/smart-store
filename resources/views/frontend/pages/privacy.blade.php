@extends('frontend.layouts.app')

@section('title', 'Chính sách bảo mật - Smart Store')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        {{-- Breadcrumb --}}
        <nav class="flex mb-8 text-sm text-gray-500 items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors">Trang chủ</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-gray-900 font-medium">Chính sách bảo mật</span>
        </nav>

        <div class="max-w-6xl mx-auto bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-gray-100">
            {{-- Header Section --}}
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-8 md:p-12 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs>
                        <rect width="100" height="100" fill="url(#grid)" />
                    </svg>
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 relative z-10">Chính sách Bảo mật</h1>
                <p class="text-slate-400 max-w-2xl mx-auto relative z-10">
                    Chúng tôi cam kết bảo vệ thông tin cá nhân của bạn và sự minh bạch trong cách chúng tôi xử lý dữ liệu.
                </p>
                <div class="mt-6 flex justify-center gap-4 text-xs font-medium text-slate-500 uppercase tracking-widest relative z-10">
                    <span>Phiên bản 2.1</span>
                    <span>•</span>
                    <span>Cập nhật ngày: {{ date('d/m/Y') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 lg:divide-x divide-gray-100">
                {{-- Table of Contents (Sidebar) --}}
                <aside class="hidden lg:block p-8 sticky top-24 h-fit">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Mục lục</h3>
                    <nav class="space-y-1">
                        <a href="#introduction" class="toc-link block px-4 py-2 text-sm font-medium text-orange-600 bg-orange-50 rounded-lg border-l-2 border-orange-500 transition-all duration-200">1. Lời giới thiệu</a>
                        <a href="#collecting" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">2. Thu thập thông tin</a>
                        <a href="#using" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">3. Mục đích sử dụng</a>
                        <a href="#security" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">4. Bảo mật dữ liệu</a>
                        <a href="#sharing" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">5. Chia sẻ thông tin</a>
                        <a href="#cookies" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">6. Công nghệ Cookie</a>
                        <a href="#rights" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">7. Quyền của bạn</a>
                        <a href="#contact" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">8. Liên hệ chúng tôi</a>
                    </nav>
                </aside>

                {{-- Content Section --}}
                <div class="lg:col-span-3 p-8 md:p-12 prose prose-slate max-w-none prose-headings:font-bold prose-a:text-orange-600 scroll-mt-24">
                    
                    {{-- Summary Boxes (Premium Touch) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 not-prose">
                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Bảo mật tuyệt đối</h4>
                            <p class="text-xs text-slate-500 mt-2">Sử dụng mã hóa SSL/TLS cho mọi giao dịch.</p>
                        </div>
                        <div class="bg-orange-50 p-6 rounded-2xl border border-orange-100 flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Quyền riêng tư</h4>
                            <p class="text-xs text-slate-500 mt-2">Bạn có toàn quyền kiểm soát dữ liệu cá nhân của mình.</p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-2xl border border-green-100 flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm">Hỗ trợ 24/7</h4>
                            <p class="text-xs text-slate-500 mt-2">Liên hệ với chúng tôi bất cứ lúc nào nếu có thắc mắc.</p>
                        </div>
                    </div>

                    <section id="introduction">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">01.</span> Lời giới thiệu
                        </h2>
                        <p>
                            Chào mừng bạn đến với <strong>Smart Store</strong>. Chúng tôi rất coi trọng sự riêng tư của bạn. Chính sách này mô tả cách chúng tôi thu thập, sử dụng, bảo vệ và tiết lộ thông tin của bạn khi bạn sử dụng dịch vụ của chúng tôi.
                        </p>
                        <p>
                            Việc bạn sử dụng trang web đồng nghĩa với việc bạn đồng ý với các nội dung được ghi trong Chính sách bảo mật này.
                        </p>
                    </section>

                    <section id="collecting" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">02.</span> Thu thập thông tin
                        </h2>
                        <p>Chúng tôi thu thập các loại thông tin sau để cung cấp dịch vụ tốt hơn cho người dùng:</p>
                        <ul>
                            <li><strong>Thông tin cá nhân:</strong> Họ tên, địa chỉ email, số điện thoại, địa chỉ giao hàng và thông tin thanh toán khi bạn đặt hàng.</li>
                            <li><strong>Thông tin thiết bị:</strong> Địa chỉ IP, loại trình duyệt, hệ điều hành và lịch sử truy cập trang web.</li>
                            <li><strong>Dữ liệu tương tác:</strong> Các sản phẩm bạn đã xem, giỏ hàng và lịch sử mua hàng.</li>
                        </ul>
                    </section>

                    <section id="using" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">03.</span> Mục đích sử dụng
                        </h2>
                        <p>Chúng tôi sử dụng thông tin thu thập được cho các mục đích:</p>
                        <ol>
                            <li>Xác nhận và quản lý đơn hàng của bạn.</li>
                            <li>Cung cấp dịch vụ vận chuyển và cập nhật trạng thái đơn hàng.</li>
                            <li>Gửi các thông báo khuyến mãi, tin tức sản phẩm (nếu bạn đồng ý).</li>
                            <li>Nâng cao trải nghiệm người dùng trên trang web.</li>
                            <li>Bảo vệ chống gian lận và các rủi ro bảo mật khác.</li>
                        </ol>
                    </section>

                    <section id="security" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">04.</span> Bảo mật dữ liệu
                        </h2>
                        <p>
                            Chúng tôi triển khai các phương pháp bảo mật vật lý và kỹ thuật tiên tiến nhất để bảo vệ thông tin của bạn. Hệ thống sử dụng công nghệ mã hóa <strong>AES-256</strong> và giao thức truyền tải bảo mật <strong>HTTPS/TLS</strong>.
                        </p>
                        <div class="bg-amber-50 p-4 rounded-xl border-l-4 border-amber-400 font-medium text-amber-800 text-sm italic">
                            Lưu ý: Mặc dù chúng tôi nỗ lực hết mình, không có phương pháp truyền tải dữ liệu nào trên internet là bảo mật 100%. Chúng tôi khuyến cáo bạn hãy bảo mật mật khẩu của mình một cách cá nhân.
                        </div>
                    </section>

                    <section id="sharing" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">05.</span> Chia sẻ thông tin
                        </h2>
                        <p>
                            Smart Store cam kết **KHÔNG** bán hoặc cho thuê thông tin cá nhân của bạn cho bên thứ ba. Chúng tôi chỉ chia sẻ dữ liệu khi thực sự cần thiết:
                        </p>
                        <ul>
                            <li>Với các đơn vị vận chuyển (Giao Hàng Nhanh, Viettel Post) để giao đơn hàng.</li>
                            <li>Với các cổng thanh toán (VNPay, Momo) để xử lý giao dịch.</li>
                            <li>Khi có yêu cầu từ cơ quan pháp luật theo quy định của Nhà nước.</li>
                        </ul>
                    </section>

                    <section id="cookies" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">06.</span> Công nghệ Cookie
                        </h2>
                        <p>
                            Chúng tôi sử dụng cookie để lưu trữ phiên đăng nhập và ghi nhớ các mặt hàng trong giỏ hàng của bạn. Cookie giúp hệ thống nhận diện bạn và cung cấp các đề xuất sản phẩm phù hợp hơn.
                        </p>
                    </section>

                    <section id="rights" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">07.</span> Quyền của bạn
                        </h2>
                        <p>Bạn có toàn quyền đối với dữ liệu của mình, bao gồm:</p>
                        <ul>
                            <li>Quyền truy cập và chỉnh sửa thông tin cá nhân.</li>
                            <li>Quyền yêu cầu xóa bỏ vĩnh viễn tài khoản và dữ liệu liên quan.</li>
                            <li>Quyền từ chối nhận email tiếp thị bất cứ lúc nào.</li>
                        </ul>
                    </section>

                    <section id="contact" class="mt-16 pt-8 border-t border-slate-100 not-prose">
                        <div class="bg-slate-900 rounded-[1.5rem] p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
                            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-orange-500/20 blur-3xl rounded-full"></div>
                            <div>
                                <h3 class="text-2xl font-bold mb-2">Bạn có thắc mắc về quyền riêng tư?</h3>
                                <p class="text-slate-400 text-sm">Đội ngũ hỗ trợ pháp lý và bảo mật của chúng tôi luôn sẵn sàng giải đáp.</p>
                            </div>
                            <a href="{{ route('page.contact') }}" class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20 whitespace-nowrap z-10">
                                Liên hệ ngay
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        {{-- Footer Note --}}
        <div class="mt-8 text-center text-gray-400 text-sm">
            © {{ date('Y') }} Smart Store. Tất cả các quyền được bảo lưu.
        </div>
    </div>
</div>

<style>
    .prose h2 { scroll-margin-top: 100px; }
    .toc-link.active {
        @apply text-orange-600 bg-orange-50 border-orange-500;
    }
    
    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }
    
    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

<script>
    // Intersection Observer for highlighting TOC on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.toc-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('text-orange-600', 'bg-orange-50', 'border-orange-500');
                link.classList.add('text-slate-600', 'border-transparent');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('text-orange-600', 'bg-orange-50', 'border-orange-500');
                    link.classList.remove('text-slate-600', 'border-transparent');
                }
            });
        });
    });
</script>
@endsection
