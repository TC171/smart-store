@extends('frontend.layouts.app')

@section('title', 'Chính sách bảo hành - Smart Store')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        {{-- Breadcrumb --}}
        <nav class="flex mb-8 text-sm text-gray-500 items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-orange-500 transition-colors">Trang chủ</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-gray-900 font-medium">Chính sách bảo hành</span>
        </nav>

        <div class="max-w-6xl mx-auto bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-gray-100">
            {{-- Header Section --}}
            <div class="bg-gradient-to-r from-red-700 to-red-600 p-8 md:p-16 text-center relative overflow-hidden text-white">
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 0 L100 100 M0 100 L100 0" stroke="white" stroke-width="0.5" />
                    </svg>
                </div>
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6 backdrop-blur-xl border border-white/30">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h1 class="text-3xl md:text-5xl font-black mb-4 relative z-10 uppercase tracking-tight">Cam kết Bảo hành</h1>
                <p class="text-red-50 max-w-2xl mx-auto relative z-10 font-medium opacity-90">
                    Yên tâm sử dụng sản phẩm với chính sách bảo hành chính hãng chuẩn quốc tế và sự hỗ trợ tận tâm từ Smart Store.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 lg:divide-x divide-gray-100">
                {{-- Table of Contents (Sidebar) --}}
                <aside class="hidden lg:block p-8 sticky top-24 h-fit">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6 border-b pb-2">Thông tin</h3>
                    <nav class="space-y-1">
                        <a href="#genuine" class="toc-link block px-4 py-3 text-sm font-bold text-red-600 bg-red-50 rounded-xl border-l-4 border-red-500 transition-all duration-200">1. Bảo hành chính hãng</a>
                        <a href="#duration" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-red-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">2. Thời hạn bảo hành</a>
                        <a href="#process" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-red-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">3. Quy trình tiếp nhận</a>
                        <a href="#conditions" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-red-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">4. Điều kiện được BH</a>
                        <a href="#exclusions" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-red-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">5. Các trường hợp từ chối</a>
                        <a href="#centers" class="toc-link block px-4 py-3 text-sm font-bold text-slate-600 hover:text-red-500 hover:bg-slate-50 rounded-xl border-l-4 border-transparent transition-all duration-200">6. Trung tâm bảo hành</a>
                    </nav>
                </aside>

                {{-- Content Section --}}
                <div class="lg:col-span-3 p-8 md:p-12 prose prose-slate max-w-none prose-headings:font-black prose-p:text-slate-600 scroll-mt-24">
                    
                    <section id="genuine">
                        <h2 class="text-3xl font-black mb-6">1. Cam kết Bảo hành Chính hãng</h2>
                        <p>
                            Mọi sản phẩm cung cấp bởi Smart Store đều là hàng chính hãng 100%. Chúng tôi cam kết thực hiện đầy đủ các quyền lợi bảo hành do nhà sản xuất quy định (Apple, Samsung, Sony, Xiaomi, v.v.).
                        </p>
                        <div class="bg-gray-900 rounded-3xl p-8 text-white flex flex-col md:flex-row items-center gap-6 not-prose shadow-2xl">
                            <div class="flex-1">
                                <h4 class="text-xl font-bold mb-2">Tra cứu Bảo hành Điện tử</h4>
                                <p class="text-slate-400 text-sm mb-4">Bạn có thể kiểm tra thời hạn bảo hành của sản phẩm bất cứ lúc nào qua số hiệu IMEI/Serial Number.</p>
                                <a href="{{ route('page.contact') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-bold transition-all">
                                    Hỗ trợ tra cứu ngay
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                            <div class="w-px h-full bg-slate-800 hidden md:block"></div>
                            <div class="flex gap-4">
                                <div class="text-center">
                                    <span class="block text-3xl font-black text-red-500">12</span>
                                    <span class="text-[10px] uppercase font-bold tracking-widest">Tháng BH</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-3xl font-black text-white">100%</span>
                                    <span class="text-[10px] uppercase font-bold tracking-widest">Chính hãng</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="duration" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">2. Thời hạn Bảo hành tiêu chuẩn</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 not-prose">
                            <div class="p-6 border border-gray-100 rounded-2xl bg-slate-50">
                                <h5 class="font-bold text-slate-900 mb-2">Điện thoại / Tablet</h5>
                                <p class="text-sm text-slate-500 mb-0">Bảo hành 12 tháng kể từ ngày kích hoạt hoặc nhận hàng.</p>
                            </div>
                            <div class="p-6 border border-gray-100 rounded-2xl bg-slate-50">
                                <h5 class="font-bold text-slate-900 mb-2">Phụ kiện (Sạc, Cáp, Tai nghe)</h5>
                                <p class="text-sm text-slate-500 mb-0">Bảo hành 06 tháng đối với lỗi nhà sản xuất.</p>
                            </div>
                            <div class="p-6 border border-gray-100 rounded-2xl bg-slate-50">
                                <h5 class="font-bold text-slate-900 mb-2">Sản phẩm Apple</h5>
                                <p class="text-sm text-slate-500 mb-0">Áp dụng chính sách bảo hành toàn cầu của Apple Inc.</p>
                            </div>
                        </div>
                    </section>

                    <section id="process" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">3. Quy trình tiếp nhận Bảo hành</h2>
                        <div class="space-y-6">
                            <div class="relative pl-12 border-l-2 border-red-100">
                                <div class="absolute -left-3.5 top-0 w-7 h-7 bg-red-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-4 border-white shadow-md">1</div>
                                <h5 class="font-bold text-slate-900">Liên hệ hỗ trợ</h5>
                                <p class="text-sm mb-0">Gọi Hotline 1900 xxxx hoặc inbox Fanpage để thông báo tình trạng lỗi.</p>
                            </div>
                            <div class="relative pl-12 border-l-2 border-red-100">
                                <div class="absolute -left-3.5 top-0 w-7 h-7 bg-red-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-4 border-white shadow-md">2</div>
                                <h5 class="font-bold text-slate-900">Gửi sản phẩm</h5>
                                <p class="text-sm mb-0">Khách hàng mang trực tiếp hoặc gửi qua đường bưu điện đến trung tâm BH.</p>
                            </div>
                            <div class="relative pl-12 border-l-2 border-red-100">
                                <div class="absolute -left-3.5 top-0 w-7 h-7 bg-red-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-4 border-white shadow-md">3</div>
                                <h5 class="font-bold text-slate-900">Kiểm tra & Xử lý</h5>
                                <p class="text-sm mb-0">Kỹ thuật viên kiểm tra lỗi và tiến hành sửa chữa/thay thế phụ kiện (3-7 ngày).</p>
                            </div>
                        </div>
                    </section>

                    <section id="conditions" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">4. Điều kiện được Bảo hành</h2>
                        <ul class="list-none pl-0 space-y-4">
                            <li class="flex items-start gap-3 p-4 bg-green-50/50 rounded-2xl border border-green-100">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-sm">Sản phẩm còn trong thời hạn bảo hành căn cứ theo phiếu BH hoặc IMEI.</span>
                            </li>
                            <li class="flex items-start gap-3 p-4 bg-green-50/50 rounded-2xl border border-green-100">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-sm">Lỗi hư hỏng được xác định do khâu sản xuất hoặc lỗi linh kiện của nhà SX.</span>
                            </li>
                            <li class="flex items-start gap-3 p-4 bg-green-50/50 rounded-2xl border border-green-100">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-sm">Tem bảo hành, số Serial/IMEI phải còn nguyên vẹn, không có dấu hiệu tẩy xóa.</span>
                            </li>
                        </ul>
                    </section>

                    <section id="exclusions" class="mt-16">
                        <h2 class="text-3xl font-black mb-6">5. Các trường hợp từ chối Bảo hành</h2>
                        <div class="bg-red-50 p-6 md:p-8 rounded-[2rem] border border-red-100 not-prose">
                            <h4 class="text-red-900 font-black mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Lưu ý quan trọng
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6">
                                <div class="flex items-center gap-2 text-sm text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> Rơi rớt, cấn móp, biến dạng.
                                </div>
                                <div class="flex items-center gap-2 text-sm text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> Máy bị vào nước, nhiễm ẩm.
                                </div>
                                <div class="flex items-center gap-2 text-sm text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> Tự ý mở máy, sửa chữa bên ngoài.
                                </div>
                                <div class="flex items-center gap-2 text-sm text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> Máy bị dính iCloud/Google Lock.
                                </div>
                                <div class="flex items-center gap-2 text-sm text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> Lỗi do thiên tai, hỏa hoạn.
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="centers" class="mt-16 pt-8 border-t border-slate-100">
                        <h2 class="text-3xl font-black mb-6">6. Trung tâm bảo hành uỷ quyền</h2>
                        <p>Dưới đây là danh sách các đơn vị tiếp nhận bảo hành chính thức của các hãng:</p>
                        <div class="flex flex-wrap gap-3 not-prose">
                            <span class="px-4 py-2 bg-slate-100 rounded-full text-xs font-bold text-slate-600">FPT Service</span>
                            <span class="px-4 py-2 bg-slate-100 rounded-full text-xs font-bold text-slate-600">Thanh Cong Service</span>
                            <span class="px-4 py-2 bg-slate-100 rounded-full text-xs font-bold text-slate-600">Samsung Care+</span>
                            <span class="px-4 py-2 bg-slate-100 rounded-full text-xs font-bold text-slate-600">Xiaomi Service Center</span>
                        </div>
                        <div class="mt-12 bg-red-600 p-8 rounded-[2rem] text-white flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative not-prose">
                            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 blur-3xl rounded-full"></div>
                            <div>
                                <h3 class="text-2xl font-black mb-2">Cần hỗ trợ kỹ thuật gấp?</h3>
                                <p class="text-red-100 text-sm">Chúng tôi luôn lắng nghe và giải quyết vấn đề của bạn nhanh nhất có thể.</p>
                            </div>
                            <div class="flex gap-4 z-10">
                                <a href="tel:1900xxxx" class="px-8 py-3 bg-white text-red-600 font-bold rounded-xl transition-all shadow-lg hover:bg-red-50">
                                    1900 xxxx
                                </a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        {{-- Footer Note --}}
        <div class="mt-8 text-center text-gray-400 text-sm">
            © {{ date('Y') }} Smart Store. Bảo hành tận tâm - Phục vụ tận tình.
        </div>
    </div>
</div>

<style>
    .prose h2 { scroll-margin-top: 100px; }
    .toc-link.active {
        @apply text-red-600 bg-red-50 border-red-500 shadow-sm shadow-red-100;
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
                link.classList.remove('text-red-600', 'bg-red-50', 'border-red-500', 'shadow-sm', 'shadow-red-100');
                link.classList.add('text-slate-600', 'border-transparent');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('text-red-600', 'bg-red-50', 'border-red-500', 'shadow-sm', 'shadow-red-100');
                    link.classList.remove('text-slate-600', 'border-transparent');
                }
            });
        });
    });
</script>
@endsection
