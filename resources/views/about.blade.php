@extends('layouts.shop')

@section('content')
{{-- Floating Paw Prints Background with Lightbox --}}
<div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
    <div class="paw-print top-20 left-10 floating-paw cursor-pointer pointer-events-auto group" style="animation-delay: 0s;" onclick="openPawLightbox(this)" data-paw="cute-cat-paws">
        <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=200&h=200&fit=crop" 
             alt="Paw" 
             class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-full shadow-lg group-hover:scale-125 transition-transform duration-300"
             onerror="this.src='https://placehold.co/100x100/F4C2C3/white?text=Paw'">
    </div>
    <div class="paw-print top-40 right-20 floating-paw cursor-pointer pointer-events-auto group" style="animation-delay: 1s;" onclick="openPawLightbox(this)" data-paw="sleeping-cat">
        <img src="https://images.unsplash.com/photo-1573865526739-10659fec78a5?w=200&h=200&fit=crop" 
             alt="Paw" 
             class="w-12 h-12 md:w-16 md:h-16 object-cover rounded-full shadow-lg group-hover:scale-125 transition-transform duration-300"
             onerror="this.src='https://placehold.co/100x100/F4C2C3/white?text=Paw'">
    </div>
    <div class="paw-print bottom-40 left-1/4 floating-paw cursor-pointer pointer-events-auto group" style="animation-delay: 2s;" onclick="openPawLightbox(this)" data-paw="kitten-eyes">
        <img src="https://images.unsplash.com/photo-1495360010541-f48722b34f7d?w=200&h=200&fit=crop" 
             alt="Paw" 
             class="w-14 h-14 md:w-20 md:h-20 object-cover rounded-full shadow-lg group-hover:scale-125 transition-transform duration-300"
             onerror="this.src='https://placehold.co/100x100/F4C2C3/white?text=Paw'">
    </div>
    <div class="paw-print bottom-20 right-1/3 floating-paw cursor-pointer pointer-events-auto group" style="animation-delay: 0.5s;" onclick="openPawLightbox(this)" data-paw="cute-puppy">
        <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=200&h=200&fit=crop" 
             alt="Paw" 
             class="w-10 h-10 md:w-14 md:h-14 object-cover rounded-full shadow-lg group-hover:scale-125 transition-transform duration-300"
             onerror="this.src='https://placehold.co/100x100/F4C2C3/white?text=Paw'">
    </div>
    <div class="paw-print top-1/3 left-20 floating-paw cursor-pointer pointer-events-auto group" style="animation-delay: 1.5s;" onclick="openPawLightbox(this)" data-paw="white-cat">
        <img src="https://images.unsplash.com/photo-1574158622682-e40e69881006?w=200&h=200&fit=crop" 
             alt="Paw" 
             class="w-12 h-12 md:w-16 md:h-16 object-cover rounded-full shadow-lg group-hover:scale-125 transition-transform duration-300"
             onerror="this.src='https://placehold.co/100x100/F4C2C3/white?text=Paw'">
    </div>
    <div class="paw-print top-1/2 right-10 floating-paw cursor-pointer pointer-events-auto group" style="animation-delay: 2.5s;" onclick="openPawLightbox(this)" data-paw="happy-dog">
        <img src="https://images.unsplash.com/photo-1517849845537-4d257902454a?w=200&h=200&fit=crop" 
             alt="Paw" 
             class="w-14 h-14 md:w-20 md:h-20 object-cover rounded-full shadow-lg group-hover:scale-125 transition-transform duration-300"
             onerror="this.src='https://placehold.co/100x100/F4C2C3/white?text=Paw'">
    </div>
</div>

{{-- Decorative Blobs --}}
<div class="fixed inset-0 pointer-events-none z-0">
    <div class="blob bg-pink-200 w-96 h-96 -top-20 -left-20"></div>
    <div class="blob bg-rose-200 w-80 h-80 top-1/2 -right-10"></div>
    <div class="blob bg-pink-100 w-64 h-64 bottom-20 left-1/3" style="animation-delay: -2s;"></div>
</div>

