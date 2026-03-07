<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Giới thiệu - Pink Charcoal Pet Store</title>
<link rel="icon" type="image/png" href="{{ asset('images/logo-pink-charcoal.png') }}">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#F4C2C3",
                    "primary-dark": "#e0a9aa",
                    "secondary": "#FFE5E5",
                    "accent": "#FF9E9E",
                    "background-light": "#f8f7f6",
                    "background-dark": "#221910",
                },
                fontFamily: {
                    "display": ["Be Vietnam Pro", "sans-serif"]
                },
                borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
                animation: {
                    'float': 'float 6s ease-in-out infinite',
                    'float-delayed': 'float 6s ease-in-out 2s infinite',
                    'wiggle': 'wiggle 1s ease-in-out infinite',
                    'bounce-slow': 'bounce-slow 3s ease-in-out infinite',
                    'pulse-soft': 'pulse-soft 2s ease-in-out infinite',
                    'slide-up': 'slide-up 0.8s ease-out',
                    'fade-in': 'fade-in 1s ease-out',
                },
                keyframes: {
                    float: {
                        '0%, 100%': { transform: 'translateY(0)' },
                        '50%': { transform: 'translateY(-20px)' },
                    },
                    wiggle: {
                        '0%, 100%': { transform: 'rotate(-3deg)' },
                        '50%': { transform: 'rotate(3deg)' },
                    },
                    'bounce-slow': {
                        '0%, 100%': { transform: 'translateY(0)' },
                        '50%': { transform: 'translateY(-10px)' },
                    },
                    'pulse-soft': {
                        '0%, 100%': { opacity: '1' },
                        '50%': { opacity: '0.7' },
                    },
                    'slide-up': {
                        '0%': { transform: 'translateY(50px)', opacity: '0' },
                        '100%': { transform: 'translateY(0)', opacity: '1' },
                    },
                    'fade-in': {
                        '0%': { opacity: '0' },
                        '100%': { opacity: '1' },
                    },
                },
            },
        },
    }
</script>
<style>
    body { font-family: 'Be Vietnam Pro', sans-serif; }
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
    
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
    
    .hero-pattern {
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(244, 194, 195, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 158, 158, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(255, 229, 229, 0.5) 0%, transparent 30%);
    }
    
    .text-gradient {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF9E9E 50%, #F4C2C3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .heart-beat {
        animation: wiggle 1s ease-in-out infinite;
    }
    
    .scroll-indicator {
        animation: bounce-slow 2s ease-in-out infinite;
    }
    
    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #F4C2C3, #FF9E9E, #F4C2C3);
        border-radius: 1rem 1rem 0 0;
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .service-card:hover::before {
        transform: scaleX(1);
    }
    
    .floating-paw {
        animation: float 4s ease-in-out infinite;
    }
    
    .content-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
        backdrop-filter: blur(10px);
    }
</style>
@stack('styles')
</head>
<body x-data="{ showSearch: false }" class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display min-h-screen flex flex-col overflow-x-hidden">

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

