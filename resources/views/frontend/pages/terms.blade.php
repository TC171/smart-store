@extends('frontend.layouts.app')

@section('title', 'Điều khoản dịch vụ - Smart Store')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        {{-- Breadcrumb --}}
        <nav class="flex mb-8 text-sm text-gray-500 items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors">Trang chủ</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-gray-900 font-medium">Điều khoản dịch vụ</span>
        </nav>

        <div class="max-w-6xl mx-auto bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-gray-100">
            {{-- Header Section --}}
            <div class="bg-slate-900 p-8 md:p-16 text-center relative overflow-hidden text-white">
                <div class="absolute inset-0 opacity-20 pointer-events-none">
                    <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_50%,#e53935_0,transparent_50%)]"></div>
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold mb-4 relative z-10">Điều khoản & Điều kiện</h1>
                <p class="text-slate-400 max-w-2xl mx-auto relative z-10 italic">
                    Vui lòng đọc kỹ các điều khoản này trước khi sử dụng dịch vụ của Smart Store để đảm bảo quyền lợi của bạn.
                </p>
                <div class="mt-8 inline-block bg-white/5 backdrop-blur-md border border-white/10 px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest text-slate-300">
                    ID: TOS-2026-V1
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 lg:divide-x divide-gray-100">
                {{-- Table of Contents (Sidebar) --}}
                <aside class="hidden lg:block p-8 sticky top-24 h-fit">
                    <nav class="space-y-1">
                        <a href="#acceptance" class="toc-link block px-4 py-3 text-sm font-bold text-orange-600 bg-orange-50 rounded-xl border-l-4 border-orange-500 transition-all duration-200">1. Chấp nhận điều khoản</a>
                        <a href="#account" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">2. Tài khoản người dùng</a>
                        <a href="#ordering" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">3. Đặt hàng & Hợp đồng</a>
                        <a href="#pricing" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">4. Giá cả & Thanh toán</a>
                        <a href="#warranty" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">5. Bảo hành & Đổi trả</a>
                        <a href="#intellectual" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">6. Sở hữu trí tuệ</a>
                        <a href="#prohibited" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-orange-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">7. Hành vi bị cấm</a>
                    </nav>
                </aside>

                {{-- Content Section --}}
                <div class="lg:col-span-3 p-8 md:p-12 prose prose-slate max-w-none prose-headings:font-black prose-p:text-slate-600 prose-li:text-slate-600 scroll-mt-24">
                    
                    <section id="acceptance">
                        <h2 class="text-3xl font-black mb-6">1. Chấp nhận các điều khoản</h2>
                        <p>
                            Bằng việc truy cập vào trang web <strong>Smart Store</strong>, bạn xác nhận rằng mình đã đọc, hiểu và đồng ý bị ràng buộc bởi các Điều khoản dịch vụ này. Nếu bạn không đồng ý với bất kỳ phần nào của các điều khoản này, vui lòng không tiếp tục sử dụng trang web.
                        </p>
                    </section>

                    <section id="account" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">2. Tài khoản khách hàng</h2>
                        <p>Khi đăng ký tài khoản tại Smart Store, bạn có trách nhiệm:</p>
                        <ul class="list-none space-y-4 pl-0">
                            <li class="flex gap-4 items-start p-4 bg-slate-50 rounded-2xl">
                                <div class="w-8 h-8 bg-slate-900 text-white rounded-lg flex-shrink-0 flex items-center justify-center font-bold">A</div>
                                <p class="m-0">Cung cấp thông tin chính xác, đầy đủ và cập nhật thường xuyên.</p>
                            </li>
                            <li class="flex gap-4 items-start p-4 bg-slate-50 rounded-2xl">
                                <div class="w-8 h-8 bg-slate-900 text-white rounded-lg flex-shrink-0 flex items-center justify-center font-bold">B</div>
                                <p class="m-0">Bảo mật mật khẩu và chịu trách nhiệm cho mọi hoạt động dưới tài khoản của mình.</p>
                            </li>
                            <li class="flex gap-4 items-start p-4 bg-slate-50 rounded-2xl">
                                <div class="w-8 h-8 bg-slate-900 text-white rounded-lg flex-shrink-0 flex items-center justify-center font-bold">C</div>
                                <p class="m-0">Thông báo ngay lập tức cho chúng tôi nếu nghi ngờ có sự xâm nhập trái phép.</p>
                            </li>
                        </ul>
                    </section>

                    <section id="ordering" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">3. Đặt hàng & Giao kết hợp đồng</h2>
                        <p>
                            Việc bạn đặt hàng trên hệ thống của chúng tôi được coi là một lời đề nghị mua hàng. Hợp đồng mua bán chỉ chính thức được thiết lập khi chúng tôi gửi email xác nhận vận chuyển hoặc xác nhận đơn hàng thành công qua số điện thoại/email.
                        </p>
                        <p>
                            Smart Store có quyền từ chối đơn hàng vì lý do lỗi kỹ thuật giá sản phẩm, hết hàng đột ngột hoặc nghi ngờ có sự gian lận trong thanh toán/khuyến mãi.
                        </p>
                    </section>

                    <section id="pricing" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">4. Giá cả & Thanh toán</h2>
                        <p>
                            Giá sản phẩm được niêm yết tại Smart Store đã bao gồm thuế GTGT (VAT) theo quy định của pháp luật Việt Nam. 
                        </p>
                        <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100 flex gap-4 items-start shadow-sm shadow-indigo-100">
                            <svg class="w-6 h-6 text-indigo-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <p class="m-0 text-sm font-medium text-indigo-900">
                                Chúng tôi hỗ trợ đa dạng phương thức thanh toán: Tiền mặt khi nhận hàng (COD), Thẻ tín dụng/ghi nợ, Chuyển khoản ngân hàng và các ví điện tử (VNPay, Momo). Mọi giao dịch thanh toán đều được xử lý qua cổng bảo mật tiêu chuẩn quốc tế.
                            </p>
                        </div>
                    </section>

                    <section id="warranty" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">5. Chính sách Bảo hành & Đổi trả</h2>
                        <p>
                            Tất cả sản phẩm điện tử tại Smart Store đều được bảo hành chính hãng theo tiêu chuẩn của nhà sản xuất (Apple, Samsung, Sony, v.v.).
                        </p>
                        <p>
                            Quy trình đổi trả tuân thủ theo <strong>Chính sách đổi trả</strong> riêng biệt của chúng tôi, áp dụng cho các lỗi kỹ thuật phát sinh từ nhà sản xuất trong vòng 30 ngày đầu sử dụng.
                        </p>
                    </section>

                    <section id="intellectual" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">6. Quyền sở hữu trí tuệ</h2>
                        <p>
                            Mọi nội dung trên trang web bao gồm văn bản, thiết kế, đồ họa, logo, biểu tượng, hình ảnh đều là tài sản của Smart Store và được bảo hộ bởi luật sở hữu trí tuệ Việt Nam. Bạn không được sao chép, tái bản hoặc sử dụng cho mục đích thương mại mà không có sự đồng ý bằng văn bản của chúng tôi.
                        </p>
                    </section>

                    <section id="prohibited" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">7. Hành vi bị nghiêm cấm</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 not-prose">
                            <div class="p-4 border border-red-100 bg-red-50 rounded-xl flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                <span class="text-sm font-medium text-red-900">Sử dụng robot, crawler để khai thác dữ liệu trái phép.</span>
                            </div>
                            <div class="p-4 border border-red-100 bg-red-50 rounded-xl flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                <span class="text-sm font-medium text-red-900">Tấn công mạng vào hệ thống hoặc ứng dụng của chúng tôi.</span>
                            </div>
                            <div class="p-4 border border-red-100 bg-red-50 rounded-xl flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                <span class="text-sm font-medium text-red-900">Giả danh người khác để lừa đảo hoặc trục lợi khuyến mãi.</span>
                            </div>
                            <div class="p-4 border border-red-100 bg-red-50 rounded-xl flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                <span class="text-sm font-medium text-red-900">Đăng tải các nội dung vi phạm thuần phong mỹ tục hoặc pháp luật.</span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        {{-- Footer Note --}}
        <div class="mt-8 text-center text-gray-400 text-sm italic">
            Điều khoản này có hiệu lực từ ngày 01/01/2026.
        </div>
    </div>
</div>

<style>
    .prose h2 { scroll-margin-top: 100px; }
    .toc-link.active {
        @apply text-orange-600 bg-orange-50 border-orange-500 shadow-sm shadow-orange-100;
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
                link.classList.remove('text-orange-600', 'bg-orange-50', 'border-orange-500', 'shadow-sm', 'shadow-orange-100');
                link.classList.add('text-slate-600', 'border-transparent');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('text-orange-600', 'bg-orange-50', 'border-orange-500', 'shadow-sm', 'shadow-orange-100');
                    link.classList.remove('text-slate-600', 'border-transparent');
                }
            });
        });
    });
</script>
@endsection
