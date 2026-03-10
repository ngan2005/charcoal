{{-- Header --}}
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 px-10 py-3 bg-white dark:bg-slate-900 sticky top-0 z-50">
    <div class="flex items-center gap-8">
        {{-- Logo --}}
        <a href="{{ route('shop') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo-pink-charcoal.png') }}" alt="Pink Charcoal" class="h-14 w-auto">
        </a>
    </div>

    <div class="flex flex-1 justify-end gap-8">
        {{-- Nav --}}
        <nav class="hidden md:flex items-center gap-9 tracking-wide z-[60]">
            <a class="{{ request()->routeIs('shop') ? 'text-primary' : 'text-slate-900 dark:text-slate-100' }} hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal" href="{{ route('shop') }}">Cửa Hàng</a>
            
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

            <a class="{{ request()->routeIs('about') ? 'text-primary' : 'text-slate-900 dark:text-slate-100' }} hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal" href="{{ route('about') }}">Giới thiệu</a>
            <button type="button" onclick="typeof toggleSupportChat === 'function' && toggleSupportChat();" class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal">Hỗ trợ</button>
            @auth
                @if(auth()->user()->RoleID == 1 || auth()->user()->RoleID == 2)
                <a class="text-slate-900 dark:text-slate-100 hover:text-primary dark:hover:text-primary transition-colors text-sm font-medium leading-normal"
                   href="{{ auth()->user()->RoleID == 1 ? route('admin.dashboard') : route('staff.dashboard') }}">
                   Dashboard
                </a>
                @endif
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

            {{-- Nút Hỗ trợ (luôn hiển thị) --}}
            <button type="button" onclick="typeof toggleSupportChat === 'function' && toggleSupportChat();" class="flex items-center justify-center h-10 w-10 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition-colors group" title="Hỗ trợ">
                <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform">support_agent</span>
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
                         x-transition.opacity
                         class="absolute right-0 mt-3 w-80 bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 z-[100] overflow-hidden p-6">
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-3 border-b border-slate-50 dark:border-slate-800 pb-4">
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">login</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-sm">Chào mừng bạn!</h4>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Đăng nhập để tiếp tục</p>
                                </div>
                            </div>

                            <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-3">
                                @csrf
                                <div class="flex flex-col gap-1.5 focus-within:translate-x-1 transition-transform">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Email</label>
                                    <input type="email" name="Email" value="{{ old('Email', old('Username')) }}" required autocomplete="email"
                                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm transition-all @error('Email') border-red-500 @enderror"
                                           placeholder="Nhập email...">
                                    @error('Email')
                                        <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-1.5 focus-within:translate-x-1 transition-transform">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Mật khẩu</label>
                                    <input type="password" name="Password" required
                                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm transition-all"
                                           placeholder="••••••••">
                                </div>

                                <p class="text-[10px] text-slate-400 leading-tight">
                                    This site is protected by reCAPTCHA and the Google 
                                    <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a> and 
                                    <a href="#" class="text-blue-500 hover:underline">Terms of Service</a> apply.
                                </p>

                                <button type="submit" 
                                        class="w-full py-3.5 bg-primary hover:bg-primary-dark text-slate-900 font-bold rounded-xl transition-all uppercase tracking-widest text-xs shadow-md shadow-primary/20 active:scale-95">
                                    ĐĂNG NHẬP
                                </button>
                            </form>

                            <div class="mt-2 pt-4 border-t border-slate-50 dark:border-slate-800 text-center flex flex-col gap-2">
                                <p class="text-xs text-slate-500">
                                    Khách hàng mới? 
                                    <a href="{{ route('register-customer') }}" class="text-primary hover:underline font-bold">Tạo tài khoản</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>