{{-- Header - Same as shop layout --}}
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 px-6 md:px-10 py-3 bg-white dark:bg-slate-900 sticky top-0 z-50">
    <div class="flex items-center gap-8">
        <a href="{{ route('shop') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo-pink-charcoal.png') }}" alt="Pink Charcoal" class="h-8 w-auto">
        </a>
    </div>

    <div class="flex flex-1 justify-end gap-8">
        <nav class="hidden md:flex items-center gap-9 tracking-wide z-[60]">
            <a class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal" href="{{ route('shop') }}">Cửa Hàng</a>
            
            <div class="relative group">
                <button class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal flex items-center gap-1 cursor-pointer">
                    Sản phẩm
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-300 group-hover:rotate-180">arrow_drop_down</span>
                </button>
                <div class="absolute top-full left-0 mt-3 w-56 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 py-2 flex flex-col translate-y-2 group-hover:translate-y-0">
                    <a href="{{ route('shop') }}" class="px-5 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors font-medium border-b border-slate-50 dark:border-slate-800/50">
                        Tất cả sản phẩm
                    </a>
                    @foreach(\App\Models\Category::all() as $category)
                        <a href="{{ route('shop', ['category' => $category->CategoryID]) }}" class="px-5 py-2.5 text-sm text-slate-600 dark:text-slate-400 hover:bg-primary/10 hover:text-primary transition-colors">
                            {{ $category->CategoryName }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="relative group">
                <button class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal flex items-center gap-1 cursor-pointer">
                    Dịch vụ
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-300 group-hover:rotate-180">arrow_drop_down</span>
                </button>
                <div class="absolute top-full left-0 mt-3 w-64 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 py-2 flex flex-col translate-y-2 group-hover:translate-y-0">
                    <a href="{{ route('services.index') }}" class="px-5 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-colors font-medium border-b border-slate-50 dark:border-slate-800/50">
                        Top Dịch vụ
                    </a>
                    @foreach(\App\Models\Service::all() as $service)
                        <a href="{{ route('services.show', $service->ServiceID) }}" class="px-5 py-2.5 text-sm text-slate-600 dark:text-slate-400 hover:bg-primary/10 hover:text-primary transition-colors truncate">
                            {{ $service->ServiceName }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a class="text-primary text-sm font-medium leading-normal" href="{{ route('about') }}">Giới thiệu</a>
            @auth
                <a class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal"
                   href="{{ auth()->user()->RoleID == 1 ? route('admin.dashboard') : (auth()->user()->RoleID == 2 ? route('staff.dashboard') : route('dashboard')) }}">
                   Dashboard
                </a>
            @endauth
        </nav>

        <div class="flex gap-2 md:gap-4 items-center">
            @php
                $cartItemCount = 0;
                if (auth()->check()) {
                    $cart = \Illuminate\Support\Facades\DB::table('carts')->where('UserID', auth()->id())->first();
                    if ($cart) {
                        $cartItemCount = \Illuminate\Support\Facades\DB::table('cart_items')->where('CartID', $cart->CartID)->sum('Quantity');
                    }
                }
            @endphp
            
            <a href="{{ route('cart.index') }}" class="relative flex cursor-pointer items-center justify-center rounded-full h-10 w-10 text-slate-700 dark:text-slate-300 hover:text-primary dark:hover:text-primary hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group">
                <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform">shopping_bag</span>
                @if($cartItemCount > 0)
                    <span class="absolute top-0.5 right-0.5 bg-rose-500 text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center border border-white dark:border-slate-900 shadow-sm">{{ $cartItemCount }}</span>
                @endif
            </a>

            <button type="button" @click="showSearch = true" class="flex items-center justify-center h-10 w-10 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition-colors group">
                <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform">search</span>
            </button>

            @auth
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.index') }}" class="hidden md:block text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-primary transition-colors cursor-pointer">
                        {{ auth()->user()->FullName }}
                    </a>
                    <a href="{{ route('profile.index') }}" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 w-10 bg-primary/10 text-slate-700 dark:text-slate-300 hover:bg-primary/20 transition-colors shadow-sm border-2 border-primary/30">
                        @php
                            $avatarUrl = null;
                            if (auth()->user()->Avatar) {
                                $avatar = auth()->user()->Avatar;
                                if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
                                    $avatarUrl = $avatar;
                                } else {
                                    $avatarUrl = asset('storage/' . $avatar);
                                }
                            }
                        @endphp
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <span class="material-symbols-outlined text-xl text-primary hidden items-center justify-center">person</span>
                        @else
                            <span class="text-primary font-bold text-sm">{{ strtoupper(substr(auth()->user()->FullName, 0, 2)) }}</span>
                        @endif
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hidden md:flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-9 px-4 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-sm font-medium">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            @else
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" 
                       class="flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 w-10 bg-primary text-slate-900 font-bold text-sm hover:bg-primary-dark transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">person</span>
                    </button>
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute right-0 mt-3 w-80 sm:w-96 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] shadow-2xl z-[70] overflow-hidden">
                        <div class="absolute -top-2 right-4 w-4 h-4 bg-white dark:bg-slate-900 rotate-45 border-l border-t border-slate-100 dark:border-slate-800"></div>
                        <div class="p-8">
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-wider">ĐĂNG NHẬP TÀI KHOẢN</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Nhập email và mật khẩu của bạn:</p>
                            </div>
                            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <input type="email" name="Email" required
                                           class="w-full px-5 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-sm placeholder:text-slate-400"
                                           placeholder="Email">
                                </div>
                                <div>
                                    <input type="password" name="Password" required
                                           class="w-full px-5 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-sm placeholder:text-slate-400"
                                           placeholder="Mật khẩu">
                                </div>
                                <button type="submit" 
                                        class="w-full py-3.5 bg-[#d4d4d4] hover:bg-primary hover:text-white text-slate-700 font-bold rounded-xl transition-all uppercase tracking-widest text-sm shadow-md">
                                    ĐĂNG NHẬP
                                </button>
                            </form>
                            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 text-center space-y-2">
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    Khách hàng mới? 
                                    <a href="{{ route('register-customer') }}" class="text-rose-400 hover:underline font-medium">Tạo tài khoản</a>
                                </p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    Quên mật khẩu? 
                                    <a href="{{ route('forgot-password') }}" class="text-rose-400 hover:underline font-medium">Khôi phục mật khẩu</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>