{{-- Main Content --}}
<div class="relative z-10 py-12 md:py-20">
    <div class="max-w-4xl mx-auto px-6">
        
        {{-- Title Section --}}
        <div class="text-center mb-12" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-1 bg-rose-100 text-rose-600 rounded-full text-sm font-medium mb-4">
                <span class="heart-beat">💕</span>
                Về chúng tôi
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white mb-4">
                Giới thiệu <span class="text-primary-dark font-display">Shop Charcoal</span>
            </h1>
            <p class="text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Nơi các boss được yêu chiều từ những điều nhỏ nhất
            </p>
        </div>

        {{-- Main Content Card --}}
        <div class="content-card rounded-[3rem] shadow-2xl overflow-hidden relative bg-white/80 dark:bg-slate-900/80 backdrop-blur-md" data-aos="fade-up" data-aos-delay="100">
            {{-- Paw Toes Decor --}}
            <div class="absolute -top-16 sm:-top-20 left-1/2 transform -translate-x-1/2 flex items-end gap-3 sm:gap-6 pointer-events-none z-30">
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary/40 rounded-[50%] transform -rotate-[25deg] translate-y-4 sm:translate-y-6 backdrop-blur-sm border-[3px] border-white/40"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary/40 rounded-[50%] transform -rotate-[10deg] backdrop-blur-sm border-[3px] border-white/40"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary/40 rounded-[50%] transform rotate-[10deg] backdrop-blur-sm border-[3px] border-white/40"></div>
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary/40 rounded-[50%] transform rotate-[25deg] translate-y-4 sm:translate-y-6 backdrop-blur-sm border-[3px] border-white/40"></div>
            </div>

            <div class="px-8 md:px-12 py-12 md:py-16">
                {{-- Intro --}}
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white mb-4">
                        Chào mừng bạn đến với Shop Charcoal
                    </h2>
                    <p class="text-lg text-primary-dark font-medium">
                        Ngôi nhà chung dành cho mọi "sen" và các boss cưng đáng yêu
                    </p>
                </div>

                {{-- Content Paragraphs --}}
                <div class="space-y-6 text-slate-600 dark:text-slate-400 leading-relaxed text-lg text-justify">
                    <p data-aos="fade-up" data-aos-delay="100">
                        Với tên gọi lấy cảm hứng từ <strong class="text-primary-dark">than hoạt tính</strong> – biểu tượng của sự tinh khiết, khử mùi và chăm sóc an toàn tuyệt đối, Charcoal không chỉ là một cửa hàng thú cưng thông thường, mà còn là người bạn đồng hành đáng tin cậy trên hành trình chăm sóc sức khỏe và hạnh phúc cho <strong>chó mèo, thỏ, hamster</strong> và mọi thú cưng nhỏ xinh trong gia đình bạn.
                    </p>

                    <div class="bg-rose-50 dark:bg-slate-800/50 rounded-3xl p-6 my-8 shadow-inner" data-aos="fade-up" data-aos-delay="200">
                        <div class="grid md:grid-cols-3 gap-6 text-center">
                            <div>
                                <div class="text-3xl mb-2">🍖</div>
                                <div class="font-bold text-slate-800 dark:text-white">Thức ăn</div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">Hạt dinh dưỡng, pate, sữa</div>
                            </div>
                            <div>
                                <div class="text-3xl mb-2">🧹</div>
                                <div class="font-bold text-slate-800 dark:text-white">Vệ sinh</div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">Tã lót, cát khử mùi</div>
                            </div>
                            <div>
                                <div class="text-3xl mb-2">🛁</div>
                                <div class="font-bold text-slate-800 dark:text-white">Chăm sóc</div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">Dầu gội, xịt khử mùi</div>
                            </div>
                        </div>
                    </div>

                    <p data-aos="fade-up" data-aos-delay="300">
                        Tại Charcoal, chúng tôi hiểu rằng mỗi bé cưng đều là một <strong class="text-slate-800 dark:text-white">cá tính riêng biệt</strong>, xứng đáng được yêu thương và chăm sóc bằng những sản phẩm tốt nhất. Vì vậy, shop luôn ưu tiên nhập khẩu và chọn lọc kỹ lưỡng các dòng sản phẩm cao cấp: thức ăn hạt dinh dưỡng, thức ăn tươi, pate, sữa... và <strong>tã lót than hoạt tính Charcoal</strong>.
                    </p>

                    <p data-aos="fade-up" data-aos-delay="400">
                        Mọi sản phẩm đều được kiểm chứng về nguồn gốc, thành phần tự nhiên, <strong class="text-primary-dark">an toàn 100%</strong> – giúp boss khỏe mạnh từ bên trong, sạch sẽ từ bên ngoài.
                    </p>

                    <div class="bg-gradient-to-r from-rose-100 to-pink-100 dark:from-slate-800 dark:to-slate-900 rounded-3xl p-6 my-8 border border-primary/20" data-aos="fade-up" data-aos-delay="500">
                        <div class="flex items-center gap-4">
                            <div class="text-4xl">👨‍💼</div>
                            <div>
                                <div class="font-bold text-slate-800 dark:text-white text-lg">Đội ngũ "sen" thực thụ</div>
                                <div class="text-slate-600 dark:text-slate-400">Luôn sẵn sàng lắng nghe và hỗ trợ bạn</div>
                            </div>
                        </div>
                    </div>

                    <p data-aos="fade-up" data-aos-delay="600">
                        Đội ngũ Charcoal luôn sẵn sàng lắng nghe câu chuyện về boss nhà bạn, chia sẻ kinh nghiệm nuôi dưỡng tận tâm.
                    </p>

                    <p class="text-center text-xl font-medium text-primary-dark italic mt-10" data-aos="fade-up" data-aos-delay="800">
                        Cảm ơn bạn đã tin tưởng và đồng hành cùng Charcoal! 
                        <span class="inline-block ml-2 heart-beat">🐾✨</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="mt-12 grid md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="300">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-lg text-center hover:shadow-xl transition-shadow border border-slate-100 dark:border-slate-800">
                <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-primary-dark">call</span>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white mb-2">Hotline / Zalo</h3>
                <p class="text-primary-dark font-medium">0367196252</p>
            </div>
            
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-lg text-center hover:shadow-xl transition-shadow border border-slate-100 dark:border-slate-800">
                <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-primary-dark">location_on</span>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white mb-2">Địa chỉ</h3>
                <p class="text-primary-dark font-medium">TP.HCM, Việt Nam</p>
            </div>
            
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-lg text-center hover:shadow-xl transition-shadow border border-slate-100 dark:border-slate-800">
                <div class="w-14 h-14 bg-rose-100 dark:bg-rose-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-primary-dark">storefront</span>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white mb-2">Fanpage / Shopee</h3>
                <p class="text-primary-dark font-medium">Shop Charcoal Thú Cưng</p>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="mt-12 flex flex-wrap justify-center gap-4" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ route('shop') }}" class="px-8 py-4 bg-primary hover:bg-primary-dark text-slate-900 font-bold rounded-2xl transition-all shadow-lg hover:-translate-y-1 flex items-center gap-2">
                <span class="material-symbols-outlined">shopping_bag</span>
                Khám phá shop
            </a>
            <a href="{{ route('services.index') }}" class="px-8 py-4 bg-white dark:bg-slate-800 hover:bg-primary/10 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all shadow-lg hover:-translate-y-1 flex items-center gap-2 border border-slate-100 dark:border-slate-700">
                <span class="material-symbols-outlined">spa</span>
                Xem dịch vụ
            </a>
        </div>
    </div>
