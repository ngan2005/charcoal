@extends('layouts.shop')

@section('content')
<div class="container-fluid px-4 py-6 max-w-7xl mx-auto" x-data="{ activeImage: '{{ $product->images->where('IsMain', 1)->first() ? (str_starts_with($product->images->where('IsMain', 1)->first()->ImageUrl, 'http') ? $product->images->where('IsMain', 1)->first()->ImageUrl : asset('storage/' . $product->images->where('IsMain', 1)->first()->ImageUrl)) : ( $product->images->first() ? (str_starts_with($product->images->first()->ImageUrl, 'http') ? $product->images->first()->ImageUrl : asset('storage/' . $product->images->first()->ImageUrl)) : 'https://placehold.co/800x800/F4C2C3/ffffff?text=' . urlencode($product->ProductName) ) }}' }">
    {{-- Breadcrumbs --}}
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('shop') }}" class="text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white inline-flex items-center text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-base mr-2">home</span>
                    Cửa hàng
                </a>
            </li>
            @if($product->category)
            <li>
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                    <a href="{{ route('shop', ['category' => $product->CategoryID]) }}" class="text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white text-sm font-medium ml-1 md:ml-2 transition-colors">
                        {{ $product->category->CategoryName }}
                    </a>
                </div>
            </li>
            @endif
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                    <span class="text-slate-900 dark:text-white text-sm font-bold ml-1 md:ml-2 truncate max-w-[200px]">{{ $product->ProductName }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        {{-- Product Gallery (Left) --}}
        <div class="flex flex-col gap-3">
            <div class="relative bg-white dark:bg-slate-900 rounded-[2rem] overflow-hidden shadow-xl border border-slate-100 dark:border-slate-800 aspect-square group">
                <img :src="activeImage" 
                     alt="{{ $product->ProductName }}" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                {{-- Zoom Icon Overlay --}}
                <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-zoom-in"
                     @click="openLightbox(activeImage, '{{ addslashes($product->ProductName) }}', '{{ number_format($product->Price, 0, ',', '.') }}đ')">
                    <div class="bg-white/90 p-4 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                        <span class="material-symbols-outlined text-primary text-3xl">zoom_in</span>
                    </div>
                </div>
            </div>

            {{-- Thumbnails --}}
            @if($product->images->count() > 1)
            <div class="flex flex-wrap gap-3 mt-4">
                @foreach($product->images as $image)
                    @php
                        $thumbUrl = str_starts_with($image->ImageUrl, 'http') ? $image->ImageUrl : asset('storage/' . $image->ImageUrl);
                    @endphp
                    <button @click="activeImage = '{{ $thumbUrl }}'"
                            class="w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden border-2 transition-all hover:scale-105 active:scale-95"
                            :class="activeImage === '{{ $thumbUrl }}' ? 'border-primary shadow-md ring-2 ring-primary/20' : 'border-slate-200 dark:border-slate-800'">
                        <img src="{{ $thumbUrl }}" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Product Info (Right) --}}
        <div class="flex flex-col gap-6 py-2">
            <div>
                <span class="text-primary font-bold text-xs tracking-widest uppercase mb-2 block">
                    {{ $product->category ? $product->category->CategoryName : 'PHỤ KIỆN' }}
                </span>
                <h1 class="text-slate-900 dark:text-white text-2xl md:text-3xl font-black font-display leading-tight mb-3">
                    {{ $product->ProductName }}
                </h1>
                
                <div class="flex items-center gap-4 mb-4">
                    <span class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white">
                        {{ number_format($product->Price, 0, ',', '.') }}đ
                    </span>
                    @if($product->Stock > 0)
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full dark:bg-green-900/30 dark:text-green-400">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        CÒN HÀNG
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 bg-rose-100 text-rose-700 text-xs font-bold rounded-full dark:bg-rose-900/30 dark:text-rose-400">
                        HẾT HÀNG
                    </span>
                    @endif
                </div>

                <p class="text-slate-600 dark:text-slate-400 text-base leading-relaxed font-normal mb-6 border-l-4 border-primary/30 pl-6 italic">
                    {{ $product->Description ? Str::limit($product->Description, 150) : 'Chào mừng bạn đến với Pink Charcoal - nơi cung cấp những sản phẩm tốt nhất cho thú cưng yêu quý của bạn.' }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col gap-6">
                {{-- Quantity --}}
                <div class="flex flex-col gap-2">
                    <span class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Số lượng</span>
                    <div class="flex items-center gap-3 bg-slate-100 dark:bg-slate-800 w-fit p-1 rounded-xl border border-slate-200 dark:border-slate-700 shadow-inner" x-data="{ qty: 1 }">
                        <button @click="if(qty > 1) qty--" class="w-8 h-8 rounded-lg bg-white dark:bg-slate-700 flex items-center justify-center text-slate-900 dark:text-white shadow-sm hover:bg-primary hover:text-white transition-all active:scale-90 font-black">
                            -
                        </button>
                        <input type="number" x-model="qty" class="w-10 text-center bg-transparent border-none focus:ring-0 text-base font-bold text-slate-900 dark:text-white" readonly>
                        <button @click="qty++" class="w-8 h-8 rounded-lg bg-white dark:bg-slate-700 flex items-center justify-center text-slate-900 dark:text-white shadow-sm hover:bg-primary hover:text-white transition-all active:scale-90 font-black">
                            +
                        </button>
                    </div>
                </div>

                {{-- Add to Cart --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <form action="{{ route('cart.store') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->ProductID }}">
                        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-slate-900 font-bold py-4 px-6 rounded-2xl shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 group overflow-hidden relative">
                            <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 skew-x-[-20deg]"></div>
                            <span class="material-symbols-outlined text-xl group-hover:rotate-12 transition-transform">add_shopping_cart</span>
                            <span class="text-base">THÊM VÀO GIỎ HÀNG</span>
                        </button>
                    </form>
                    
                    <button class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:text-rose-500 hover:bg-rose-50 transition-all active:scale-90 shadow-sm border border-slate-200 dark:border-slate-700">
                        <span class="material-symbols-outlined text-2xl">favorite</span>
                    </button>
                </div>
            </div>

            {{-- Product Metadata --}}
            <div class="grid grid-cols-2 gap-4 mt-2 pt-6 border-t border-slate-100 dark:border-slate-800">
                <div class="flex flex-col gap-1">
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Mã sản phẩm</span>
                    <span class="text-slate-900 dark:text-white font-medium">#{{ $product->ProductCode ?? 'PC-' . $product->ProductID }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Kích thước</span>
                    <span class="text-slate-900 dark:text-white font-medium">{{ $product->Size ?? 'N/A' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Trọng lượng</span>
                    <span class="text-slate-900 dark:text-white font-medium">{{ $product->Weight ?? 'N/A' }} g</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Lượt mua</span>
                    <span class="text-slate-900 dark:text-white font-medium">{{ $product->PurchaseCount ?? 0 }} lượt</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Details & Reviews Section --}}
    <div class="mt-12">
        <div class="flex border-b border-slate-200 dark:border-slate-800 gap-8 mb-6">
            <button class="pb-4 text-xl font-bold border-b-4 border-primary text-slate-900 dark:text-white transition-all">
                Mô tả sản phẩm
            </button>
            <button class="pb-4 text-xl font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-all">
                Đánh giá ({{ $product->PurchaseCount > 0 ? rand(1, 10) : 0 }})
            </button>
        </div>
        
        <div class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-loose">
            @if($product->Description)
                {!! nl2br(e($product->Description)) !!}
            @else
                <p>Chúng tôi tự hào giới thiệu <strong>{{ $product->ProductName }}</strong> - một sản phẩm chuẩn từ thương hiệu <em>Pink Charcoal</em>. </p>
                <p>Pink Charcoal pet store là địa chỉ tin cậy dành cho những người yêu thú cưng. Chúng tôi chuyên cung cấp các loại thức ăn dinh dưỡng, phụ kiện thời trang, đồ chơi sáng tạo và dịch vụ chăm sóc hoàn hảo cho các bạn nhỏ bốn chân. Với triết lý "Yêu thương từ những điều nhỏ nhất", mỗi sản phẩm tại Pink Charcoal đều được tuyển chọn kỹ lưỡng về chất lượng và độ an toàn.</p>
                <p><strong>Tại sao nên chọn sản phẩm này?</strong></p>
                <ul>
                    <li>Chất liệu an toàn tuyệt đối cho thú cưng.</li>
                    <li>Thiết kế thông minh, đa năng và thời trang.</li>
                    <li>Giá cả hợp lý cùng chính sách chăm sóc khách hàng tận tâm.</li>
                </ul>
            @endif
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <div class="mt-20">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-slate-900 dark:text-white text-2xl font-black font-display tracking-tight flex items-center gap-3">
                <span class="w-12 h-1 bg-primary rounded-full"></span>
                Sản phẩm liên quan
            </h2>
            <a href="{{ route('shop', ['category' => $product->CategoryID]) }}" class="text-primary font-bold hover:underline transition-all flex items-center gap-2">
                Xem tất cả
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                @php
                    $mainImg = $related->images->where('IsMain', 1)->first();
                    $firstImg = $related->images->first();
                    $relUrl = $mainImg ? $mainImg->ImageUrl : ($firstImg ? $firstImg->ImageUrl : '');
                    $fullRelUrl = $relUrl ? (str_starts_with($relUrl, 'http') ? $relUrl : asset('storage/' . $relUrl)) : 'https://placehold.co/800x800/F4C2C3/ffffff?text=' . urlencode($related->ProductName);
                @endphp
                <div class="flex flex-col bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-slate-100 dark:border-slate-800 group hover:-translate-y-2">
                    <div class="aspect-square relative group/img cursor-pointer overflow-hidden" onclick="window.location.href='{{ route('product.show', $related->ProductID) }}'">
                        <img src="{{ $fullRelUrl }}" class="w-full h-full object-cover transform group-hover/img:scale-110 transition-transform duration-700 ease-out">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="bg-white/90 text-slate-900 rounded-full p-2.5 shadow-lg backdrop-blur-sm transform translate-y-4 group-hover/img:translate-y-0 transition-all duration-300">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col gap-3">
                        <h3 class="text-slate-900 dark:text-white font-bold text-base leading-snug line-clamp-2 hover:text-primary transition-colors cursor-pointer" onclick="window.location.href='{{ route('product.show', $related->ProductID) }}'">
                            {{ $related->ProductName }}
                        </h3>
                        <div class="flex items-center justify-between mt-2 pt-4 border-t border-dashed border-slate-200 dark:border-slate-800">
                            <span class="text-xl font-black text-slate-900 dark:text-white">
                                {{ number_format($related->Price, 0, ',', '.') }}đ
                            </span>
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $related->ProductID }}">
                                <button type="submit" class="w-10 h-10 rounded-full bg-primary hover:bg-primary-dark text-slate-900 flex items-center justify-center shadow-lg transition-transform hover:scale-110 active:scale-90" title="Thêm vào giỏ">
                                    <span class="material-symbols-outlined text-xl">add_shopping_cart</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type=number] {
      -moz-appearance: textfield;
    }
</style>
@endpush

@endsection