{{-- Main Content --}}
<main class="relative z-10 py-12 md:py-20">
    <div class="max-w-4xl mx-auto px-6">
        
        {{-- Title Section --}}
        <div class="text-center mb-12" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-1 bg-rose-100 text-rose-600 rounded-full text-sm font-medium mb-4">
                <span class="heart-beat">💕</span>
                Về chúng tôi
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-slate-800 mb-4">
                Giới thiệu <span class="text-rose-500">Shop Charcoal</span>
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                Nơi các boss được yêu chiều từ những điều nhỏ nhất
            </p>
        </div>

        {{-- Main Content Card --}}
        <div class="content-card rounded-[3rem] shadow-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="100">
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
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4">
                        Chào mừng bạn đến với Shop Charcoal
                    </h2>
                    <p class="text-lg text-rose-500 font-medium">
                        Ngôi nhà chung dành cho mọi "sen" và các boss cưng đáng yêu
                    </p>
                </div>

                {{-- Content Paragraphs --}}
                <div class="space-y-6 text-slate-600 leading-relaxed text-lg">
                    <p data-aos="fade-up" data-aos-delay="100">
                        Với tên gọi lấy cảm hứng từ <strong class="text-rose-500">than hoạt tính</strong> – biểu tượng của sự tinh khiết, khử mùi và chăm sóc an toàn tuyệt đối, Charcoal không chỉ là một cửa hàng thú cưng thông thường, mà còn là người bạn đồng hành đáng tin cậy trên hành trình chăm sóc sức khỏe và hạnh phúc cho <strong>chó mèo, thỏ, hamster</strong> và mọi thú cưng nhỏ xinh trong gia đình bạn.
                    </p>

                    <div class="bg-rose-50 rounded-3xl p-6 my-8" data-aos="fade-up" data-aos-delay="200">
                        <div class="grid md:grid-cols-3 gap-6 text-center">
                            <div>
                                <div class="text-3xl mb-2">🍖</div>
                                <div class="font-bold text-slate-800">Thức ăn</div>
                                <div class="text-sm text-slate-500">Hạt dinh dưỡng, pate, sữa</div>
                            </div>
                            <div>
                                <div class="text-3xl mb-2">🧹</div>
                                <div class="font-bold text-slate-800">Vệ sinh</div>
                                <div class="text-sm text-slate-500">Tã lót, cát khử mùi</div>
                            </div>
                            <div>
                                <div class="text-3xl mb-2">🛁</div>
                                <div class="font-bold text-slate-800">Chăm sóc</div>
                                <div class="text-sm text-slate-500">Dầu gội, xịt khử mùi</div>
                            </div>
                        </div>
                    </div>

                    <p data-aos="fade-up" data-aos-delay="300">
                        Tại Charcoal, chúng tôi hiểu rằng mỗi bé cưng đều là một <strong class="text-slate-800">cá tính riêng biệt</strong>, xứng đáng được yêu thương và chăm sóc bằng những sản phẩm tốt nhất. Vì vậy, shop luôn ưu tiên nhập khẩu và chọn lọc kỹ lưỡng các dòng sản phẩm cao cấp: từ thức ăn hạt dinh dưỡng, thức ăn tươi, pate, sữa dành riêng cho từng giai đoạn tuổi và giống loài; đến <strong>tã lót than hoạt tính Charcoal</strong>, cát vệ sinh khử mùi siêu mạnh, dầu gội than tre, xịt khử mùi, đồ chơi an toàn, quần áo thời trang, phụ kiện và cả các dịch vụ tư vấn dinh dưỡng, chăm sóc sức khỏe tận tâm.
                    </p>

                    <p data-aos="fade-up" data-aos-delay="400">
                        Mọi sản phẩm đều được kiểm chứng về nguồn gốc, thành phần tự nhiên, <strong class="text-rose-500">an toàn 100%</strong> – giúp boss khỏe mạnh từ bên trong, sạch sẽ từ bên ngoài, và không còn lo mùi hôi khó chịu trong nhà.
                    </p>

                    <div class="bg-gradient-to-r from-rose-100 to-pink-100 rounded-3xl p-6 my-8" data-aos="fade-up" data-aos-delay="500">
                        <div class="flex items-center gap-4">
                            <div class="text-4xl">👨‍💼</div>
                            <div>
                                <div class="font-bold text-slate-800 text-lg">Đội ngũ "sen" thực thụ</div>
                                <div class="text-slate-600">Luôn sẵn sàng lắng nghe và hỗ trợ bạn</div>
                            </div>
                        </div>
                    </div>

                    <p data-aos="fade-up" data-aos-delay="600">
                        Đội ngũ Charcoal không chỉ bán hàng, mà còn là những <strong class="text-slate-800">"sen" thực thụ</strong> – luôn sẵn sàng lắng nghe câu chuyện về boss nhà bạn, chia sẻ kinh nghiệm nuôi dưỡng, gợi ý combo phù hợp với ngân sách và nhu cầu thực tế. Chúng tôi tin rằng, một chú cún vui vẻ tung tăng, một em mèo lười biếng cuộn tròn, hay một bé hamster năng động sẽ mang lại nguồn năng lượng tích cực và niềm hạnh phúc vô giá cho cả gia đình.
                    </p>

                    <p data-aos="fade-up" data-aos-delay="700">
                        <strong class="text-rose-500">Shop Charcoal</strong> – nơi than hoạt tính không chỉ khử mùi, mà còn khơi dậy tình yêu thương vô điều kiện dành cho thú cưng. Hãy ghé thăm chúng tôi để cùng nhau chăm sóc những người bạn bốn chân (hoặc ít chân hơn) của bạn nhé! Vì một ngôi nhà trọn vẹn niềm vui, bắt đầu từ việc yêu thương boss thật nhiều.
                    </p>

                    <p class="text-center text-xl font-medium text-rose-500 italic mt-10" data-aos="fade-up" data-aos-delay="800">
                        Cảm ơn bạn đã tin tưởng và đồng hành cùng Charcoal! 
                        <span class="inline-block ml-2">🐾✨</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="mt-12 grid md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="300">
            <div class="bg-white rounded-2xl p-6 shadow-lg text-center hover:shadow-xl transition-shadow">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-rose-500">call</span>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Hotline / Zalo</h3>
                <p class="text-rose-500 font-medium">0367196252</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg text-center hover:shadow-xl transition-shadow">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-rose-500">location_on</span>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Địa chỉ</h3>
                <p class="text-rose-500 font-medium">TP.HCM, Việt Nam</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg text-center hover:shadow-xl transition-shadow">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-rose-500">storefront</span>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Fanpage / Shopee</h3>
                <p class="text-rose-500 font-medium">Shop Charcoal Thú Cưng</p>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="mt-12 flex flex-wrap justify-center gap-4" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ route('shop') }}" class="px-8 py-4 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl transition-all hover:shadow-xl hover:shadow-rose-300/50 hover:-translate-y-1 flex items-center gap-2">
                <span class="material-symbols-outlined">shopping_bag</span>
                Khám phá shop
            </a>
            <a href="{{ route('services.index') }}" class="px-8 py-4 bg-white hover:bg-rose-50 text-slate-700 font-bold rounded-2xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 flex items-center gap-2">
                <span class="material-symbols-outlined">spa</span>
                Xem dịch vụ
            </a>
            <a href="tel:0367196252" class="px-8 py-4 bg-rose-100 hover:bg-rose-200 text-rose-600 font-bold rounded-2xl transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">call</span>
                Liên hệ ngay
            </a>
        </div>

    </div>