</div>

{{-- Paw Lightbox Modal --}}
<div id="pawLightbox" class="hidden fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300 opacity-0 pt-20" onclick="closePawLightbox()">
    <div id="pawLightboxContent" class="relative max-w-xl w-full flex flex-col items-center justify-center transform scale-95 transition-transform duration-300" onclick="event.stopPropagation()">
        <button type="button" class="absolute -top-16 right-0 sm:right-4 text-white hover:text-white bg-primary/80 hover:bg-primary shadow-lg rounded-full p-2 transition-all flex items-center justify-center z-40" onclick="closePawLightbox()">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>
        
        <div class="relative">
            {{-- Paw Toes Decor --}}
            <div class="absolute -top-16 sm:-top-20 left-1/2 transform -translate-x-1/2 flex items-end gap-3 sm:gap-6 pointer-events-none z-30">
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary rounded-[50%] transform -rotate-[25deg] translate-y-4 sm:translate-y-6 shadow-xl border-4 border-white dark:border-slate-800"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary rounded-[50%] transform -rotate-[10deg] shadow-xl border-4 border-white dark:border-slate-800"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary rounded-[50%] transform rotate-[10deg] shadow-xl border-4 border-white dark:border-slate-800"></div>
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary rounded-[50%] transform rotate-[25deg] translate-y-4 sm:translate-y-6 shadow-xl border-4 border-white dark:border-slate-800"></div>
            </div>
            
            <img id="pawLightboxImg" src="" alt="Paw Image" class="relative z-10 max-h-[50vh] w-auto object-contain rounded-b-[2rem] rounded-t-[3rem] sm:rounded-t-[4rem] shadow-2xl border-[6px] border-primary bg-white">
        </div>
        
        <div class="w-full text-center mt-6">
            <h3 id="pawLightboxTitle" class="text-white text-2xl font-bold font-display tracking-wide drop-shadow-md"></h3>
            <p id="pawLightboxDesc" class="text-primary font-medium mt-1 drop-shadow-md"></p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #fff5f5 0%, #ffe4e8 50%, #ffccd5 100%);
    }
    
    .card-hover {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .card-hover:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(244, 194, 195, 0.4);
    }
    
    .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.5;
        animation: float 8s ease-in-out infinite;
    }
    
    .paw-print {
        position: absolute;
        opacity: 0.15;
        animation: pulse-soft 3s ease-in-out infinite;
    }
    
    .heart-beat {
        animation: wiggle 1s ease-in-out infinite;
    }
    
    .floating-paw {
        animation: float 4s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    @keyframes wiggle {
        0%, 100% { transform: rotate(-3deg); }
        50% { transform: rotate(3deg); }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 100,
        easing: 'ease-out-cubic',
    });

    // Paw Lightbox Functionality
    const pawLightboxData = {
        'cute-cat-paws': {
            src: 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=800&h=800&fit=crop',
            title: 'Chân mèo dễ thương',
            desc: 'Những chú mèo đáng yêu'
        },
        'sleeping-cat': {
            src: 'https://images.unsplash.com/photo-1573865526739-10659fec78a5?w=800&h=800&fit=crop',
            title: 'Mèo đang ngủ',
            desc: 'Giấc ngủ ngon lành'
        },
        'kitten-eyes': {
            src: 'https://images.unsplash.com/photo-1495360010541-f48722b34f7d?w=800&h=800&fit=crop',
            title: 'Mèo con',
            desc: 'Đôi mắt long lanh'
        },
        'cute-puppy': {
            src: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&h=800&fit=crop',
            title: 'Chú chó con',
            desc: 'Boss nhỏ đáng yêu'
        },
        'white-cat': {
            src: 'https://images.unsplash.com/photo-1574158622682-e40e69881006?w=800&h=800&fit=crop',
            title: 'Mèo trắng',
            desc: 'White beauty'
        },
        'happy-dog': {
            src: 'https://images.unsplash.com/photo-1517849845537-4d257902454a?w=800&h=800&fit=crop',
            title: 'Chó hạnh phúc',
            desc: 'Nụ cười hạnh phúc'
        }
    };

    function openPawLightbox(element) {
        const pawType = element.getAttribute('data-paw');
        const data = pawLightboxData[pawType];
        
        if (data) {
            document.getElementById('pawLightboxImg').src = data.src;
            document.getElementById('pawLightboxTitle').textContent = data.title;
            document.getElementById('pawLightboxDesc').textContent = data.desc;
            
            const lightbox = document.getElementById('pawLightbox');
            lightbox.classList.remove('hidden');
            setTimeout(() => {
                lightbox.classList.remove('opacity-0');
                document.getElementById('pawLightboxContent').classList.remove('scale-95');
                document.getElementById('pawLightboxContent').classList.add('scale-100');
            }, 10);
            document.body.style.overflow = 'hidden';
        }
    }

    function closePawLightbox() {
        const lightbox = document.getElementById('pawLightbox');
        if (!lightbox) return;
        lightbox.classList.add('opacity-0');
        document.getElementById('pawLightboxContent').classList.remove('scale-100');
        document.getElementById('pawLightboxContent').classList.add('scale-95');
        
        setTimeout(() => {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }
</script>
@endpush
