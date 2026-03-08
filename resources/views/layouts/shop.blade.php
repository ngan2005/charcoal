<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Pink Charcoal - Cửa Hàng Thú Cưng</title>
<link rel="icon" type="image/png" href="{{ asset('images/logo-pink-charcoal.png') }}">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#F4C2C3",
                    "primary-dark": "#e0a9aa",
                    "background-light": "#f8f7f6",
                    "background-dark": "#221910",
                },
                fontFamily: {
                    "display": ["Be Vietnam Pro", "sans-serif"]
                },
                borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
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

    /* Override Bootstrap Blue with Pink Charcoal */
    :root {
        --bs-primary: #F4C2C3;
        --bs-primary-rgb: 244, 194, 195;
        --bs-link-color: #F4C2C3;
        --bs-link-hover-color: #e0a9aa;
    }
    .btn-primary {
        --bs-btn-bg: #F4C2C3;
        --bs-btn-border-color: #F4C2C3;
        --bs-btn-hover-bg: #e0a9aa;
        --bs-btn-hover-border-color: #e0a9aa;
        --bs-btn-active-bg: #e0a9aa;
        --bs-btn-active-border-color: #e0a9aa;
        color: #1e293b !important; /* slate-900 */
    }
    .text-primary { color: #F4C2C3 !important; }
    .bg-primary { background-color: #F4C2C3 !important; }

    /* Fix Dropdown visibility conflict */
    .nav-item-holder { position: relative !important; }
    .nav-item-dropdown {
        display: none !important;
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        z-index: 9999 !important;
        background: white !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important;
        opacity: 0 !important;
        visibility: hidden !important;
        transition: opacity 0.2s ease, visibility 0.2s ease !important;
    }
    .nav-item-holder:hover > .nav-item-dropdown {
        display: flex !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
</style>
@stack('styles')
</head>
<body x-data="{ showSearch: false }" class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display min-h-screen flex flex-col">

{{-- Header --}}
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 px-10 py-3 bg-white dark:bg-slate-900 sticky top-0 z-50">
    <div class="flex items-center gap-8">
        {{-- Logo --}}
        <a href="{{ route('shop') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo-pink-charcoal.png') }}" alt="Pink Charcoal" class="h-8 w-auto">
        </a>
    </div>

    <div class="flex flex-1 justify-end gap-8">
        {{-- Nav --}}
        <nav class="hidden md:flex items-center gap-9 tracking-wide z-[60]">
            <a class="text-primary text-sm font-medium leading-normal" href="{{ route('shop') }}">Cửa Hàng</a>
            
            {{-- Dropdown Sản phẩm --}}
            <div class="nav-item-holder">
                <button class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal flex items-center gap-1 cursor-pointer">
                    Sản phẩm
                    <span class="material-symbols-outlined text-[18px]">arrow_drop_down</span>
                </button>
                
                {{-- Dropdown Menu --}}
                <div class="nav-item-dropdown mt-2 w-56 rounded-2xl py-2 flex-col">
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

            {{-- Dropdown Dịch vụ --}}
            <div class="nav-item-holder">
                <button class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal flex items-center gap-1 cursor-pointer">
                    Dịch vụ
                    <span class="material-symbols-outlined text-[18px]">arrow_drop_down</span>
                </button>
                
                {{-- Dropdown Menu --}}
                <div class="nav-item-dropdown mt-2 w-64 rounded-2xl py-2 flex-col">
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

            <a class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal" href="{{ route('about') }}">Giới thiệu</a>
            <button type="button" onclick="toggleSupportChat()" class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal">Hỗ trợ</button>
            @auth
                <a class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal"
                   href="{{ auth()->user()->RoleID == 1 ? route('admin.dashboard') : (auth()->user()->RoleID == 2 ? route('staff.dashboard') : route('dashboard')) }}">
                   Dashboard
                </a>
            @endauth
        </nav>

        {{-- Actions --}}
        <div class="flex gap-2 md:gap-4 items-center">
            @php
                $cartItemCount = 0;
                $cartItems = [];
                if (auth()->check()) {
                    $cart = \Illuminate\Support\Facades\DB::table('carts')->where('UserID', auth()->id())->first();
                    if ($cart) {
                        $cartItems = \Illuminate\Support\Facades\DB::table('cart_items')
                            ->leftJoin('products', 'cart_items.ProductID', '=', 'products.ProductID')
                            ->where('CartID', $cart->CartID)
                            ->select('cart_items.*', 'products.ProductName', 'products.Price')
                            ->get();
                        
                        // Fetch images correctly
                        foreach ($cartItems as $item) {
                            $image = \Illuminate\Support\Facades\DB::table('product_images')
                                ->where('ProductID', $item->ProductID)
                                ->orderByDesc('IsMain')
                                ->first();
                            $item->Image = $image ? $image->ImageUrl : null;
                        }
                        
                        $cartItemCount = $cartItems->sum('Quantity');
                    }
                }
            @endphp
            
            {{-- Shopping Cart --}}
            <a href="{{ route('cart.index') }}" class="relative flex cursor-pointer items-center justify-center rounded-full h-10 w-10 text-slate-700 dark:text-slate-300 hover:text-primary dark:hover:text-primary hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group">
                <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform">shopping_bag</span>
                @if($cartItemCount > 0)
                    <span class="absolute top-0.5 right-0.5 bg-rose-500 text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center border border-white dark:border-slate-900 shadow-sm">{{ $cartItemCount }}</span>
                @endif
            </a>

            {{-- Nút Tìm Kiếm --}}
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

                    <!-- Quick Login Dropdown -->
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute right-0 mt-3 w-80 sm:w-96 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] shadow-2xl z-[70] overflow-hidden">
                        
                        {{-- Dropdown Arrow --}}
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

                                {{-- reCAPTCHA placeholder/text if needed --}}
                                <p class="text-[10px] text-slate-400 leading-tight">
                                    This site is protected by reCAPTCHA and the Google 
                                    <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a> and 
                                    <a href="#" class="text-blue-500 hover:underline">Terms of Service</a> apply.
                                </p>

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

{{-- Page Content --}}
<main class="flex-1 max-w-[1440px] w-full mx-auto px-10 py-8 flex flex-col gap-8">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-8 mt-8">
    <div class="max-w-[1440px] mx-auto px-10 text-center text-slate-400 text-sm">
        <p>© 2024 Pink Charcoal Pet Store. Bảo lưu mọi quyền.</p>
    </div>
</footer>

{{-- About Modal --}}
<div id="aboutModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Overlay --}}
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('aboutModal').classList.add('hidden')"></div>
    
    {{-- Modal Panel --}}
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0 pt-24">
            <div class="relative transform overflow-visible rounded-b-3xl rounded-t-[3rem] sm:rounded-t-[4rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border-4 border-primary/20">
                
                {{-- Paw Toes Decor (Ngón chân mèo) --}}
                <div class="absolute -top-16 sm:-top-20 left-1/2 transform -translate-x-1/2 flex items-end gap-3 sm:gap-6 pointer-events-none z-30">
                    <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary/40 dark:bg-primary/20 rounded-[50%] transform -rotate-[25deg] translate-y-4 sm:translate-y-6 backdrop-blur-sm border-[3px] border-white/40"></div>
                    <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary/40 dark:bg-primary/20 rounded-[50%] transform -rotate-[10deg] backdrop-blur-sm border-[3px] border-white/40"></div>
                    <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary/40 dark:bg-primary/20 rounded-[50%] transform rotate-[10deg] backdrop-blur-sm border-[3px] border-white/40"></div>
                    <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary/40 dark:bg-primary/20 rounded-[50%] transform rotate-[25deg] translate-y-4 sm:translate-y-6 backdrop-blur-sm border-[3px] border-white/40"></div>
                </div>

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-primary/20 flex items-center justify-between bg-primary/10 rounded-t-[2.7rem] sm:rounded-t-[3.7rem]">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2" id="modal-title">
                        <span class="material-symbols-outlined text-primary">pets</span>
                        Giới thiệu về Shop Charcoal
                    </h3>
                    <button type="button" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300" onclick="document.getElementById('aboutModal').classList.add('hidden')">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                {{-- Body (Scrollable) --}}
                <div class="px-6 py-5 text-slate-600 dark:text-slate-400 text-sm leading-relaxed space-y-4 max-h-[60vh] overflow-y-auto">
                    <p class="font-medium text-slate-800 dark:text-slate-200 text-base">Nơi các boss được yêu chiều từ những điều nhỏ nhất</p>
                    
                    <p>Chào mừng bạn đến với Shop Charcoal – ngôi nhà chung dành cho mọi "sen" và các boss cưng đáng yêu tại Đồng Tháp cũng như khắp nơi! Với tên gọi lấy cảm hứng từ than hoạt tính – biểu tượng của sự tinh khiết, khử mùi và chăm sóc an toàn tuyệt đối, Charcoal không chỉ là một cửa hàng thú cưng thông thường, mà còn là người bạn đồng hành đáng tin cậy trên hành trình chăm sóc sức khỏe và hạnh phúc cho chó mèo, thỏ, hamster và mọi thú cưng nhỏ xinh trong gia đình bạn.</p>
                    
                    <p>Tại Charcoal, chúng tôi hiểu rằng mỗi bé cưng đều là một cá tính riêng biệt, xứng đáng được yêu thương và chăm sóc bằng những sản phẩm tốt nhất. Vì vậy, shop luôn ưu tiên nhập khẩu và chọn lọc kỹ lưỡng các dòng sản phẩm cao cấp: từ thức ăn hạt dinh dưỡng, thức ăn tươi, pate, sữa dành riêng cho từng giai đoạn tuổi và giống loài; đến tã lót than hoạt tính Charcoal, cát vệ sinh khử mùi siêu mạnh, dầu gội than tre, xịt khử mùi, đồ chơi an toàn, quần áo thời trang, phụ kiện và cả các dịch vụ tư vấn dinh dưỡng, chăm sóc sức khỏe tận tâm. Mọi sản phẩm đều được kiểm chứng về nguồn gốc, thành phần tự nhiên, an toàn 100% – giúp boss khỏe mạnh từ bên trong, sạch sẽ từ bên ngoài, và không còn lo mùi hôi khó chịu trong nhà.</p>
                    
                    <p>Đội ngũ Charcoal không chỉ bán hàng, mà còn là những "sen" thực thụ – luôn sẵn sàng lắng nghe câu chuyện về boss nhà bạn, chia sẻ kinh nghiệm nuôi dưỡng, gợi ý combo phù hợp với ngân sách và nhu cầu thực tế. Chúng tôi tin rằng, một chú cún vui vẻ tung tăng, một em mèo lười biếng cuộn tròn, hay một bé hamster năng động sẽ mang lại nguồn năng lượng tích cực và niềm hạnh phúc vô giá cho cả gia đình.</p>
                    
                    <p>Shop Charcoal – nơi than hoạt tính không chỉ khử mùi, mà còn khơi dậy tình yêu thương vô điều kiện dành cho thú cưng. Hãy ghé thăm chúng tôi để cùng nhau chăm sóc những người bạn bốn chân (hoặc ít chân hơn) của bạn nhé! Vì một ngôi nhà trọn vẹn niềm vui, bắt đầu từ việc yêu thương boss thật nhiều.</p>
                    
                    <p class="font-medium italic text-primary-dark">Cảm ơn bạn đã tin tưởng và đồng hành cùng Charcoal! 🐾✨</p>
                </div>
                
                {{-- Footer Info --}}
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 text-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="material-symbols-outlined text-base text-primary">call</span>
                            <span class="font-medium">Hotline/Zalo:</span> 0367196252
                        </div>
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="material-symbols-outlined text-base text-primary">location_on</span>
                            <span class="font-medium">Địa chỉ:</span> TP.HCM, Việt Nam
                        </div>
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <span class="material-symbols-outlined text-base text-primary">storefront</span>
                            <span class="font-medium">Fanpage/Shopee:</span> Shop Charcoal Thú Cưng
                        </div>
                    </div>
                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 sm:mt-0 sm:w-auto transition-colors" onclick="document.getElementById('aboutModal').classList.add('hidden')">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox Modal --}}
<div id="lightboxModal" class="hidden fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300 opacity-0 pt-20 sm:pt-24" onclick="closeLightbox(event)">
    <div class="relative max-w-4xl w-full flex flex-col items-center justify-center transform scale-95 transition-transform duration-300" id="lightboxContent">
        <button type="button" class="absolute -top-16 sm:-top-20 right-0 sm:right-4 text-white hover:text-white bg-primary/80 hover:bg-primary shadow-lg rounded-full p-2 transition-all flex items-center justify-center z-40" onclick="closeLightbox(event, true)">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>
        
        <div class="relative">
            {{-- Paw Toes Decor (Ngón chân mèo) --}}
            <div class="absolute -top-16 sm:-top-20 left-1/2 transform -translate-x-1/2 flex items-end gap-3 sm:gap-6 pointer-events-none z-30">
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary rounded-[50%] transform -rotate-[25deg] translate-y-4 sm:translate-y-6 shadow-xl border-4 border-white dark:border-slate-800"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary rounded-[50%] transform -rotate-[10deg] shadow-xl border-4 border-white dark:border-slate-800"></div>
                <div class="w-16 h-20 sm:w-24 sm:h-28 bg-primary rounded-[50%] transform rotate-[10deg] shadow-xl border-4 border-white dark:border-slate-800"></div>
                <div class="w-14 h-16 sm:w-20 sm:h-24 bg-primary rounded-[50%] transform rotate-[25deg] translate-y-4 sm:translate-y-6 shadow-xl border-4 border-white dark:border-slate-800"></div>
            </div>
            
            <img id="lightboxImage" src="" alt="Zoomed Image" class="relative z-10 max-h-[70vh] w-auto object-contain rounded-b-[2rem] rounded-t-[3rem] sm:rounded-t-[4rem] shadow-2xl border-[6px] border-primary bg-white" onclick="event.stopPropagation()">
        </div>
        
        <div class="w-full text-center mt-6">
            <h3 id="lightboxTitle" class="text-white text-2xl font-bold font-display tracking-wide drop-shadow-md"></h3>
            <p id="lightboxPrice" class="text-primary font-bold text-xl mt-1 drop-shadow-md"></p>
        </div>
    </div>
</div>

<script>
    function openLightbox(src, title = '', price = '') {
        const modal = document.getElementById('lightboxModal');
        const img = document.getElementById('lightboxImage');
        const titleEl = document.getElementById('lightboxTitle');
        const priceEl = document.getElementById('lightboxPrice');
        const content = document.getElementById('lightboxContent');
        
        img.src = src;
        titleEl.textContent = title;
        priceEl.textContent = price;
        
        modal.classList.remove('hidden');
        // trigger reflow
        void modal.offsetWidth;
        
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
        
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(e, force = false) {
        if (!force && e.target.id !== 'lightboxModal') return;
        
        const modal = document.getElementById('lightboxModal');
        const content = document.getElementById('lightboxContent');
        
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('lightboxImage').src = '';
        }, 300);
    }
</script>

@stack('scripts')

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
        {{-- Close Button --}}
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
        
        {{-- Suggestion Keywords --}}
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
{{-- Support Chat Box --}}
<div id="supportChatBox" class="fixed bottom-6 right-6 z-[100] hidden flex-col w-80 sm:w-96 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-300 translate-y-4 opacity-0">
    {{-- Chat Header --}}
    <div class="bg-primary px-5 py-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-primary text-2xl">support_agent</span>
            </div>
            <div>
                <h4 class="text-slate-900 font-bold text-sm leading-tight">Hỗ trợ khách hàng</h4>
                <p class="text-slate-800 text-[10px] uppercase tracking-wider font-semibold">Trực tuyến</p>
            </div>
        </div>
        <button type="button" onclick="toggleSupportChat()" class="text-slate-800 hover:text-black transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    {{-- Chat Messages --}}
    <div id="chatMessageContainer" class="flex-1 h-80 overflow-y-auto p-4 space-y-4 bg-slate-50/50 dark:bg-slate-800/30 no-scrollbar">
        <div class="flex flex-col gap-1 max-w-[80%]">
            <div class="bg-white dark:bg-slate-800 p-3 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 dark:border-slate-700">
                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Chào bạn! Pink Charcoal có thể giúp gì cho bạn và thú cưng của mình không ạ? 🐾</p>
            </div>
            <span class="text-[10px] text-slate-400 ml-1">Vừa xong</span>
        </div>
    </div>

    {{-- Chat Input --}}
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
        <form id="chatForm" class="flex items-center gap-2">
            <input type="text" id="chatInput" placeholder="Nhập tin nhắn..." class="flex-1 bg-slate-100 dark:bg-slate-800 border-none rounded-full px-4 py-2 text-sm focus:ring-1 focus:ring-primary placeholder:text-slate-400">
            <button type="submit" class="h-9 w-9 bg-primary hover:bg-primary-dark text-slate-900 rounded-full flex items-center justify-center shadow-sm transition-colors">
                <span class="material-symbols-outlined text-xl">send</span>
            </button>
        </form>
    </div>
</div>

<script>
    function toggleSupportChat() {
        const chatBox = document.getElementById('supportChatBox');
        if (chatBox.classList.contains('hidden')) {
            chatBox.classList.remove('hidden');
            // Trigger reflow
            void chatBox.offsetWidth;
            chatBox.classList.remove('translate-y-4', 'opacity-0');
            chatBox.classList.add('translate-y-0', 'opacity-100');
            
            // Load messages
            loadMessages();
            
            // Scroll to bottom
            const container = document.getElementById('chatMessageContainer');
            container.scrollTop = container.scrollHeight;
        } else {
            chatBox.classList.add('translate-y-4', 'opacity-0');
            chatBox.classList.remove('translate-y-0', 'opacity-100');
            setTimeout(() => {
                chatBox.classList.add('hidden');
            }, 300);
        }
    }

    async function loadMessages() {
        try {
            const response = await fetch('{{ route('support.messages') }}');
            const messages = await response.json();
            const container = document.getElementById('chatMessageContainer');
            
            // Clear existing except first welcome
            const welcome = container.firstElementChild.outerHTML;
            container.innerHTML = welcome;

            messages.forEach(msg => {
                appendMessage(msg.Message, msg.IsFromAdmin, new Date(msg.created_at));
            });
            
            container.scrollTop = container.scrollHeight;
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    // Handle chat form submission
    document.getElementById('chatForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (message) {
            appendMessage(message, false);
            input.value = '';
            
            try {
                const response = await fetch('{{ route('support.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                });
                
                if (!response.ok) throw new Error('Failed to send');
                
                // Optional: load messages again or just keep the appended one
            } catch (error) {
                console.error('Error sending message:', error);
            }
        }
    });

    function appendMessage(text, isFromAdmin, date = new Date()) {
        const container = document.getElementById('chatMessageContainer');
        const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        const messageHtml = `
            <div class="flex flex-col gap-1 ${isFromAdmin ? 'max-w-[80%]' : 'max-w-[80%] items-end ml-auto'}">
                <div class="${isFromAdmin ? 'bg-white dark:bg-slate-800 rounded-tl-none border-slate-100 dark:border-slate-700' : 'bg-primary text-slate-900 rounded-tr-none border-transparent'} p-3 rounded-2xl shadow-sm border">
                    <p class="text-sm leading-relaxed">${text}</p>
                </div>
                <span class="text-[10px] text-slate-400 ${isFromAdmin ? 'ml-1' : 'mr-1'}">${time}</span>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', messageHtml);
        container.scrollTop = container.scrollHeight;
    }
</script>
</body>
</html>