</main>

{{-- Footer --}}
<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-8 mt-12">
    <div class="max-w-[1440px] mx-auto px-10 text-center text-slate-400 text-sm">
        <p>© 2024 Pink Charcoal Pet Store. Bảo lưu mọi quyền.</p>
    </div>
</footer>

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
        lightbox.classList.add('opacity-0');
        document.getElementById('pawLightboxContent').classList.remove('scale-100');
        document.getElementById('pawLightboxContent').classList.add('scale-95');
        
        setTimeout(() => {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    // Close lightbox on overlay click
    document.getElementById('pawLightbox').addEventListener('click', function(e) {
        if (e.target === this) {
            closePawLightbox();
        }
    });

    // Close lightbox on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePawLightbox();
        }
    });
</script>

{{-- Paw Lightbox Modal --}}
<div id="pawLightbox" class="hidden fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300 opacity-0 pt-20" onclick="closePawLightbox()">
    <div id="pawLightboxContent" class="relative max-w-2xl w-full flex flex-col items-center justify-center transform scale-95 transition-transform duration-300" onclick="event.stopPropagation()">
        <button type="button" class="absolute -top-16 right-0 sm:right-4 text-white hover:text-white bg-primary/80 hover:bg-primary shadow-lg rounded-full p-2 transition-all flex items-center justify-center z-40" onclick="closePawLightbox()">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>
        
        <div class="relative">
            {{-- Paw Toes Decor --}}
            <div class="absolute -top-16 sm:-top-20 left-1/2 transform -translate-x-1/2 flex items-end gap-3 sm:gap-6 pointer-events-none z-30">
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary rounded-[50%] transform -rotate-[25deg] translate-y-4 sm:translate-y-6 shadow-xl border-4 border-white"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary rounded-[50%] transform -rotate-[10deg] shadow-xl border-4 border-white"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary rounded-[50%] transform rotate-[10deg] shadow-xl border-4 border-white"></div>
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary rounded-[50%] transform rotate-[25deg] translate-y-4 sm:translate-y-6 shadow-xl border-4 border-white"></div>
            </div>
            
            <img id="pawLightboxImg" src="" alt="Paw Image" class="relative z-10 max-h-[60vh] w-auto object-contain rounded-b-[2rem] rounded-t-[3rem] sm:rounded-t-[4rem] shadow-2xl border-[6px] border-primary bg-white">
        </div>
        
        <div class="w-full text-center mt-6">
            <h3 id="pawLightboxTitle" class="text-white text-2xl font-bold font-display tracking-wide drop-shadow-md"></h3>
            <p id="pawLightboxDesc" class="text-primary font-medium mt-1 drop-shadow-md"></p>
        </div>
    </div>
