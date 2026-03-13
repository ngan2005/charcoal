@extends('layouts.shop')

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
    $currentUser = Auth::user();
    $sMain = $service->images->where('IsMain', 1)->first();
    $sFirst = $service->images->first();
    $initialImg = ($sMain ?? $sFirst)?->ImageUrl;
    if ($initialImg) {
        if (str_contains($initialImg, '/storage/')) {
            $initialImgUrl = asset('storage/' . substr($initialImg, strpos($initialImg, '/storage/') + 9));
        } elseif (str_starts_with($initialImg, 'http')) {
            $initialImgUrl = $initialImg;
        } else {
            $initialImgUrl = asset('storage/' . $initialImg);
        }
    } else {
        $initialImgUrl = 'https://placehold.co/800x800/F4C2C3/ffffff?text=' . urlencode($service->ServiceName);
    }
@endphp

<div class="container-fluid px-4 py-4 max-w-5xl mx-auto" x-data="{ activeImage: '{{ $initialImgUrl }}' }">
    {{-- Breadcrumbs --}}
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('shop') }}" class="text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white inline-flex items-center text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-base mr-2">home</span>
                    Cửa hàng
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                    <a href="{{ route('services.index') }}" class="text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white text-sm font-medium ml-1 md:ml-2 transition-colors">
                        Dịch vụ
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                    <span class="text-slate-900 dark:text-white text-sm font-bold ml-1 md:ml-2 truncate max-w-[200px]">{{ $service->ServiceName }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        {{-- Service Gallery (Left) --}}
        <div class="flex flex-col gap-4">
            <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-xl border border-slate-100 dark:border-slate-800 aspect-square group">
                <img :src="activeImage" 
                     alt="{{ $service->ServiceName }}" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                {{-- Zoom Icon Overlay --}}
                <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-zoom-in"
                     @click="openLightbox(activeImage, '{{ addslashes($service->ServiceName) }}', '{{ number_format($service->BasePrice, 0, ',', '.') }}đ')">
                    <div class="bg-white/90 p-5 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                        <span class="material-symbols-outlined text-primary text-3xl font-black">zoom_in</span>
                    </div>
                </div>
            </div>

            {{-- Thumbnails --}}
            @if($service->images->count() > 1)
            <div class="flex flex-wrap gap-3">
                @foreach($service->images as $image)
                    @php
                        $thumbUrl = $image->ImageUrl;
                        if (str_contains($thumbUrl, '/storage/')) {
                            $thumbSrc = asset('storage/' . substr($thumbUrl, strpos($thumbUrl, '/storage/') + 9));
                        } elseif (str_starts_with($thumbUrl, 'http')) {
                            $thumbSrc = $thumbUrl;
                        } else {
                            $thumbSrc = asset('storage/' . $thumbUrl);
                        }
                    @endphp
                    <button @click="activeImage = '{{ $thumbSrc }}'"
                            class="w-20 h-20 rounded-2xl overflow-hidden border-2 transition-all hover:scale-105 active:scale-95"
                            :class="activeImage === '{{ $thumbSrc }}' ? 'border-primary shadow-md ring-4 ring-primary/10' : 'border-slate-100 dark:border-slate-800'">
                        <img src="{{ $thumbSrc }}" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Service Info (Right) --}}
        <div class="flex flex-col gap-8 py-2">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                        DỊCH VỤ THÚ CƯNG
                    </span>
                    <div class="flex items-center gap-1 text-amber-400">
                        <span class="material-symbols-outlined text-sm fill-current">star</span>
                        <span class="text-slate-900 dark:text-white text-xs font-bold">{{ number_format($averageRating, 1) }}</span>
                    </div>
                </div>
                
                <h1 class="text-slate-900 dark:text-white text-2xl md:text-3xl font-black font-display leading-tight">
                    {{ $service->ServiceName }}
                </h1>
                
                <div class="flex items-center gap-6">
                    <div class="text-2xl md:text-3xl font-black text-primary">
                        {{ number_format($service->BasePrice, 0, ',', '.') }}đ
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700">
                        <span class="material-symbols-outlined text-slate-500 text-lg">schedule</span>
                        <span class="text-slate-700 dark:text-slate-300 font-bold uppercase text-[10px]">{{ $service->Duration }} PHÚT</span>
                    </div>
                </div>

                <div class="prose dark:prose-invert prose-slate prose-p:leading-relaxed max-w-none border-l-4 border-primary/30 pl-4 text-slate-600 dark:text-slate-400 text-sm italic">
                    {{ $service->Description ?? 'Chào mừng bạn đến với Pink Charcoal - nơi cung cấp những dịch vụ chăm sóc tốt nhất cho thú cưng yêu quý của bạn.' }}
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col gap-4 pt-4">
                <form action="{{ route('cart.store') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="ServiceID" value="{{ $service->ServiceID }}">
                    <input type="hidden" name="Quantity" value="1">
                    <input type="hidden" name="redirect" value="checkout">
                    <button type="submit" 
                       class="w-full bg-primary hover:bg-primary-dark text-slate-900 font-black py-4 px-6 rounded-[1.25rem] shadow-xl shadow-primary/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 group relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 skew-x-[-20deg]"></div>
                        <span class="material-symbols-outlined text-xl group-hover:rotate-12 transition-transform">calendar_month</span>
                        <span class="text-base uppercase tracking-wide">ĐẶT LỊCH NGAY</span>
                    </button>
                </form>
                
                <button type="button" onclick="openSupportWithService('{{ addslashes($service->ServiceName) }}', {{ $service->BasePrice }}, '{{ route('services.show', $service->ServiceID) }}')" 
                        class="w-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-900 dark:text-slate-100 font-bold py-3 px-6 rounded-[1.25rem] transition-all flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 text-sm">
                    <span class="material-symbols-outlined text-xl">support_agent</span>
                    TƯ VẤN CHI TIẾT
                </button>
                <p class="text-slate-400 dark:text-slate-500 text-[11px] text-center italic">Nhắn tin ngay với shop để được tư vấn kĩ hơn về lộ trình chăm sóc bé yêu ✨</p>
            </div>

            {{-- Service Metadata --}}
            <div class="grid grid-cols-2 gap-6 mt-4 pt-8 border-t border-dashed border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-xl">verified</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-[9px] font-black uppercase tracking-widest">Cam kết</span>
                        <span class="text-slate-900 dark:text-white font-bold text-xs">Chuẩn 5 sao</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-xl">diversity_1</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-[9px] font-black uppercase tracking-widest">Đội ngũ</span>
                        <span class="text-slate-900 dark:text-white font-bold text-xs">Chuyên nghiệp</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Suggested Products --}}
    @if(isset($suggestedProducts) && $suggestedProducts->count() > 0)
    <div class="mt-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-slate-900 dark:text-white text-2xl font-black font-display tracking-tight flex items-center gap-3">
                <span class="w-10 h-1.5 bg-primary rounded-full"></span>
                Sản phẩm đề xuất
            </h2>
            <a href="{{ route('shop') }}" class="text-primary font-bold hover:underline flex items-center gap-2 group">
                Xem thêm
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($suggestedProducts as $product)
                @php
                    $pImg = $product->images->where('IsMain', 1)->first() ?? $product->images->first();
                    $pUrl = $pImg ? (str_starts_with($pImg->ImageUrl, 'http') ? $pImg->ImageUrl : asset('storage/' . $pImg->ImageUrl)) : 'https://placehold.co/400x400/F4C2C3/ffffff?text=' . urlencode($product->ProductName);
                @endphp
                <div class="flex flex-col bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-slate-100 dark:border-slate-800 group hover:-translate-y-2">
                    <a href="{{ route('product.show', $product->ProductID) }}" class="aspect-square relative overflow-hidden p-4 bg-slate-50 dark:bg-slate-800">
                        <img src="{{ $pUrl }}" class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal transform group-hover:scale-110 transition-transform duration-700 ease-out">
                    </a>
                    <div class="p-6 flex flex-col gap-3">
                        <h3 class="text-slate-900 dark:text-white font-bold text-sm leading-snug line-clamp-2 hover:text-primary transition-colors">
                            <a href="{{ route('product.show', $product->ProductID) }}">{{ $product->ProductName }}</a>
                        </h3>
                        <div class="flex items-center justify-between mt-2 pt-4 border-t border-dashed border-slate-200 dark:border-slate-800">
                            <span class="text-lg font-black text-slate-900 dark:text-white">
                                {{ number_format($product->Price, 0, ',', '.') }}đ
                            </span>
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ProductID" value="{{ $product->ProductID }}">
                                <button type="submit" class="w-10 h-10 rounded-full bg-primary hover:bg-primary-dark text-slate-900 flex items-center justify-center shadow-lg transition-transform hover:scale-110 active:scale-90">
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

    {{-- Other Services --}}
    @if(isset($otherServices) && $otherServices->count() > 0)
    <div class="mt-24">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-slate-900 dark:text-white text-3xl font-black font-display tracking-tight flex items-center gap-4">
                <span class="w-12 h-1.5 bg-primary rounded-full"></span>
                Dịch vụ khác
            </h2>
            <a href="{{ route('services.index') }}" class="text-primary font-bold hover:underline flex items-center gap-2 group">
                Xem tất cả
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($otherServices as $rel)
                @php
                    $rMain = $rel->images->where('IsMain', 1)->first();
                    $rFirst = $rel->images->first();
                    $rImg = ($rMain ?? $rFirst)?->ImageUrl;
                    if ($rImg) {
                        if (str_contains($rImg, '/storage/')) {
                            $rImgUrl = asset('storage/' . substr($rImg, strpos($rImg, '/storage/') + 9));
                        } elseif (str_starts_with($rImg, 'http')) {
                            $rImgUrl = $rImg;
                        } else {
                            $rImgUrl = asset('storage/' . $rImg);
                        }
                    } else {
                        $rImgUrl = 'https://placehold.co/600x400/F4C2C3/ffffff?text=' . urlencode($rel->ServiceName);
                    }
                @endphp
                <a href="{{ route('services.show', $rel->ServiceID) }}" class="flex flex-col bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-slate-100 dark:border-slate-800 group hover:-translate-y-2">
                    <div class="aspect-[4/3] relative overflow-hidden">
                        <img src="{{ $rImgUrl }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
                    </div>
                    <div class="p-6 flex flex-col gap-3">
                        <h3 class="text-slate-900 dark:text-white font-bold text-base leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                            {{ $rel->ServiceName }}
                        </h3>
                        <div class="flex items-center justify-between mt-2 pt-4 border-t border-dashed border-slate-200 dark:border-slate-800">
                            <span class="text-lg font-black text-primary">
                                {{ number_format($rel->BasePrice, 0, ',', '.') }}đ
                            </span>
                            <span class="w-10 h-10 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Reviews & Comments --}}
    <div class="mt-24 bg-white dark:bg-slate-900 rounded-[3rem] p-10 shadow-xl border border-slate-100 dark:border-slate-800" id="comments-section">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 mb-12">
            <h2 class="text-3xl font-black text-slate-900 dark:text-white flex items-center gap-4">
                <span class="w-12 h-1.5 bg-primary rounded-full"></span>
                Đánh giá & Bình luận
            </h2>
            <div class="flex items-center gap-8 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                <div class="flex flex-col items-center">
                    <span class="text-4xl font-black text-slate-900 dark:text-white">{{ number_format($averageRating, 1) }}</span>
                    <span class="text-[10px] text-slate-400 font-black uppercase">trung bình</span>
                </div>
                <div class="h-10 w-px bg-slate-200 dark:bg-slate-700"></div>
                <div class="flex flex-col items-center">
                    <span class="text-4xl font-black text-slate-900 dark:text-white">{{ $reviewCount }}</span>
                    <span class="text-[10px] text-slate-400 font-black uppercase">lượt đánh giá</span>
                </div>
            </div>
        </div>

        {{-- Add Comment Form --}}
        @auth
        <div class="bg-slate-50 dark:bg-slate-800/30 rounded-[2rem] p-8 mb-12 border border-slate-100 dark:border-slate-800">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Viết đánh giá của bạn</h3>
            <form id="comment-form" class="space-y-6">
                @csrf
                <input type="hidden" name="ServiceID" value="{{ $service->ServiceID }}">

                <div class="flex items-center gap-4">
                    <span class="text-slate-600 dark:text-slate-400 font-bold">Chất lượng dịch vụ:</span>
                    <div class="flex gap-2" id="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="text-3xl text-slate-300 hover:text-amber-400 transition-colors star-btn transform hover:scale-110" data-rating="{{ $i }}">
                            <span class="material-symbols-outlined fill-current">star</span>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="Rating" id="rating-value" value="5">
                </div>

                <textarea name="Comment" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về dịch vụ này..."
                    class="w-full px-6 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all resize-none text-lg"
                    required minlength="3" maxlength="1000"></textarea>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-slate-900 font-black py-4 px-10 rounded-2xl shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                        <span class="material-symbols-outlined">send</span>
                        GỬI ĐÁNH GIÁ
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="bg-slate-50 dark:bg-slate-900 rounded-3xl p-10 mb-12 text-center border-2 border-dashed border-slate-200 dark:border-slate-800">
            <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl text-primary">lock_open</span>
            </div>
            <p class="text-slate-600 dark:text-slate-400 text-lg mb-4">Vui lòng đăng nhập để viết đánh giá</p>
            <a href="{{ route('login') }}" class="inline-block bg-primary hover:bg-primary-dark text-slate-900 font-black py-3 px-8 rounded-xl transition-all shadow-lg hover:scale-105 active:scale-95">
                ĐĂNG NHẬP NGAY
            </a>
        </div>
        @endauth

        {{-- Comments List --}}
        <div class="space-y-6" id="comments-list">
            @forelse($reviews as $review)
                <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-6">
                        @php
                            $cName = $review->customer ? $review->customer->FullName : 'Khách yêu';
                            $cAvatar = $review->customer && $review->customer->Avatar ? $review->customer->Avatar : null;
                            $avatarUrl = $cAvatar
                                ? (str_starts_with($cAvatar, 'http') ? $cAvatar : asset('storage/' . $cAvatar))
                                : 'https://ui-avatars.com/api/?name=' . urlencode($cName) . '&background=F4C2C3&color=fff&size=128';
                            $isAdmin = $review->customer && $review->customer->RoleID == 1;
                            $canDelete = $currentUser && ($currentUser->RoleID == 1 || $currentUser->UserID == $review->CustomerID);
                        @endphp
                        <img src="{{ $avatarUrl }}" alt="{{ $cName }}" class="w-16 h-16 rounded-full ring-4 ring-primary/10 shrink-0">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h5 class="font-black text-slate-900 dark:text-white text-lg inline-flex items-center gap-2">
                                        {{ $cName }}
                                        @if($isAdmin)
                                            <span class="px-2 py-0.5 bg-red-500 text-white text-xs rounded-full font-medium">Admin</span>
                                        @endif
                                    </h5>
                                    <div class="flex items-center gap-1 text-amber-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-sm {{ $i <= $review->Rating ? 'fill-current' : 'text-slate-200' }}">star</span>
                                        @endfor
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 text-xs font-medium">{{ \Carbon\Carbon::parse($review->CreatedAt)->diffForHumans() }}</span>
                                    @if($canDelete)
                                        <button onclick="deleteReview({{ $review->ReviewID }})" class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Xóa đánh giá">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-base italic">{{ $review->Comment }}</p>
                            
                            {{-- Store Response --}}
                            @if($review->replies && $review->replies->count() > 0)
                                @foreach($review->replies as $reply)
                                    @if(!$reply->Deleted)
                                        <div class="mt-4 bg-primary/10 dark:bg-primary/5 rounded-2xl p-5 flex gap-4 border border-primary/20">
                                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center shrink-0 shadow-lg">
                                                <span class="material-symbols-outlined text-slate-900 text-sm">paws</span>
                                            </div>
                                            <div>
                                                <h6 class="font-black text-sm text-primary-dark dark:text-primary mb-1 inline-flex items-center gap-2">
                                                    Pink Charcoal Reply
                                                    <span class="bg-primary/20 text-primary-dark dark:text-primary text-[8px] px-2 py-0.5 rounded-full uppercase">Official</span>
                                                </h6>
                                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ $reply->Comment }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-slate-50 dark:bg-slate-900/50 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <span class="material-symbols-outlined text-5xl text-slate-300 mb-4">rate_review</span>
                    <p class="text-slate-500 text-lg">Chưa có đánh giá nào. Hãy là người đầu tiên trải nghiệm!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function openSupportWithService(serviceName, servicePrice, serviceUrl) {
        if (typeof toggleSupportChat === 'function') {
            toggleSupportChat();
        }

        setTimeout(async function() {
            const message = `Xin chào! Tôi muốn được tư vấn thêm về dịch vụ "${serviceName}" (giá: ${new Intl.NumberFormat('vi-VN').format(servicePrice)}đ). ✨🐾`;
            
            try {
                const response = await fetch('{{ route("support.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                });
                
                if (response.ok) {
                    if (typeof appendMessage === 'function') {
                        appendMessage(message, false, new Date());
                    }
                }
            } catch (error) {
                console.error('Error sending service info:', error);
            }
        }, 800);
    }
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const starBtns = document.querySelectorAll('.star-btn');
    const ratingValue = document.getElementById('rating-value');

    function updateStars(rating) {
        starBtns.forEach((btn, index) => {
            const icon = btn.querySelector('span');
            if (index < rating) {
                icon.classList.remove('text-slate-300');
                icon.classList.add('text-amber-400');
                icon.style.fontVariationSettings = "'FILL' 1";
            } else {
                icon.classList.remove('text-amber-400');
                icon.classList.add('text-slate-300');
                icon.style.fontVariationSettings = "'FILL' 0";
            }
        });
        ratingValue.value = rating;
    }

    starBtns.forEach(btn => {
        btn.addEventListener('click', () => updateStars(parseInt(btn.dataset.rating)));
    });

    const form = document.getElementById('comment-form');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('service-reviews.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    alert('Cảm ơn bạn đã gửi đánh giá! ✨');
                    window.location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại sau.');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }

    // Delete review function
    window.deleteReview = async function(reviewId) {
        if (!confirm('Bạn có chắc muốn xóa đánh giá này?')) {
            return;
        }

        try {
            const response = await fetch(`/services/reviews/${reviewId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        }
    };
});
</script>
@endpush

@endsection
