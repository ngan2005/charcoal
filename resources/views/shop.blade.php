@extends('layouts.shop')

@push('styles')
<style>
    .blob-shape {
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        animation: blob-morph 8s ease-in-out infinite;
    }
    @keyframes blob-morph {
        0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
        50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
    }
    .animate-float-slow { animation: float 6s ease-in-out infinite; }
    .animate-float-delayed { animation: float 6s ease-in-out 2s infinite; }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
</style>
@endpush

@section('content')

<div class="relative z-10 space-y-12">
{{-- Hero Banner --}}
<section class="container-fluid px-0 mb-12 relative overflow-hidden rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-xl">
    <div class="absolute top-[-10%] left-[-5%] w-64 h-64 bg-primary/20 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-96 h-96 bg-accent/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="row g-0 items-center min-h-[450px] relative z-10 bg-gradient-to-br from-[#fff5f5] to-[#ffe4e8] dark:from-slate-800 dark:to-slate-900 rounded-[3rem]">
        <div class="col-md-6 px-8 py-12 md:ps-20 flex flex-col gap-6 text-center md:text-start">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-full w-fit mx-auto md:mx-0 border border-white/40 shadow-sm">
                <span class="text-primary text-xl animate-bounce">✨</span>
                <span class="text-slate-600 dark:text-slate-300 text-xs font-bold tracking-widest uppercase">Pink Charcoal Pet Store</span>
            </div>
            <h1 class="text-slate-900 dark:text-white text-4xl md:text-6xl font-extrabold leading-[1.1] tracking-tight">
                Ưu Đãi Đặc Biệt <br><span class="text-primary-dark">Cho Thú Cưng</span>
            </h1>
            <p class="text-slate-600 dark:text-slate-400 text-lg md:text-xl max-w-xl leading-relaxed font-medium">
                Nâng niu người bạn nhỏ của bạn với những sản phẩm chất lượng, được tuyển chọn kỹ lưỡng bằng cả tình yêu thương.
            </p>
            <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-2">
                <button onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})" class="bg-primary hover:bg-primary-dark text-slate-900 font-bold py-4 px-10 rounded-2xl shadow-lg shadow-primary/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2 group">
                    <span>Khám Phá Ngay</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </div>
        </div>
        <div class="col-md-6 h-full relative p-8 md:p-12 flex items-center justify-center">
            <span class="material-symbols-outlined absolute top-1/4 left-10 text-4xl text-primary/40 animate-float-slow select-none">pets</span>
            <span class="material-symbols-outlined absolute bottom-1/4 right-10 text-5xl text-accent/30 animate-float-delayed select-none">favorite</span>
            <div class="relative w-full max-w-[400px] aspect-square flex items-center justify-center">
                <div class="absolute inset-0 bg-primary/30 blob-shape transform rotate-12 scale-110"></div>
                <div class="absolute inset-0 bg-white/40 dark:bg-slate-800/40 backdrop-blur-sm blob-shape transform -rotate-6"></div>
                <div class="relative w-[90%] h-[90%] overflow-hidden blob-shape border-[8px] border-white dark:border-slate-800 shadow-2xl">
                    <img alt="Happy pets" class="w-full h-full object-cover object-center" src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?q=80&w=1000&auto=format&fit=crop" onerror="this.src='https://placehold.co/600x600/F4C2C3/ffffff?text=Happy+Pets'"/>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Main Content: Filter + Products --}}
