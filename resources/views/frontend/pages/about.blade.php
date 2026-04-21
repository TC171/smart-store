@extends('frontend.layouts.app')

@section('title', 'Về chúng tôi - Smart Store')

@section('content')
<div class="bg-white min-h-screen">
    {{-- Hero Section --}}
    <section class="relative h-[60vh] flex items-center justify-center overflow-hidden bg-slate-900">
        <div class="absolute inset-0 z-0 opacity-40">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-900/80"></div>
            {{-- Placeholder for a premium tech store image --}}
            <div class="w-full h-full bg-[url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <span class="inline-block px-4 py-1 bg-orange-500 text-white text-xs font-bold rounded-full mb-6 uppercase tracking-widest animate-bounce">Chào mừng tới Smart Store</span>
            <h1 class="text-4xl md:text-7xl font-black text-white mb-6 tracking-tighter">
                Định nghĩa lại <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-300">Trải nghiệm Công nghệ</span>
            </h1>
            <p class="text-slate-300 max-w-2xl mx-auto text-lg font-medium leading-relaxed">
                Chúng tôi không chỉ bán thiết bị, chúng tôi mang tới những giải pháp công nghệ hiện đại nhất để nâng tầm cuộc sống của bạn.
            </p>
        </div>

        <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-white to-transparent"></div>
    </section>

    {{-- Stats Section --}}
    <section class="py-12 -mt-12 relative z-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 text-center transition-transform hover:-translate-y-2">
                    <h3 class="text-3xl md:text-5xl font-black text-orange-500 mb-2">10K+</h3>
                    <p class="text-xs md:text-sm font-bold text-slate-400 uppercase tracking-widest">Khách hàng tin dùng</p>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 text-center transition-transform hover:-translate-y-2">
                    <h3 class="text-3xl md:text-5xl font-black text-slate-900 mb-2">05+</h3>
                    <p class="text-xs md:text-sm font-bold text-slate-400 uppercase tracking-widest">Năm kinh nghiệm</p>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 text-center transition-transform hover:-translate-y-2">
                    <h3 class="text-3xl md:text-5xl font-black text-slate-900 mb-2">50+</h3>
                    <p class="text-xs md:text-sm font-bold text-slate-400 uppercase tracking-widest">Thương hiệu quốc tế</p>
                </div>
                <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 text-center transition-transform hover:-translate-y-2">
                    <h3 class="text-3xl md:text-5xl font-black text-slate-900 mb-2">24/7</h3>
                    <p class="text-xs md:text-sm font-bold text-slate-400 uppercase tracking-widest">Hỗ trợ kỹ thuật</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Story Section --}}
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <div class="relative">
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-orange-100 rounded-full blur-3xl opacity-50"></div>
                        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-8 leading-tight">
                            Câu chuyện của <br> <span class="text-orange-500">Smart Store</span>
                        </h2>
                        <div class="space-y-6 text-slate-600 leading-relaxed text-lg">
                            <p>
                                Bắt đầu từ một cửa hàng nhỏ đam mê các sản phẩm Apple và High-tech, Smart Store đã trải qua hành trình 5 năm không ngừng nỗ lực để trở thành điểm đến uy tín hàng đầu cho cộng đồng yêu công nghệ.
                            </p>
                            <p>
                                Chúng tôi hiểu rằng, mỗi chiếc điện thoại, mỗi chiếc laptop không đơn thuần là một cỗ máy, mà là người bạn đồng hành trong công việc, sáng tạo và giải trí của bạn.
                            </p>
                            <p class="font-bold text-slate-900 italic border-l-4 border-orange-500 pl-6">
                                "Sứ mệnh của chúng tôi là xóa nhòa khoảng cách giữa con người và công nghệ đỉnh cao."
                            </p>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div class="rounded-[2.5rem] overflow-hidden shadow-2xl transition-transform hover:scale-105 duration-500">
                                <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070&auto=format&fit=crop" alt="Smart Store Work" class="w-full h-80 object-cover">
                            </div>
                            <div class="bg-orange-500 p-8 rounded-[2.5rem] text-white">
                                <p class="text-4xl font-black mb-2">99%</p>
                                <p class="text-sm font-medium opacity-80 uppercase tracking-widest">Tỷ lệ hài lòng</p>
                            </div>
                        </div>
                        <div class="pt-12 space-y-4">
                            <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white">
                                <p class="text-4xl font-black mb-2">100%</p>
                                <p class="text-sm font-medium opacity-80 uppercase tracking-widest">Chính hãng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Values Section --}}
    <section class="py-20 bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-4xl font-black text-slate-900 mb-4">Giá trị cốt lõi</h2>
                <p class="text-slate-500 font-medium">Ba trụ cột vững chắc tạo nên sự khác biệt của chúng tôi trong lòng khách hàng.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group p-10 bg-white rounded-[2.5rem] border border-slate-100 transition-all hover:bg-orange-500 hover:border-orange-500 hover:-translate-y-4 shadow-xl shadow-slate-200/50">
                    <div class="w-16 h-16 bg-orange-100 text-orange-500 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h4 class="text-2xl font-black mb-4 group-hover:text-white transition-colors">Chất lượng Tuyệt đối</h4>
                    <p class="text-slate-500 group-hover:text-white/80 transition-colors leading-relaxed">Mọi sản phẩm trước khi đến tay bạn đều trải qua quy trình kiểm tra nghiêm ngặt 3 bước để đảm bảo hoàn hảo về cả ngoại hình lẫn hiệu năng.</p>
                </div>

                <div class="group p-10 bg-white rounded-[2.5rem] border border-slate-100 transition-all hover:bg-slate-900 hover:border-slate-900 hover:-translate-y-4 shadow-xl shadow-slate-200/50">
                    <div class="w-16 h-16 bg-slate-100 text-slate-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    </div>
                    <h4 class="text-2xl font-black mb-4 group-hover:text-white transition-colors">Tận tâm Phục vụ</h4>
                    <p class="text-slate-500 group-hover:text-white/80 transition-colors leading-relaxed">Chúng tôi không chỉ bán hàng, chúng tôi xây dựng mối quan hệ. Đội ngũ Smart Store luôn sẵn sàng lắng nghe và hỗ trợ bạn trọn đời sản phẩm.</p>
                </div>

                <div class="group p-10 bg-white rounded-[2.5rem] border border-slate-100 transition-all hover:bg-orange-600 hover:border-orange-600 hover:-translate-y-4 shadow-xl shadow-slate-200/50">
                    <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h4 class="text-2xl font-black mb-4 group-hover:text-white transition-colors">Tiên phong Công nghệ</h4>
                    <p class="text-slate-500 group-hover:text-white/80 transition-colors leading-relaxed">Luôn cập nhật những xu hướng công nghệ mới nhất thế giới để mang tới cho người Việt những sản phẩm đột phá với mức giá cạnh tranh nhất.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="bg-gradient-to-r from-orange-600 to-orange-500 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl shadow-orange-500/30">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full blur-[100px] -mr-20 -mt-20"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-5xl font-black text-white mb-8">Sẵn sàng để "Smart" hơn?</h2>
                    <p class="text-orange-50 mb-12 text-lg font-medium max-w-xl mx-auto">Hãy để chúng tôi tư vấn cho bạn món đồ công nghệ phù hợp nhất với nhu cầu và phong cách của bạn.</p>
                    <div class="flex flex-col md:flex-row justify-center gap-4">
                        <a href="{{ route('home') }}" class="px-10 py-5 bg-white text-orange-600 font-bold rounded-2xl shadow-lg hover:bg-slate-50 transition-all hover:scale-105">
                            Ghé thăm Shop ngay
                        </a>
                        <a href="{{ route('page.contact') }}" class="px-10 py-5 bg-slate-900 text-white font-bold rounded-2xl shadow-lg hover:bg-slate-800 transition-all hover:scale-105">
                            Liên hệ tư vấn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
