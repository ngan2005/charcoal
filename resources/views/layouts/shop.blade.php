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
</style>
@stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display min-h-screen flex flex-col">

{{-- Header --}}
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 px-10 py-3 bg-white dark:bg-slate-900 sticky top-0 z-50">
    <div class="flex items-center gap-8">
        {{-- Logo --}}
        <a href="{{ route('shop') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo-pink-charcoal.png') }}" alt="Pink Charcoal" class="h-8 w-auto">
        </a>

        {{-- Search --}}
        <form action="{{ route('shop') }}" method="GET" class="flex items-center min-w-40 h-10 max-w-64 w-full">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            <div class="flex w-full flex-1 items-stretch rounded-full h-full bg-slate-100 dark:bg-slate-800">
                <div class="text-slate-500 dark:text-slate-400 flex items-center justify-center pl-4 rounded-l-full">
                    <span class="material-symbols-outlined text-xl">search</span>
                </div>
                <input
                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-full text-slate-900 dark:text-slate-100 focus:outline-0 focus:ring-0 border-none bg-transparent focus:border-none h-full placeholder:text-slate-500 dark:placeholder:text-slate-400 px-4 text-sm font-normal leading-normal"
                    placeholder="Tìm kiếm sản phẩm..."
                    name="search"
                    value="{{ request('search') }}"
                />
            </div>
        </form>
    </div>

    <div class="flex flex-1 justify-end gap-8">
        {{-- Nav --}}
        <nav class="hidden md:flex items-center gap-9 tracking-wide z-[60]">
            <a class="text-primary text-sm font-medium leading-normal" href="{{ route('shop') }}">Cửa Hàng</a>
            
            {{-- Dropdown Sản phẩm --}}
            <div class="relative group">
                <button class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal flex items-center gap-1 cursor-pointer">
                    Sản phẩm
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-300 group-hover:rotate-180">arrow_drop_down</span>
                </button>
                
                {{-- Dropdown Menu --}}
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

            {{-- Dropdown Dịch vụ --}}
            <div class="relative group">
                <button class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal flex items-center gap-1 cursor-pointer">
                    Dịch vụ
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-300 group-hover:rotate-180">arrow_drop_down</span>
                </button>
                
                {{-- Dropdown Menu --}}
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

            <button onclick="document.getElementById('aboutModal').classList.remove('hidden')" class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal">Giới thiệu</button>
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

            @auth
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.index') }}" class="hidden md:block text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-primary transition-colors cursor-pointer">
                        {{ auth()->user()->FullName }}
                    </a>
                    <a href="{{ route('profile.index') }}" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 w-10 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-primary hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm border-2 border-transparent hover:border-primary/30">
                        @if(auth()->user()->Avatar)
                            <img src="{{ asset('storage/' . auth()->user()->Avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-xl text-primary">face_3</span>
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
</body>
</html>