<div class="flex gap-8">
    {{-- Filter Sidebar --}}
    <aside class="w-64 shrink-0 flex-col gap-6 hidden lg:flex">
        <div class="flex flex-col gap-2">
            <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold">Lọc Sản Phẩm</h3>
        </div>

        {{-- Filter by Category --}}
        <details class="flex flex-col rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-[15px] py-[7px] group" open>
            <summary class="flex cursor-pointer items-center justify-between gap-6 py-2 list-none">
                <p class="text-slate-900 dark:text-slate-100 text-sm font-medium leading-normal">Theo danh mục</p>
                <span class="material-symbols-outlined text-slate-500 group-open:rotate-180 transition-transform">expand_more</span>
            </summary>
            <div class="flex flex-col gap-3 pb-3 pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 category-filter"
                           type="checkbox" value="" {{ !request('category') ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">Tất cả</span>
                </label>
                @foreach($categories as $category)
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 category-filter"
                           type="checkbox" value="{{ $category->CategoryID }}"
                           {{ in_array($category->CategoryID, explode(',', request('category', ''))) ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">{{ $category->CategoryName }}</span>
                </label>
                @endforeach
            </div>
        </details>

        {{-- Filter by Price --}}
        <details class="flex flex-col rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-[15px] py-[7px] group">
            <summary class="flex cursor-pointer items-center justify-between gap-6 py-2 list-none">
                <p class="text-slate-900 dark:text-slate-100 text-sm font-medium leading-normal">Theo giá</p>
                <span class="material-symbols-outlined text-slate-500 group-open:rotate-180 transition-transform">expand_more</span>
            </summary>
            <div class="flex flex-col gap-3 pb-3 pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 price-filter"
                           type="radio" name="price" value="all" {{ !request('price') || request('price') === 'all' ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">Tất cả</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 price-filter"
                           type="radio" name="price" value="0-100000" {{ request('price') === '0-100000' ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">Dưới 100k</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 price-filter"
                           type="radio" name="price" value="100000-500000" {{ request('price') === '100000-500000' ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">100k - 500k</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 price-filter"
                           type="radio" name="price" value="500000-9999999" {{ request('price') === '500000-9999999' ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">Trên 500k</span>
                </label>
            </div>
        </details>

        {{-- Filter by Service --}}
        <div class="flex flex-col gap-2 mt-2">
            <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold">Lọc Dịch Vụ</h3>
        </div>
        <details class="flex flex-col rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-[15px] py-[7px] group" {{ request('service') ? 'open' : '' }}>
            <summary class="flex cursor-pointer items-center justify-between gap-6 py-2 list-none">
                <p class="text-slate-900 dark:text-slate-100 text-sm font-medium leading-normal">Loại dịch vụ</p>
                <span class="material-symbols-outlined text-slate-500 group-open:rotate-180 transition-transform">expand_more</span>
            </summary>
            <div class="flex flex-col gap-3 pb-3 pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 service-filter"
                           type="checkbox" value="" {{ !request('service') ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">Tất cả dịch vụ</span>
                </label>
                @foreach($allServices as $svc)
                <label class="flex items-center gap-3 cursor-pointer">
                    <input class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-800 service-filter"
                           type="checkbox" value="{{ $svc->ServiceID }}"
                           {{ in_array($svc->ServiceID, explode(',', request('service', ''))) ? 'checked' : '' }}/>
                    <span class="text-slate-700 dark:text-slate-300 text-sm">{{ $svc->ServiceName }}</span>
                </label>
                @endforeach
            </div>
        </details>

        {{-- Vouchers / Promotions --}}
        @if(isset($vouchers) && $vouchers->count() > 0)
        <div class="flex flex-col gap-4 mt-4">
            <div class="flex flex-col gap-1">
                <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold">Mã Giảm Giá</h3>
                <p class="text-[10px] text-slate-500 font-medium italic">Bấm vào mã để sao chép nhanh ✨</p>
            </div>
            
            <div class="flex flex-col gap-3">
                @foreach($vouchers as $voucher)
                <div class="relative bg-white dark:bg-slate-900 border-2 border-dashed border-primary/40 rounded-2xl p-4 flex flex-col gap-2 group hover:border-primary transition-all cursor-pointer overflow-hidden shadow-sm shadow-primary/5"
                     onclick="copyVoucherCode('{{ $voucher->Code }}', this)">
                    {{-- Ticket Cutouts --}}
                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-background-light dark:bg-background-dark rounded-full border-r-2 border-dashed border-primary/40 group-hover:border-primary"></div>
                    <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-background-light dark:bg-background-dark rounded-full border-l-2 border-dashed border-primary/40 group-hover:border-primary"></div>
                    
                    <div class="flex justify-between items-center z-10">
                        <span class="text-xs font-black text-primary bg-primary/10 px-2 py-1 rounded-lg tracking-widest uppercase">
                            {{ $voucher->Code }}
                        </span>
                        <span class="material-symbols-outlined text-primary text-sm opacity-0 group-hover:opacity-100 transition-opacity">content_copy</span>
                    </div>
                    
                    <div class="flex flex-col z-10">
                        <p class="text-slate-900 dark:text-slate-100 text-xs font-bold">{{ $voucher->Description }}</p>
                        @if($voucher->MinOrderAmount > 0)
                        <p class="text-[10px] text-slate-400">Đơn tối thiểu: {{ number_format($voucher->MinOrderAmount, 0, ',', '.') }}đ</p>
                        @endif
                    </div>
                    
                    {{-- Copy Success Overlay --}}
                    <div class="copy-success absolute inset-0 bg-primary/95 flex items-center justify-center translate-y-full transition-transform duration-300 z-20">
                        <div class="flex flex-col items-center gap-1">
                            <span class="material-symbols-outlined text-slate-900">check_circle</span>
                            <span class="text-[10px] font-black text-slate-900 uppercase">Đã sao chép!</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </aside>

    {{-- Products Section --}}
    <section class="flex-1 flex flex-col gap-6" id="products">
        {{-- Header & Sort --}}
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-slate-900 dark:text-slate-100 text-3xl font-bold leading-tight">Sản phẩm cho thú cưng</h1>
            <label class="flex items-center gap-3">
                <span class="text-slate-600 dark:text-slate-400 text-sm font-medium whitespace-nowrap">Sắp xếp theo:</span>
                <form action="{{ route('shop') }}" method="GET">
                    @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('price'))
                    <input type="hidden" name="price" value="{{ request('price') }}">
                    @endif
                    <select name="sort" onchange="this.form.submit()"
                            class="form-select rounded-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:border-primary focus:ring-primary text-sm py-2 pl-4 pr-10 cursor-pointer">
                        <option value="newest"    {{ request('sort') == 'newest'    ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
                        <option value="price_desc"{{ request('sort') == 'price_desc'? 'selected' : '' }}>Giá: Cao đến thấp</option>
                        <option value="popular"   {{ request('sort') == 'popular'   ? 'selected' : '' }}>Phổ biến nhất</option>
                    </select>
                </form>
            </label>
        </div>

        {{-- Mobile category filter --}}
        <div class="lg:hidden flex gap-2 overflow-x-auto py-2 no-scrollbar">
            <button class="category-btn flex-shrink-0 px-4 py-2 rounded-full bg-primary text-slate-900 text-sm font-medium" data-category="">Tất cả</button>
            @foreach($categories as $category)
            <button class="category-btn flex-shrink-0 px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-primary/20"
                    data-category="{{ $category->CategoryID }}">{{ $category->CategoryName }}</button>
            @endforeach
        </div>

        {{-- Services Section (if services are available or filtered) --}}
        @if($services->count() > 0)
        <div class="flex flex-col gap-6 mb-12">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-xl">content_cut</span>
                    Dịch vụ Thú cưng
                </h2>
                <span class="text-sm text-slate-500 font-medium">{{ $services->count() }} dịch vụ</span>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($services as $service)
                    @php
                        $sMainImage = $service->images->where('IsMain', 1)->first();
                        $sFirstImage = $service->images->first();
                        $sImageUrl = $sMainImage ? $sMainImage->ImageUrl : ($sFirstImage ? $sFirstImage->ImageUrl : '');
                        if ($sImageUrl) {
                            if (str_contains($sImageUrl, '/storage/')) {
                                $sImgSrc = asset('storage/' . substr($sImageUrl, strpos($sImageUrl, '/storage/') + 9));
                            } elseif (str_starts_with($sImageUrl, 'http')) {
                                $sImgSrc = $sImageUrl;
                            } else {
                                $sImgSrc = asset('storage/' . $sImageUrl);
                            }
                        }
                        $sImgPlaceholder = 'https://placehold.co/600x400/F4C2C3/ffffff?text=' . urlencode($service->ServiceName);
                    @endphp
                    <div class="flex flex-col bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-800 border-b-4 border-b-primary/30">
                        <div class="aspect-[16/10] bg-slate-100 dark:bg-slate-800 relative group overflow-hidden">
                            @if($sImageUrl)
                                <img alt="{{ $service->ServiceName }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="{{ $sImgSrc }}" onerror="this.onerror=null; this.src='{{ $sImgPlaceholder }}';"/>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-primary/5">
                                    <span class="material-symbols-outlined text-4xl text-primary/30">photo</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                <a href="{{ route('services.index') }}" class="text-white text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:underline">
                                    Xem chi tiết dịch vụ
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                        <div class="p-3.5 flex flex-col gap-1.5">
                            <h3 class="text-slate-900 dark:text-slate-100 font-bold text-[13px] leading-snug line-clamp-1">{{ $service->ServiceName }}</h3>
                            <div class="flex items-baseline gap-2">
                                <span class="text-primary font-black text-base">{{ number_format($service->BasePrice, 0, ',', '.') }}đ</span>
                                <span class="text-[9px] text-slate-400 font-medium uppercase tracking-tighter">{{ $service->Duration }} phút</span>
                            </div>
                            <a href="{{ route('appointment.create', ['service_id' => $service->ServiceID]) }}" class="mt-1 w-full py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-primary hover:text-slate-900 text-slate-700 dark:text-slate-300 rounded-xl text-[10px] font-bold transition-all text-center">
                                ĐẶT LỊCH NGAY
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Products Grid with Load More Logic --}}
        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-xl">shopping_cart</span>
            Sản phẩm Thú cưng
        </h2>
        <div class="flex flex-col gap-8" x-data="{ totalItems: {{ $products->count() }} }">
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-2 md:gap-3">
                @foreach($products as $index => $product)
                    @php
                        $mainImage = $product->images->where('IsMain', 1)->first();
                        $firstImage = $product->images->first();
                        $imageUrl = $mainImage ? $mainImage->ImageUrl : ($firstImage ? $firstImage->ImageUrl : '');
                    @endphp
                    <div class="flex flex-col bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all border border-slate-100 dark:border-slate-800">
                    {{-- Image --}}
                    <div class="aspect-square bg-slate-100 dark:bg-slate-800 relative group">
                        @if($imageUrl)
                            <img alt="{{ $product->ProductName }}"
                                 class="w-full h-full object-cover"
                                 src="{{ str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl) }}"
                                 onerror="this.src='https://placehold.co/400x400/F4C2C3/ffffff?text={{ urlencode($product->ProductName) }}'"/>
                        @else
                            <img alt="{{ $product->ProductName }}"
                                 class="w-full h-full object-cover"
                                 src="https://placehold.co/400x400/F4C2C3/ffffff?text={{ urlencode($product->ProductName) }}"/>
                        @endif

                        {{-- Badge --}}
                        @if($product->PurchaseCount > 100)
                            <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">HOT</div>
                        @elseif($product->created_at && $product->created_at->diffInDays() < 7)
                            <div class="absolute top-3 left-3 bg-primary text-slate-900 text-xs font-bold px-2 py-1 rounded-full">MỚI</div>
                        @endif

                        {{-- Hover --}}
                        @php
                            $fullImgUrl = $imageUrl ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl)) : 'https://placehold.co/800x800/F4C2C3/ffffff?text=' . urlencode($product->ProductName);
                            $priceText = number_format($product->Price, 0, ',', '.') . 'đ';
                        @endphp
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer" onclick="window.location.href='{{ route('product.show', $product->ProductID) }}'">
                            <div class="bg-white/90 text-slate-900 rounded-full p-3 transform translate-y-4 group-hover:translate-y-0 transition-all hover:bg-primary backdrop-blur-sm shadow-lg">
                                <span class="material-symbols-outlined text-2xl">visibility</span>
                            </div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-4 flex flex-col gap-3 flex-1">
                        <div class="flex-1">
                            <span class="text-xs font-semibold text-primary uppercase tracking-wider">
                                {{ $product->category ? $product->category->CategoryName : 'Chưa phân loại' }}
                            </span>
                            <h3 class="text-slate-900 dark:text-slate-100 font-medium text-base leading-snug mt-1 line-clamp-2 hover:text-primary transition-colors">
                                <a href="{{ route('product.show', $product->ProductID) }}">{{ $product->ProductName }}</a>
                            </h3>
                        </div>
                        <div class="flex items-center justify-between mt-auto pt-3 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                {{ number_format($product->Price, 0, ',', '.') }}đ
                            </span>
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ProductID" value="{{ $product->ProductID }}">
                                <button type="submit" class="flex items-center justify-center bg-primary hover:bg-primary-dark text-slate-900 rounded-full w-10 h-10 transition-all shadow-sm hover:scale-110 active:scale-95" title="Thêm vào giỏ">
                                    <span class="material-symbols-outlined text-xl">add_shopping_cart</span>
                                </button>
                            </form>
                        </div> {{-- End Price/Cart --}}
                    </div> {{-- End Info Section --}}
                </div> {{-- End Product Card --}}
            @endforeach

                @if($products->isEmpty())
                    <div class="col-span-full text-center py-12">
                        <div class="bg-primary/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-4xl text-primary">shopping_bag</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Chưa có sản phẩm nào</h3>
                        <p class="text-slate-500">Hãy quay lại sau khi admin thêm sản phẩm nhé!</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="flex justify-center mt-8">
            <nav class="flex items-center gap-2">
                @if($products->onFirstPage())
                <button class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500" disabled>
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </button>
                @else
                <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-sm">chevron_left</span>
                </a>
                @endif

                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                    <button class="w-10 h-10 flex items-center justify-center rounded-full bg-primary text-slate-900 font-medium">{{ $page }}</button>
                    @else
                    <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-medium">{{ $page }}</a>
                    @endif
                @endforeach

                @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                @else
                <button class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-500" disabled>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </button>
                @endif
            </nav>
        </div>
        @endif
    </section>
</div>

@push('scripts')
<script>
    // Mobile category filter
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.category-btn').forEach(b => {
                b.classList.remove('bg-primary', 'text-slate-900');
                b.classList.add('bg-slate-100', 'text-slate-700');
            });
            this.classList.remove('bg-slate-100', 'text-slate-700');
            this.classList.add('bg-primary', 'text-slate-900');
        });
    });

    // Sidebar filter → apply on change
    function applyFilters() {
        const params = new URLSearchParams(window.location.search);

        const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked'))
            .map(cb => cb.value).filter(v => v);
        if (selectedCategories.length > 0) {
            params.set('category', selectedCategories.join(','));
        } else {
            params.delete('category');
        }

        const selectedPrice = document.querySelector('.price-filter:checked')?.value;
        if (selectedPrice && selectedPrice !== 'all') {
            params.set('price', selectedPrice);
        } else {
            params.delete('price');
        }

        const selectedServices = Array.from(document.querySelectorAll('.service-filter:checked'))
            .map(cb => cb.value).filter(v => v);
        if (selectedServices.length > 0) {
            params.set('service', selectedServices.join(','));
        } else {
            params.delete('service');
        }

        // Keep sort & search
        window.location.href = '{{ route("shop") }}?' + params.toString();
    }

    document.querySelectorAll('.category-filter, .price-filter, .service-filter').forEach(input => {
        input.addEventListener('change', applyFilters);
    });

    // Copy Voucher Code
    window.copyVoucherCode = function(code, element) {
        navigator.clipboard.writeText(code).then(() => {
            const successOverlay = element.querySelector('.copy-success');
            successOverlay.classList.remove('translate-y-full');
            
            setTimeout(() => {
                successOverlay.classList.add('translate-y-full');
            }, 1000);
        });
    }
</script>
@endpush

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

</div> {{-- End of z-10 container --}}
@endsection
