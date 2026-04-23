@extends('frontend.layouts.app')

@section('title', 'Chính sách vận chuyển - Smart Store')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        {{-- Breadcrumb --}}
        <nav class="flex mb-8 text-sm text-gray-500 items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors">Trang chủ</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-gray-900 font-medium">Chính sách vận chuyển</span>
        </nav>

        <div class="max-w-6xl mx-auto bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-gray-100">
            {{-- Header Section --}}
            <div class="bg-gradient-to-r from-orange-600 to-orange-500 p-8 md:p-12 text-center relative overflow-hidden text-white">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full blur-2xl -ml-24 -mb-24"></div>
                <h1 class="text-3xl md:text-5xl font-extrabold mb-4 relative z-10">Dịch vụ Giao hàng</h1>
                <p class="text-orange-50 max-w-2xl mx-auto relative z-10 font-medium">
                    Smart Store sử dụng đội ngũ nhân viên giao nhận nội bộ tin cậy, được đào tạo chuyên nghiệp để mang sản phẩm đến tay bạn an toàn nhất.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-6 relative z-10">
                    <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full backdrop-blur-md border border-white/30 text-sm font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Niêm phong an toàn
                    </div>
                    <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full backdrop-blur-md border border-white/30 text-sm font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Bảo hiểm 100% giá trị
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 lg:divide-x divide-gray-100">
                {{-- Table of Contents (Sidebar) --}}
                <aside class="hidden lg:block p-8 sticky top-24 h-fit">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Mục lục</h3>
                    <nav class="space-y-1">
                        <a href="#partner" class="toc-link block px-4 py-2 text-sm font-bold text-orange-600 bg-orange-50 rounded-lg border-l-2 border-orange-500 transition-all duration-200">1. Đội ngũ giao vận nội bộ</a>
                        <a href="#fee" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">2. Cước phí & Miễn phí</a>
                        <a href="#time" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">3. Thời gian giao hàng</a>
                        <a href="#inspection" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">4. Quy định đồng kiểm</a>
                        <a href="#package" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">5. Quy cách đóng gói</a>
                        <a href="#issues" class="toc-link block px-4 py-2 text-sm font-medium text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all duration-200">6. Sự cố & Khiếu nại</a>
                    </nav>
                </aside>

                {{-- Content Section --}}
                <div class="lg:col-span-3 p-8 md:p-12 prose prose-slate max-w-none prose-headings:font-bold prose-strong:text-slate-900 scroll-mt-24">
                    
                    <section id="partner">
                        <h2 class="text-2xl font-black flex items-center gap-3">
                            <span class="text-orange-500">01.</span> Đội ngũ giao nhận nội bộ tin cậy
                        </h2>
                        <p class="text-slate-600 font-medium italic mb-6">
                            "Vì chúng tôi hiểu giá trị của sản phẩm công nghệ là vô giá, nên chúng tôi không giao vận qua trung gian."
                        </p>
                        <p>
                            Để đảm bảo hàng hóa (đặc biệt là đồ công nghệ giá trị cao) được vận chuyển an toàn tuyệt đối, 100% đơn hàng tại Smart Store được giao bởi đội ngũ nhân viên chính thức:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 not-prose mt-8">
                            <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl flex items-start gap-4">
                                <div class="w-12 h-12 bg-orange-500 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-orange-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-1">Nhân viên Trung thực</h4>
                                    <p class="text-xs text-slate-500">Đội ngũ được tuyển dụng và đào tạo bài bản về thái độ phục vụ và đạo đức nghề nghiệp.</p>
                                </div>
                            </div>
                            <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl flex items-start gap-4">
                                <div class="w-12 h-12 bg-slate-900 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-slate-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-1">Trách nhiệm tối đa</h4>
                                    <p class="text-xs text-slate-500">Mỗi nhân viên trực tiếp quản lý và bảo quản kỹ lưỡng từng đơn hàng từ lúc xuất kho đến khi khách ký nhận.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="fee" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">02.</span> Ưu đãi Vận chuyển đặc biệt
                        </h2>
                        <div class="bg-gradient-to-br from-orange-600 to-orange-400 p-8 rounded-[2.5rem] text-white not-prose shadow-xl shadow-orange-200 relative overflow-hidden">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                            <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                                <div class="w-24 h-24 bg-white/20 rounded-3xl flex-shrink-0 flex items-center justify-center backdrop-blur-md">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-black mb-2 uppercase italic tracking-tighter text-white">Miễn phí ship 100%</h3>
                                    <p class="text-orange-50 text-lg font-medium opacity-90 leading-relaxed">
                                        Đặc quyền có 1-0-2 tại Smart Store. Với thông điệp "Khách hàng là thượng đế", chúng tôi miễn phí vận chuyển cho **TẤT CẢ** đơn hàng, không giới hạn giá trị tối thiểu, trên toàn quốc.
                                    </p>
                                    <div class="mt-4 flex gap-3">
                                        <span class="px-3 py-1 bg-white/10 rounded-lg text-xs font-bold border border-white/20">KHÔNG CẦN VOUCHER</span>
                                        <span class="px-3 py-1 bg-white/10 rounded-lg text-xs font-bold border border-white/20">KHÔNG GIỚI HẠN KHOẢNG CÁCH</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="time" class="mt-12">
                        <h2 class="text-2xl font-black flex items-center gap-3">
                            <span class="text-orange-500">03.</span> Lịch trình Giao nhận cố định
                        </h2>
                        <div class="bg-white rounded-[2rem] border-2 border-dashed border-slate-200 p-8 not-prose">
                            <div class="flex flex-col md:flex-row items-center gap-8">
                                <div class="w-16 h-16 bg-slate-900 text-white rounded-2xl flex items-center justify-center flex-shrink-0 animate-pulse">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900 mb-2">Giao hàng sớm nhất tới tay bạn</h4>
                                    <p class="text-slate-600 leading-relaxed mb-0">
                                        Đội ngũ nhân viên của Smart Store luôn trực chiến để xử lý và giao đơn hàng của bạn **ngay lập tức**. 
                                        Chúng tôi cam kết lộ trình vận chuyển luôn là ngắn nhất và nhanh nhất để sản phẩm công nghệ được bàn giao tới bạn một cách thần tốc và an toàn tuyệt đối.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="inspection" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">04.</span> Quy định Nhận hàng & Quay Video
                        </h2>
                        <div class="bg-indigo-900 rounded-[2rem] p-8 text-white relative overflow-hidden not-prose">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
                            <div class="flex flex-col md:flex-row gap-6 items-center">
                                <div class="w-20 h-20 bg-white/20 rounded-3xl flex-shrink-0 flex items-center justify-center backdrop-blur-xl">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold mb-3 italic">Thanh toán trước khi mở kiện hàng</h3>
                                    <p class="text-indigo-100 text-sm leading-relaxed">
                                        Để đảm bảo tính bảo mật và nguyên seal của sản phẩm, Smart Store <strong>không áp dụng chính sách đồng kiểm</strong> lúc nhận hàng. Quý khách vui lòng thanh toán cho nhân viên giao hàng trước khi mở bưu kiện.
                                    </p>
                                    <p class="mt-4 text-sm font-bold text-orange-400">
                                        💡 QUAN TRỌNG: Quý khách vui lòng QUAY VIDEO quá trình mở hộp sản phẩm. Đây là bằng chứng duy nhất để Smart Store hỗ trợ đổi trả nếu có sự cố về sản phẩm.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="package" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">05.</span> Quy cách đóng gói tiêu chuẩn
                        </h2>
                        <p>Tất cả sản phẩm tại **Smart Store** được đóng gói 3 lớp bảo vệ:</p>
                        <ol>
                            <li><strong>Lớp 1:</strong> Túi bóng khí (xốp nổ) chống sốc bao quanh hộp sản phẩm chính.</li>
                            <li><strong>Lớp 2:</strong> Thùng carton cứng chịu lực in logo Smart Store.</li>
                            <li><strong>Lớp 3:</strong> Băng keo niêm phong thương hiệu (giúp khách hàng nhận diện nếu hàng có dấu hiệu bị bóc mở).</li>
                        </ol>
                    </section>

                    <section id="issues" class="mt-12">
                        <h2 class="text-2xl font-bold flex items-center gap-3">
                            <span class="text-orange-500">06.</span> Xử lý sự cố khi nhận hàng
                        </h2>
                        <p>Nếu gặp các trường hợp sau, quý khách vui lòng từ chối nhận hàng và gọi ngay Hotline **1900 xxxx**:</p>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg> Băng keo niêm phong bị rách hoặc có dấu hiệu dán đè.</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg> Hộp bị bóp méo, thủng hoặc thấm nước nghiêm trọng.</li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>

        {{-- Footer Note --}}
        <div class="mt-8 text-center text-gray-400 text-sm">
            Mọi thắc mắc vui lòng gửi về: <a href="mailto:chammut0009@gmail.com" class="text-orange-500 underline">chammut0009@gmail.com</a> hoặc qua trang <a href="{{ route('page.contact') }}" class="text-orange-500 underline">Liên hệ</a>
        </div>
    </div>
</div>

<style>
    .prose h2 { scroll-margin-top: 100px; }
    .toc-link.active {
        @apply text-orange-600 bg-orange-50 border-orange-500;
    }
    
    html {
        scroll-behavior: smooth;
    }
</style>

<script>
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
