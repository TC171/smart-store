@extends('frontend.layouts.app')

@section('title', 'Chính sách đổi trả - Smart Store')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        {{-- Breadcrumb --}}
        <nav class="flex mb-8 text-sm text-gray-500 items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors">Trang chủ</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-gray-900 font-medium">Chính sách đổi trả</span>
        </nav>

        <div class="max-w-6xl mx-auto bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-gray-100">
            {{-- Header Section --}}
            <div class="bg-gradient-to-r from-green-600 to-green-500 p-8 md:p-16 text-center relative overflow-hidden text-white">
                <div class="absolute inset-0 opacity-20 pointer-events-none">
                    <div class="absolute -top-24 -left-24 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-yellow-300 rounded-full blur-3xl"></div>
                </div>
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6 backdrop-blur-xl border border-white/30">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
                <h1 class="text-3xl md:text-5xl font-black mb-4 relative z-10 uppercase tracking-tight">Dịch vụ Đổi trả</h1>
                <p class="text-green-50 max-w-2xl mx-auto relative z-10 font-medium opacity-90">
                    Sự hài lòng của bạn là ưu tiên hàng đầu. Smart Store cam kết mang tới quy trình đổi trả minh bạch và nhanh chóng nhất.
                </p>
                <div class="mt-8 bg-black/10 backdrop-blur-md border border-white/20 inline-flex items-center gap-3 px-6 py-2 rounded-full text-sm font-bold relative z-10">
                    <span class="w-2 h-2 bg-yellow-300 rounded-full animate-pulse"></span>
                    CAM KẾT 30 NGÀY DÙNG THỬ - LỖI LÀ ĐỔI
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 lg:divide-x divide-gray-100">
                {{-- Table of Contents (Sidebar) --}}
                <aside class="hidden lg:block p-8 sticky top-24 h-fit">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Mục lục</h3>
                    <nav class="space-y-1">
                        <a href="#welcome" class="toc-link block px-4 py-3 text-sm font-bold text-green-600 bg-green-50 rounded-xl border-l-4 border-green-500 transition-all duration-200">1. Lời ngỏ</a>
                        <a href="#period" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-green-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">2. Thời hạn đổi trả</a>
                        <a href="#conditions" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-green-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">3. Điều kiện đổi trả</a>
                        <a href="#how" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-green-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">4. Cách thức thực hiện</a>
                        <a href="#refund" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-green-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">5. Hoàn tiền</a>
                        <a href="#shipping" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-green-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">6. Chi phí gửi hàng</a>
                    </nav>
                </aside>

                {{-- Content Section --}}
                <div class="lg:col-span-3 p-8 md:p-12 prose prose-slate max-w-none prose-headings:font-black prose-strong:text-green-600 scroll-mt-24">
                    
                    <section id="welcome">
                        <h2 class="text-3xl font-black mb-6">1. Lời ngỏ từ Smart Store</h2>
                        <p>
                            Tại <strong>Smart Store</strong>, chúng tôi hiểu rằng việc mua sắm đồ công nghệ đôi khi có những điều không như ý. Chính vì vậy, chúng tôi xây dựng chính sách đổi trả "Cực dễ" để đảm bảo bạn luôn có được sản phẩm ưng ý nhất.
                        </p>
                    </section>

                    <section id="period" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">2. Thời hạn Đổi trả vàng</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 not-prose">
                            <div class="relative p-8 bg-white border border-green-100 rounded-3xl shadow-sm overflow-hidden group">
                                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-20 transition-opacity">
                                    <svg class="w-24 h-24 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                </div>
                                <span class="text-xs font-bold text-green-600 uppercase tracking-widest">Ưu tiên số 1</span>
                                <h4 class="text-4xl font-black text-slate-900 mt-2 mb-4">30 Ngày</h4>
                                <p class="text-sm text-slate-500 font-medium">Lỗi do nhà sản xuất, đổi mới 1-1 ngay lập tức cùng cấu hình, cùng màu sắc.</p>
                            </div>
                            <div class="relative p-8 bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden group">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Theo nhu cầu</span>
                                <h4 class="text-4xl font-black text-slate-400 mt-2 mb-4">07 Ngày</h4>
                                <p class="text-sm text-slate-500 font-medium">Đổi trả sản phẩm theo nhu cầu (chưa qua sử dụng, còn nguyên seal).</p>
                            </div>
                        </div>
                    </section>

                    <section id="conditions" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">3. Điều kiện Đổi trả sản phẩm</h2>
                        <p>Để được chấp nhận đổi trả, sản phẩm của Quý khách cần thỏa mãn:</p>
                        <div class="space-y-4 not-prose">
                            <div class="flex items-center gap-4 p-4 hover:bg-slate-50 rounded-2xl transition-colors border border-transparent hover:border-slate-100">
                                <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex-shrink-0 flex items-center justify-center font-bold">1</div>
                                <span class="text-sm font-medium text-slate-700">Sản phẩm đầy đủ hộp, phụ kiện đi kèm (sạc, cáp, tai nghe) và quà tặng (nếu có).</span>
                            </div>
                            <div class="flex items-center gap-4 p-4 hover:bg-slate-50 rounded-2xl transition-colors border border-transparent hover:border-slate-100">
                                <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex-shrink-0 flex items-center justify-center font-bold">2</div>
                                <span class="text-sm font-medium text-slate-700">Sản phẩm chưa có dấu hiệu can thiệp phần cứng trái phép, chưa bị vào nước.</span>
                            </div>
                            <div class="flex items-center gap-4 p-4 hover:bg-slate-50 rounded-2xl transition-colors border border-transparent hover:border-slate-100">
                                <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex-shrink-0 flex items-center justify-center font-bold">3</div>
                                <span class="text-sm font-medium text-slate-700">IMEI/Serial Number trên sản phẩm phải trùng khớp với đơn hàng tại Smart Store.</span>
                            </div>
                        </div>
                    </section>

                    <section id="how" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">4. Cách thức thực hiện Đổi trả</h2>
                        <div class="bg-slate-50 p-8 rounded-[2rem] border border-slate-100 not-prose">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h5 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        Tại cửa hàng
                                    </h5>
                                    <p class="text-sm text-slate-500 leading-relaxed">Mang sản phẩm cùng hóa đơn trực tiếp tới hệ thống Smart Store trên toàn quốc. Chúng tôi xử lý ngay trong 15-30 phút.</p>
                                </div>
                                <div>
                                    <h5 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        Gửi qua Bưu điện
                                    </h5>
                                    <p class="text-sm text-slate-500 leading-relaxed">Đóng gói cẩn thận và gửi về kho của chúng tôi. Sau khi nhận và kiểm tra, chúng tôi sẽ gọi lại xác nhận và gửi hàng đổi mới.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="refund" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">5. Quy định Hoàn tiền</h2>
                        <p>Trường hợp Smart Store không còn hàng để đổi mới, bạn có thể chọn:</p>
                        <ul class="list-disc pl-5">
                            <li>Đổi sang sản phẩm khác tương đương giá trị (hoặc bù trừ chênh lệch).</li>
                            <li>Nhận lại 100% số tiền đã mua (Hoàn tiền qua thẻ/chuyển khoản trong vòng 2-3 ngày làm việc).</li>
                        </ul>
                    </section>

                    <section id="shipping" class="mt-16 pt-8 border-t border-slate-100 not-prose">
                        <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden">
                            <div class="absolute -right-20 -top-20 w-80 h-80 bg-green-500/20 blur-[100px] rounded-full"></div>
                            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                                <div class="max-w-md">
                                    <h3 class="text-3xl font-black mb-4">6. Chi phí gửi hàng?</h3>
                                    <p class="text-slate-400 text-sm leading-relaxed mb-0">
                                        Nếu lỗi thuộc về nhà sản xuất hoặc chúng tôi giao nhầm hàng, <strong>Smart Store sẽ chịu 100% chi phí vận chuyển 2 chiều</strong>. Bạn không phải trả thêm bất kỳ đồng nào.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        </div>
    </div>
</div>

<style>
    .prose h2 { scroll-margin-top: 100px; }
    .toc-link.active {
        @apply text-green-600 bg-green-50 border-green-500 shadow-sm shadow-green-100;
    }
    html { scroll-behavior: smooth; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.toc-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('text-green-600', 'bg-green-50', 'border-green-500', 'shadow-sm', 'shadow-green-100');
                link.classList.add('text-slate-600', 'border-transparent');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('text-green-600', 'bg-green-50', 'border-green-500', 'shadow-sm', 'shadow-green-100');
                    link.classList.remove('text-slate-600', 'border-transparent');
                }
            });
        });
    });
</script>
@endsection
