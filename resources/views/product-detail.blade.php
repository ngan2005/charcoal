@extends('layouts.shop')

@section('content')
@php
    $currentUser = Auth::user();
@endphp
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
                        <input type="hidden" name="ProductID" value="{{ $product->ProductID }}">
                        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-slate-900 font-bold py-4 px-6 rounded-2xl shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 group overflow-hidden relative">
                            <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 skew-x-[-20deg]"></div>
                            <span class="material-symbols-outlined text-xl group-hover:rotate-12 transition-transform">add_shopping_cart</span>
                            <span class="text-base">THÊM VÀO GIỎ HÀNG</span>
                        </button>
                    </form>
                    
                    <form action="{{ route('cart.store') }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="ProductID" value="{{ $product->ProductID }}">
                        <input type="hidden" name="Quantity" value="1">
                        <input type="hidden" name="redirect" value="checkout">
                        <button type="submit" class="w-full sm:w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-800/50 hover:text-emerald-700 dark:hover:text-emerald-300 transition-all active:scale-90 shadow-sm border border-emerald-200 dark:border-emerald-800" title="Mua luôn">
                            <span class="material-symbols-outlined text-2xl">shopping_bag</span>
                            <span class="sm:hidden ml-2 font-bold text-sm">Mua luôn</span>
                        </button>
                    </form>
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

    {{-- Frequently Bought Together --}}
    @if($frequentlyBoughtTogether->count() > 0)
    <div class="mt-20">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-slate-900 dark:text-white text-2xl font-black font-display tracking-tight flex items-center gap-3">
                <span class="w-12 h-1 bg-primary rounded-full"></span>
                Thường mua cùng nhau
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($frequentlyBoughtTogether as $item)
                @php
                    $mainImg = $item->images->where('IsMain', 1)->first();
                    $firstImg = $item->images->first();
                    $relUrl = $mainImg ? $mainImg->ImageUrl : ($firstImg ? $firstImg->ImageUrl : '');
                    $fullRelUrl = $relUrl ? (str_starts_with($relUrl, 'http') ? $relUrl : asset('storage/' . $relUrl)) : 'https://placehold.co/800x800/F4C2C3/ffffff?text=' . urlencode($item->ProductName);
                @endphp
                <div class="flex flex-col bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-slate-100 dark:border-slate-800 group hover:-translate-y-2">
                    <div class="aspect-square relative group/img cursor-pointer overflow-hidden" onclick="window.location.href='{{ route('product.show', $item->ProductID) }}'">
                        <img src="{{ $fullRelUrl }}" class="w-full h-full object-cover transform group-hover/img:scale-110 transition-transform duration-700 ease-out">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="bg-white/90 text-slate-900 rounded-full p-2.5 shadow-lg backdrop-blur-sm transform translate-y-4 group-hover/img:translate-y-0 transition-all duration-300">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col gap-3">
                        <h3 class="text-slate-900 dark:text-white font-bold text-base leading-snug line-clamp-2 hover:text-primary transition-colors cursor-pointer" onclick="window.location.href='{{ route('product.show', $item->ProductID) }}'">
                            {{ $item->ProductName }}
                        </h3>
                        <div class="flex items-center justify-between mt-2 pt-4 border-t border-dashed border-slate-200 dark:border-slate-800">
                            <span class="text-xl font-black text-slate-900 dark:text-white">
                                {{ number_format($item->Price, 0, ',', '.') }}đ
                            </span>
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ProductID" value="{{ $item->ProductID }}">
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
                                <input type="hidden" name="ProductID" value="{{ $related->ProductID }}">
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

    {{-- Comments Section --}}
    <div class="mt-20" id="comments-section">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-slate-900 dark:text-white text-2xl font-black font-display tracking-tight flex items-center gap-3">
                <span class="w-12 h-1 bg-primary rounded-full"></span>
                Đánh giá & Bình luận
            </h2>
        </div>

        {{-- Form bình luận (chỉ khi đăng nhập) --}}
        @auth
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 mb-8 shadow-lg border border-slate-100 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Viết đánh giá của bạn</h3>
            <form id="comment-form" class="space-y-4">
                @csrf
                <input type="hidden" name="ProductID" value="{{ $product->ProductID }}">

                {{-- Rating stars --}}
                <div class="flex items-center gap-2">
                    <span class="text-slate-600 dark:text-slate-400 text-sm">Đánh giá:</span>
                    <div class="flex gap-1" id="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="text-2xl text-slate-300 hover:text-amber-400 transition-colors star-btn" data-rating="{{ $i }}">
                            <span class="material-symbols-outlined">star</span>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="Rating" id="rating-value" value="5">
                </div>

                {{-- Nội dung --}}
                <textarea name="Content" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                    required minlength="3" maxlength="1000"></textarea>

                <button type="submit" class="bg-primary hover:bg-primary-dark text-slate-900 font-bold py-3 px-6 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined">send</span>
                    Gửi đánh giá
                </button>
            </form>
        </div>
        @else
        <div class="bg-slate-50 dark:bg-slate-900 rounded-3xl p-6 mb-8 text-center border border-slate-200 dark:border-slate-800">
            <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">login</span>
            <p class="text-slate-600 dark:text-slate-400 mb-3">Vui lòng đăng nhập để viết đánh giá</p>
            <a href="{{ route('login') }}" class="inline-block bg-primary hover:bg-primary-dark text-slate-900 font-bold py-2 px-6 rounded-xl transition-all">
                Đăng nhập ngay
            </a>
        </div>
        @endauth

        {{-- Danh sách bình luận --}}
        <div id="comments-list" class="space-y-4">
            <div class="text-center py-8 text-slate-500">
                <span class="material-symbols-outlined text-3xl animate-spin">sync</span>
                <p class="mt-2">Đang tải đánh giá...</p>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for comments --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productId = {{ $product->ProductID }};
    const currentUserId = {{ $currentUser ? $currentUser->UserID : 'null' }};
    const currentUserRole = {{ $currentUser ? $currentUser->RoleID : 'null' }};

    // Load comments on page load
    loadComments();

    // Rating stars handling
    const starBtns = document.querySelectorAll('.star-btn');
    const ratingValue = document.getElementById('rating-value');

    function updateStars(rating) {
        starBtns.forEach((btn, index) => {
            const icon = btn.querySelector('span');
            if (index < rating) {
                icon.classList.remove('text-slate-300');
                icon.classList.add('text-amber-400');
            } else {
                icon.classList.remove('text-amber-400');
                icon.classList.add('text-slate-300');
            }
        });
        ratingValue.value = rating;
    }

    starBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            updateStars(parseInt(btn.dataset.rating));
        });
        btn.addEventListener('mouseenter', () => {
            updateStars(parseInt(btn.dataset.rating));
        });
    });

    // Submit comment
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(commentForm);
            const content = formData.get('Content');
            const rating = formData.get('Rating');

            if (content.length < 3) {
                alert('Bình luận phải có ít nhất 3 ký tự!');
                return;
            }

            try {
                const response = await fetch('{{ route('comments.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ProductID: productId,
                        Content: content,
                        Rating: rating
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    commentForm.reset();
                    updateStars(5);
                    loadComments();
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    }

    // Load comments function
    async function loadComments() {
        const commentsList = document.getElementById('comments-list');

        try {
            const response = await fetch(`/comments/${productId}`);
            const data = await response.json();

            if (data.success && data.comments.length > 0) {
                let html = '';
                data.comments.forEach(comment => {
                    const stars = '★'.repeat(comment.Rating) + '☆'.repeat(5 - comment.Rating);
                    const userName = comment.customer ? (comment.customer.FullName || comment.customer.full_name || 'Người dùng') : 'Người dùng';
                    const userAvatar = comment.customer && (comment.customer.Avatar || comment.customer.avatar);
                    const avatar = userAvatar
                        ? (userAvatar.startsWith('http') ? userAvatar : '/storage/' + userAvatar)
                        : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(userName) + '&background=random';
                    const date = new Date(comment.CreatedAt).toLocaleDateString('vi-VN', {
                        day: '2-digit', month: '2-digit', year: 'numeric'
                    });
                    const roleBadge = comment.customer && comment.customer.role_name === 'Admin'
                        ? '<span class="ml-2 px-2 py-0.5 bg-red-500 text-white text-xs rounded-full font-medium">Admin</span>'
                        : '';
                    const canDelete = (currentUserId && (currentUserRole === 1 || currentUserId === comment.CustomerID))
                        ? `<button onclick="deleteComment(${comment.ReviewID})" class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Xóa bình luận">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                            </svg>
                           </button>`
                        : '';

                    // Generate replies HTML
                    let repliesHtml = '';
                    if (comment.replies && comment.replies.length > 0) {
                        comment.replies.forEach(reply => {
                            const replyUserName = reply.customer ? (reply.customer.FullName || 'Admin') : 'Admin';
                            const replyAvatar = reply.customer && reply.customer.Avatar
                                ? (reply.customer.Avatar.startsWith('http') ? reply.customer.Avatar : '/storage/' + reply.customer.Avatar)
                                : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(replyUserName) + '&background=FF6B6B&color=fff&size=128';
                            const replyRoleBadge = reply.customer && reply.customer.role_name === 'Admin'
                                ? '<span class="ml-2 px-2 py-0.5 bg-red-500 text-white text-xs rounded-full font-medium">Admin</span>'
                                : '';
                            const replyDate = new Date(reply.CreatedAt).toLocaleDateString('vi-VN', {
                                day: '2-digit', month: '2-digit', year: 'numeric'
                            });

                            repliesHtml += `
                            <div class="mt-4 bg-primary/10 dark:bg-primary/5 rounded-2xl p-5 flex gap-4 border border-primary/20">
                                <img src="${replyAvatar}" alt="${replyUserName}" class="w-10 h-10 rounded-full ring-2 ring-primary/20 shrink-0">
                                <div class="flex-1">
                                    <h6 class="font-black text-sm text-primary-dark dark:text-primary mb-1 inline-flex items-center gap-2">
                                        ${replyUserName}${replyRoleBadge}
                                        <span class="bg-primary/20 text-primary-dark dark:text-primary text-[8px] px-2 py-0.5 rounded-full uppercase">Official</span>
                                        <span class="text-slate-400 text-xs font-normal ml-2">${replyDate}</span>
                                    </h6>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">${reply.Comment}</p>
                                </div>
                            </div>`;
                        });
                    }

                    html += `
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-800">
                        <div class="flex items-start gap-4">
                            <img src="${avatar}" alt="${userName}"
                                class="w-12 h-12 rounded-full object-cover ring-2 ring-primary/20">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span class="font-bold text-slate-900 dark:text-white">${userName}</span>${roleBadge}
                                        <span class="text-amber-400 ml-2 text-sm">${stars}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400 text-sm">${date}</span>
                                        ${canDelete}
                                    </div>
                                </div>
                                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">${comment.Comment}</p>
                                ${repliesHtml}
                            </div>
                        </div>
                    </div>`;
                });
                commentsList.innerHTML = html;
            } else {
                commentsList.innerHTML = `
                    <div class="text-center py-8 text-slate-500 bg-slate-50 dark:bg-slate-900 rounded-2xl">
                        <span class="material-symbols-outlined text-4xl mb-2">rate_review</span>
                        <p>Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                    </div>`;
            }
        } catch (error) {
            console.error('Error loading comments:', error);
            commentsList.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <p>Không thể tải đánh giá. Vui lòng tải lại trang.</p>
                </div>`;
        }
    }

    // Delete comment function
    window.deleteComment = async function(commentId) {
        if (!confirm('Bạn có chắc muốn xóa bình luận này?')) {
            return;
        }

        try {
            const response = await fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                loadComments();
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