</div>

{{-- Search Overlay --}}
<div x-show="showSearch" 
     x-cloak
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="-translate-y-full opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="translate-y-0 opacity-100"
     x-transition:leave-end="-translate-y-full opacity-0"
     class="fixed inset-x-0 top-0 z-[100] bg-white/95 dark:bg-slate-900/95 backdrop-blur-md shadow-lg border-b border-slate-200/50 dark:border-slate-700/50 py-4">
    <div class="max-w-3xl mx-auto px-6 relative">
        <button type="button" @click="showSearch = false" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-full transition-all">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
        <form action="{{ route('shop') }}" method="GET" class="relative group">
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                    <span class="material-symbols-outlined">search</span>
                </span>
                <input type="text" 
                       name="search" 
                       placeholder="Tìm kiếm sản phẩm..." 
                       autofocus
                       class="w-full bg-slate-50 dark:bg-slate-800/50 border-2 border-slate-200 dark:border-slate-700 focus:border-primary rounded-full px-12 py-2.5 text-sm font-medium outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-inner">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary hover:bg-rose-400 text-white rounded-full px-4 py-1.5 text-sm font-medium transition-colors">
                    Tìm
                </button>
            </div>
        </form>
        <div class="flex flex-wrap justify-center items-center gap-2 mt-3 text-xs">
            <span class="text-slate-400 font-medium mr-1">Gợi ý:</span>
            <a href="{{ route('shop', ['search' => 'Thức ăn']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Thức ăn</a>
            <a href="{{ route('shop', ['search' => 'Bánh thưởng']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Bánh thưởng</a>
            <a href="{{ route('shop', ['search' => 'Cát mèo']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Cát mèo</a>
            <a href="{{ route('shop', ['search' => 'Đồ chơi']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Đồ chơi</a>
            <a href="{{ route('shop', ['search' => 'Phụ kiện']) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-primary/20 hover:text-primary dark:hover:text-primary rounded-full transition-colors">Phụ kiện</a>
        </div>
    </div>
</div>

{{-- Backdrop for Search --}}
<div x-show="showSearch" 
     x-cloak
     x-transition.opacity
     @click="showSearch = false"
     class="fixed inset-0 z-[90] bg-slate-900/40 backdrop-blur-sm"></div>

@stack('scripts')
</body>
</html>
