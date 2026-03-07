@extends('layouts.shop')

@section('content')

<div class="flex flex-col gap-8 w-full max-w-5xl mx-auto py-4">
    {{-- Breadcrumb --}}
    <nav class="flex text-slate-500 text-sm font-medium gap-2">
        <a href="{{ route('shop') }}" class="hover:text-primary transition-colors">Trang chủ</a> 
        <span>/</span>
        <a href="{{ route('services.index') }}" class="hover:text-primary transition-colors">Dịch vụ</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-slate-100 truncate max-w-xs">{{ $service->ServiceName }}</span>
    </nav>
    
    {{-- Service Details --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 p-8 flex flex-col md:flex-row gap-10">
        {{-- Image --}}
        @php
            $serviceImgUrl = "https://placehold.co/800x800/F4C2C3/ffffff?text=" . urlencode($service->ServiceName);
            $servicePriceText = number_format($service->BasePrice, 0, ',', '.') . 'đ';
        @endphp
        <div class="w-full md:w-1/2 aspect-square md:aspect-auto rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0 relative group cursor-pointer" onclick="openLightbox('{{ $serviceImgUrl }}', '{{ addslashes($service->ServiceName) }}', '{{ $servicePriceText }}')">
            <img alt="{{ $service->ServiceName }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 src="{{ $serviceImgUrl }}"/>
            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                <span class="bg-white/90 text-slate-900 rounded-full p-3 shadow-lg flex backdrop-blur-sm">
                    <span class="material-symbols-outlined">zoom_in</span>
                </span>
            </div>
        </div>
        
        {{-- Info --}}
        <div class="flex-1 flex flex-col py-2">
            <h1 class="text-slate-900 dark:text-slate-100 text-3xl sm:text-4xl font-bold leading-tight mb-4">
                {{ $service->ServiceName }}
            </h1>
            
            <div class="text-3xl font-bold text-primary mb-6">
                {{ number_format($service->BasePrice, 0, ',', '.') }}đ
            </div>
            
            <div class="prose dark:prose-invert prose-slate prose-p:leading-relaxed prose-a:text-primary mb-8 border-t border-slate-100 dark:border-slate-800 pt-6">
                <p>{{ $service->Description ?? 'Dịch vụ này không có mô tả.' }}</p>
                <p>Quý khách vui lòng đặt lịch trước để được phục vụ tốt nhất. Thời gian làm việc: 8:00 AM - 10:00 PM.</p>
            </div>
            
            <div class="mt-auto flex flex-col gap-4">
                <button class="w-full bg-primary hover:bg-primary-dark text-slate-900 font-bold py-4 rounded-xl shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2 transform hover:scale-105">
                    <span class="material-symbols-outlined">calendar_month</span>
                    Đặt Lịch Ngay
                </button>
                <button class="w-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-900 dark:text-slate-100 font-bold py-4 rounded-xl transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">support_agent</span>
                    Tư Vấn Thêm
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Reviews & Comments Section --}}
<div class="w-full max-w-5xl mx-auto mt-12 mb-8 bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-800">
    <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-3 mb-8">
        <span class="material-symbols-outlined text-primary text-3xl">forum</span>
        Đánh Giá & Bình Luận
    </h3>

    <div class="flex flex-col md:flex-row gap-10">
        {{-- Rating Summary --}}
        <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
            <div class="text-5xl font-extrabold text-slate-900 dark:text-white mb-2">{{ number_format($averageRating, 1) }}<span class="text-2xl text-slate-400 font-medium">/5</span></div>
            <div class="flex items-center gap-1 text-amber-400 mb-2">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($averageRating))
                        <span class="material-symbols-outlined text-2xl fill-current">star</span>
                    @elseif($i - 0.5 <= $averageRating)
                        <span class="material-symbols-outlined text-2xl fill-current">star_half</span>
                    @else
                        <span class="material-symbols-outlined text-2xl text-slate-300">star</span>
                    @endif
                @endfor
            </div>
            <p class="text-slate-500 text-sm">Dựa trên {{ $reviewCount }} đánh giá</p>
        </div>

        {{-- Add Comment Form --}}
        <div class="w-full md:w-2/3 flex flex-col gap-4">
            <h4 class="font-bold text-slate-900 dark:text-slate-100 text-lg">Viết đánh giá của bạn</h4>
            <div class="flex items-center gap-2 mb-2 text-slate-300">
                <span class="material-symbols-outlined text-3xl cursor-pointer hover:text-amber-400 transition-colors">star</span>
                <span class="material-symbols-outlined text-3xl cursor-pointer hover:text-amber-400 transition-colors">star</span>
                <span class="material-symbols-outlined text-3xl cursor-pointer hover:text-amber-400 transition-colors">star</span>
                <span class="material-symbols-outlined text-3xl cursor-pointer hover:text-amber-400 transition-colors">star</span>
                <span class="material-symbols-outlined text-3xl cursor-pointer hover:text-amber-400 transition-colors">star</span>
            </div>
            <textarea rows="3" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-4 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Chia sẻ trải nghiệm của bạn về dịch vụ này..."></textarea>
            <div class="flex justify-end mt-2">
                <button class="bg-primary hover:bg-primary-dark text-slate-900 font-bold py-3 px-8 rounded-xl shadow-md transition-all">Gửi Đánh Giá</button>
            </div>
        </div>
    </div>

    {{-- Comments List --}}
    <div class="mt-10 flex flex-col gap-6">
        <h4 class="font-bold text-slate-900 dark:text-slate-100 text-lg border-b border-slate-100 dark:border-slate-800 pb-2">Bình luận mới nhất</h4>
        
        @forelse($reviews as $review)
            <div class="flex gap-4 p-4 rounded-2xl bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-800">
                @php
                    $customerName = $review->customer ? $review->customer->FullName : 'Khách vãng lai';
                @endphp
                <img src="https://ui-avatars.com/api/?name={{ urlencode($customerName) }}&background=F4C2C3&color=fff" alt="Avatar" class="w-12 h-12 rounded-full shadow-sm shrink-0">
                <div class="flex flex-col w-full">
                    <div class="flex justify-between items-start mb-1">
                        <h5 class="font-bold text-slate-900 dark:text-slate-100">{{ $customerName }}</h5>
                        <span class="text-xs text-slate-400">{{ $review->CreatedAt ? \Carbon\Carbon::parse($review->CreatedAt)->diffForHumans() : '' }}</span>
                    </div>
                    
                    {{-- Stars --}}
                    <div class="flex items-center gap-1 text-amber-400 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->Rating)
                                <span class="material-symbols-outlined text-sm fill-current">star</span>
                            @else
                                <span class="material-symbols-outlined text-sm text-slate-300">star</span>
                            @endif
                        @endfor
                    </div>
                    
                    <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed whitespace-pre-line">{{ $review->Comment }}</p>
                    
                    {{-- Shop Replies --}}
                    @if($review->replies && $review->replies->count() > 0)
                        @foreach($review->replies as $reply)
                            @if(!$reply->Deleted)
                                <div class="mt-4 bg-primary/10 dark:bg-primary/5 rounded-xl p-4 flex gap-3 border border-primary/20">
                                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-white text-sm">storefront</span>
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-sm text-primary-dark dark:text-primary mb-1">
                                            Shop Charcoal ({{ $reply->staff ? $reply->staff->FullName : 'Quản trị viên' }})
                                        </h6>
                                        <p class="text-slate-600 dark:text-slate-400 text-sm whitespace-pre-line">{{ $reply->Comment }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-slate-500">
                Chưa có phần đánh giá nào cho Dịch vụ này.
            </div>
        @endforelse
    </div>
</div>

{{-- Suggested Products --}}
@if(isset($suggestedProducts) && $suggestedProducts->count() > 0)
<div class="w-full max-w-5xl mx-auto mt-12 mb-8 flex flex-col gap-6">
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
        <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">shopping_bag</span>
            Sản Phẩm Đề Xuất
        </h3>
        <a href="{{ route('shop') }}" class="text-sm font-medium text-primary hover:text-primary-dark flex items-center gap-1 transition-colors">
            Xem tất cả <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($suggestedProducts as $product)
            <div class="flex flex-col bg-white dark:bg-slate-900 rounded-xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 hover:border-primary/50 transition-all group">
                <div class="aspect-square bg-slate-50 dark:bg-slate-800 relative p-4">
                    @php
                        $img = $product->images->where('IsMain', 1)->first() ?? $product->images->first();
                        $imageUrl = $img ? $img->ImageURL : null;
                        $fullImgUrl = $imageUrl ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl)) : 'https://placehold.co/400x400/F4C2C3/ffffff?text=' . urlencode($product->ProductName);
                    @endphp
                    <img src="{{ $fullImgUrl }}" alt="{{ $product->ProductName }}" class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <h4 class="font-medium text-slate-900 dark:text-slate-100 text-sm leading-tight group-hover:text-primary transition-colors line-clamp-2 mb-2 min-h-[2.5rem]">{{ $product->ProductName }}</h4>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-slate-900 dark:text-white font-bold">{{ number_format($product->Price, 0, ',', '.') }}đ</span>
                        <button class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Related Services --}}
@if($otherServices->count() > 0)
<div class="w-full max-w-5xl mx-auto mt-12 mb-8 flex flex-col gap-6">
    <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Dịch Vụ Khác</h3>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($otherServices as $other)
            <a href="{{ route('services.show', $other->ServiceID) }}" class="flex flex-col bg-white dark:bg-slate-900 rounded-xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-800 hover:border-primary transition-colors group">
                <div class="aspect-video bg-slate-100 relative">
                    <img src="https://placehold.co/400x300/F4C2C3/ffffff?text={{ urlencode($other->ServiceName) }}" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-slate-900 dark:text-slate-100 text-base leading-tight group-hover:text-primary transition-colors mb-2 line-clamp-1">{{ $other->ServiceName }}</h4>
                    <span class="text-primary font-bold text-sm">{{ number_format($other->BasePrice, 0, ',', '.') }}đ</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

@endsection
