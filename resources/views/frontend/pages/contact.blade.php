@extends('frontend.layouts.app')

@section('title', 'Liên hệ với chúng tôi - Smart Store')

@section('content')
<div class="bg-slate-50 min-h-screen">
    {{-- Hero Section --}}
    <section class="relative bg-slate-900 py-24 md:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 left-0 w-96 h-96 bg-orange-500 rounded-full blur-[150px] -ml-48 -mt-48"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600 rounded-full blur-[150px] -mr-48 -mb-48"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <span class="inline-block px-4 py-1 bg-white/10 backdrop-blur-md text-orange-400 text-xs font-bold rounded-full mb-6 uppercase tracking-[0.2em]">Contact Us</span>
            <h1 class="text-4xl md:text-7xl font-black text-white mb-6 tracking-tighter leading-tight">
                Chúng tôi luôn <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-300">Lắng nghe Bạn</span>
            </h1>
            <p class="text-slate-400 max-w-2xl mx-auto text-lg font-medium">
                Mọi ý kiến đóng góp hoặc nhu cầu hỗ trợ kỹ thuật, hãy gửi tin nhắn cho chúng tôi. Đội ngũ Smart Store sẽ phản hồi bạn trong thời gian sớm nhất.
            </p>
        </div>
    </section>

    <div class="container mx-auto px-4 -mt-20 relative z-20 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Contact Cards Column --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Phone Card --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 group hover:bg-orange-500 transition-all duration-500 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2 group-hover:text-white transition-colors tracking-tight">Tổng đài hỗ trợ</h3>
                    <p class="text-slate-500 group-hover:text-white/80 transition-colors text-sm mb-4">Hoạt động 8:00 – 22:00 hằng ngày</p>
                    <a href="tel:19001234" class="text-2xl font-black text-orange-500 group-hover:text-white transition-colors">1900 1234</a>
                </div>

                {{-- Email Card --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 group hover:bg-slate-900 transition-all duration-500 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-slate-100 text-slate-900 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2 group-hover:text-white transition-colors tracking-tight">Hòm thư điện tử</h3>
                    <p class="text-slate-500 group-hover:text-white/80 transition-colors text-sm mb-4">Hỗ trợ các vấn đề về đơn hàng</p>
                    <a href="mailto:chammut0009@gmail.com" class="text-lg font-bold text-slate-900 group-hover:text-white transition-colors break-all">chammut0009@gmail.com</a>
                </div>

                {{-- Address Card --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 group transition-all duration-500 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2 tracking-tight">Trụ sở chính</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Tòa nhà FPT Polytechnic, <br>
                        Trịnh Văn Bô, Nam Từ Liêm, Hà Nội.
                    </p>
                </div>
            </div>

            {{-- Form Column --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[3.5rem] p-8 md:p-12 shadow-2xl shadow-slate-200/60 border border-slate-100 h-full">
                    <div class="mb-10">
                        <h2 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">Gửi lời nhắn của Bạn</h2>
                        <p class="text-slate-500 font-medium italic">Chúng tôi sẽ trả lời email của bạn muộn nhất là sau 2 tiếng làm việc.</p>
                    </div>

                    @if(session('success'))
                    {{-- Toast Notification --}}
                    <div id="successToast"
                        class="fixed top-6 right-6 z-[9999] flex items-center gap-4 bg-white border border-green-200 shadow-2xl shadow-green-100 rounded-2xl px-6 py-4 max-w-sm transition-all duration-500 translate-x-0 opacity-100">
                        <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-black text-slate-900 text-sm tracking-tight">Gửi thành công! 🎉</p>
                            <p class="text-slate-500 text-xs mt-0.5">{{ session('success') }}</p>
                        </div>
                        <button onclick="document.getElementById('successToast').remove()" class="text-slate-400 hover:text-slate-600 ml-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <script>
                        setTimeout(function() {
                            const toast = document.getElementById('successToast');
                            if (toast) {
                                toast.style.opacity = '0';
                                toast.style.transform = 'translateX(120%)';
                                setTimeout(() => toast.remove(), 500);
                            }
                        }, 4000);
                    </script>
                    @endif

                    <form action="{{ route('page.contact.submit') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Họ và tên *</label>
                            <input type="text" name="name" required placeholder="Nhập tên của bạn"
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-orange-500/10 focus:bg-white transition-all outline-none text-slate-900 font-semibold">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Địa chỉ Email *</label>
                            <input type="email" name="email" required placeholder="example@gmail.com"
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-orange-500/10 focus:bg-white transition-all outline-none text-slate-900 font-semibold">
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Số điện thoại</label>
                            <input type="tel" name="phone" placeholder="0xxx xxx xxx"
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-orange-500/10 focus:bg-white transition-all outline-none text-slate-900 font-semibold">
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nội dung tin nhắn *</label>
                            <textarea name="message" required rows="6" placeholder="Bạn cần chúng tôi hỗ trợ vấn đề gì?"
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none focus:ring-4 focus:ring-orange-500/10 focus:bg-white transition-all outline-none text-slate-900 font-semibold resize-none"></textarea>
                        </div>

                        <div class="md:col-span-2 pt-4">
                            <button type="submit"
                                class="w-full md:w-auto px-12 py-5 bg-slate-900 text-white font-black rounded-2xl shadow-xl shadow-slate-900/20 hover:bg-orange-500 hover:shadow-orange-500/40 transition-all duration-300 hover:-translate-y-1">
                                Gửi yêu cầu ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Map Section --}}
        <div class="mt-12 rounded-[3.5rem] bg-white p-4 shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden group">
            <div class="h-[450px] w-full rounded-[3rem] overflow-hidden relative">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.863!2d105.7413863!3d21.0381!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313454b991d14cc5%3A0x7e1f56e1635419f!2zQ2FvIMSR4bqzbmcgRlBUIFBvbHl0ZWNobmlj!5e0!3m2!1svi!2svn!4v1711234567890"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" class="grayscale contrast-125 hover:grayscale-0 transition-all duration-1000">
                </iframe>
                <div class="absolute top-8 left-8 bg-slate-900 text-white px-6 py-4 rounded-3xl shadow-2xl pointer-events-none group-hover:-translate-y-2 transition-transform duration-500">
                    <p class="text-xs font-bold uppercase tracking-widest text-orange-400 mb-1">Our Location</p>
                    <h4 class="text-lg font-black tracking-tight">Smart Store @ Hà Nội</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
