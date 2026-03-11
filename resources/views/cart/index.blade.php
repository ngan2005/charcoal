@extends('layouts.shop')

@section('title', 'Giỏ hàng của bạn - Pink Charcoal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity buttons
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const action = this.dataset.action;
            const itemEl = document.querySelector(`.cart-item[data-item-id="${id}"]`);
            const input = itemEl.querySelector('.qty-input');
            let qty = parseInt(input.value);

            if (action === 'decrease' && qty > 1) {
                qty--;
            } else if (action === 'increase') {
                qty++;
            } else {
                return;
            }

            updateQuantity(id, qty);
        });
    });

    function updateQuantity(id, qty) {
        fetch(`/cart/item/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ quantity: qty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update quantity input
                const itemEl = document.querySelector(`.cart-item[data-item-id="${id}"]`);
                itemEl.querySelector('.qty-input').value = qty;
                itemEl.querySelector('.item-total').textContent = data.itemTotal;

                // Update subtotal and count
                document.querySelector('.cart-subtotal').textContent = data.subtotal;
                document.querySelector('.cart-count').textContent = `Tạm tính (${data.itemCount} món)`;

                // Update total in order summary
                document.querySelectorAll('.cart-subtotal').forEach(el => {
                    el.textContent = data.subtotal;
                });
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        });
    }
});
</script>
@endpush

@section('content')
<div class="w-full max-w-[1200px] mx-auto min-h-[60vh] flex flex-col pt-4 pb-12">
    <div class="flex items-center gap-3 mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white font-display flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-4xl">shopping_cart</span>
            Giỏ Hàng Của Bạn
        </h1>
        <span class="bg-primary/10 text-primary-dark dark:text-primary font-bold px-4 py-1.5 rounded-full text-sm mt-1">
            {{ $cartItems->count() }} sản phẩm
        </span>
    </div>

    @if($cartItems->count() > 0)
    <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 relative items-start">
        {{-- Cart Items List --}}
        <div class="w-full lg:w-2/3 flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 p-2 md:p-6 flex flex-col gap-2">
                
                {{-- Table Header (Hidden on mobile) --}}
                <div class="hidden md:flex items-center px-4 pb-4 border-b border-slate-100 dark:border-slate-800 text-slate-500 text-sm font-medium">
                    <div class="w-[55%]">Sản phẩm / Dịch vụ</div>
                    <div class="w-[15%] text-center">Đơn giá</div>
                    <div class="w-[15%] text-center">Số lượng</div>
                    <div class="w-[15%] text-right pr-4">Tạm tính</div>
                </div>

                {{-- Cart Items --}}
                @foreach($cartItems as $item)
                    @php
                        $name = $item->ProductID ? ($item->ProductName ?? '') : ($item->ServiceName ?? '');
                        $price = $item->ProductID ? ($item->ProductPrice ?? 0) : ($item->ServicePrice ?? 0);
                        $total = $price * $item->Quantity;
                        $imageUrl = $item->ImageURL ?? null;
                        $imgUrl = $imageUrl ? (str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl)) : 'https://placehold.co/150x150/F4C2C3/ffffff?text=' . urlencode($name ?: 'N/A');
                        $typeBadge = $item->ProductID ? 'Sản phẩm' : 'Dịch vụ';
                        $routeLink = $item->ProductID ? route('shop', ['search' => $name]) : route('services.show', $item->ServiceID ?? 0);
                    @endphp

                    <div class="cart-item flex flex-col md:flex-row md:items-center gap-4 p-4 rounded-2xl bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group relative" data-item-id="{{ $item->CartItemID }}" data-price="{{ $price }}">
                        
                        {{-- Remove button (Mobile) --}}
                        <form action="{{ route('cart.destroy', $item->CartItemID) }}" method="POST" class="absolute top-4 right-4 md:static md:hidden z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <span class="material-symbols-outlined text-sm">close</span>
                            </button>
                        </form>

                        {{-- Product Info --}}
                        <div class="w-full md:w-[55%] flex items-center gap-4">
                            <a href="{{ $routeLink }}" class="w-24 h-24 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0 border border-slate-100 dark:border-slate-800">
                                <img src="{{ $imgUrl }}" alt="{{ $name }}" class="w-full h-full object-cover">
                            </a>
                            <div class="flex flex-col gap-1 pr-8 md:pr-0">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-primary">{{ $typeBadge }}</span>
                                <a href="{{ $routeLink }}" class="font-bold text-slate-900 dark:text-white text-base hover:text-primary transition-colors line-clamp-2 leading-tight">
                                    {{ $name }}
                                </a>
                                <div class="md:hidden font-bold text-primary mt-1">
                                    {{ number_format($price, 0, ',', '.') }}đ
                                </div>
                            </div>
                        </div>

                        {{-- Price (Desktop) --}}
                        <div class="hidden md:flex w-[15%] justify-center font-bold text-slate-700 dark:text-slate-300">
                            {{ number_format($price, 0, ',', '.') }}đ
                        </div>

                        {{-- Quantity Control --}}
                        <div class="w-full md:w-[15%] flex justify-between md:justify-center items-center mt-2 md:mt-0">
                            <span class="md:hidden text-sm text-slate-500 font-medium">Số lượng:</span>
                            <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-full p-1 border border-slate-200 dark:border-slate-700">
                                <button type="button" class="qty-btn w-8 h-8 flex items-center justify-center rounded-full text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all focus:outline-none" data-action="decrease" data-id="{{ $item->CartItemID }}">
                                    <span class="material-symbols-outlined text-sm">remove</span>
                                </button>
                                <input type="text" value="{{ $item->Quantity }}" class="qty-input w-8 text-center bg-transparent border-none text-sm font-bold text-slate-900 dark:text-white focus:ring-0 p-0" readonly data-id="{{ $item->CartItemID }}">
                                <button type="button" class="qty-btn w-8 h-8 flex items-center justify-center rounded-full text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all focus:outline-none" data-action="increase" data-id="{{ $item->CartItemID }}">
                                    <span class="material-symbols-outlined text-sm">add</span>
                                </button>
                            </div>
                        </div>

                        {{-- Total Price & Remove (Desktop) --}}
                        <div class="hidden md:flex w-[15%] justify-end items-center gap-4">
                            <div class="item-total font-extrabold text-primary text-lg">
                                {{ number_format($total, 0, ',', '.') }}đ
                            </div>
                            <form action="{{ route('cart.destroy', $item->CartItemID) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors shrink-0">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between items-center mt-2 px-2">
                <a href="{{ route('shop') }}" class="text-slate-500 hover:text-primary font-medium flex items-center gap-1 transition-colors text-sm">
                    <span class="material-symbols-outlined text-sm">arrow_back</span> Tiếp tục mua sắm
                </a>
                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-600 font-medium text-sm flex items-center gap-1 transition-colors">
                        <span class="material-symbols-outlined text-sm">delete_sweep</span> Xóa toàn bộ
                    </button>
                </form>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="w-full lg:w-1/3 lg:sticky lg:top-24">
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-lg shadow-primary/5 border border-primary/20 dark:border-slate-800 p-8 flex flex-col gap-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -z-0"></div>
                
                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-4 relative z-10 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>
                    Tổng Đơn Hàng
                </h3>

                <div class="flex flex-col gap-4 relative z-10">
                    <div class="flex justify-between items-center text-slate-600 dark:text-slate-400 text-sm font-medium">
                        <span class="cart-count">Tạm tính ({{ $cartItems->count() }} món)</span>
                        <span class="cart-subtotal font-bold text-slate-900 dark:text-white">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                    </div>
                    
                    <div class="flex justify-between items-center text-slate-600 dark:text-slate-400 text-sm font-medium">
                        <span>Phí vận chuyển</span>
                        <span>Chưa tính</span>
                    </div>

                    <div class="flex justify-between items-center text-slate-600 dark:text-slate-400 text-sm font-medium">
                        <span>Giảm giá</span>
                        <span class="text-emerald-500">- 0đ</span>
                    </div>

                    <div class="flex flex-col gap-2 pt-4 border-t border-dashed border-slate-200 dark:border-slate-700 mt-2">
                        <div class="flex justify-between items-end">
                            <span class="font-bold text-slate-900 dark:text-white text-lg">Tổng cộng</span>
                            <span class="font-extrabold text-primary text-3xl cart-subtotal">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <p class="text-[11px] text-slate-400 text-right">(Đã bao gồm VAT nếu có)</p>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="mt-4 flex flex-col gap-3 relative z-10">
                    <a href="{{ route('checkout.index') }}" class="w-full bg-primary hover:bg-primary-dark text-slate-900 font-bold text-lg py-4 rounded-2xl shadow-[0_4px_14px_0_rgba(244,194,195,0.39)] hover:shadow-[0_6px_20px_rgba(244,194,195,0.23)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 group text-center">
                        Thanh Toán Ngay
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                    
                    <div class="flex items-center justify-center gap-2 text-slate-400 text-xs mt-2">
                        <span class="material-symbols-outlined text-sm">lock</span>
                        Thanh toán an toàn & bảo mật
                    </div>
                </div>
            </div>
            
            {{-- Discount Code Promo --}}
            <div class="mt-6 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-2xl p-5 border border-emerald-100 dark:border-emerald-800/50 flex gap-4 items-center">
                <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shrink-0 shadow-sm text-emerald-500">
                    <span class="material-symbols-outlined">redeem</span>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm">Có mã giảm giá?</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Bạn có thể nhập ở bước thanh toán cuối cùng.</p>
                </div>
            </div>
        </div>
    </div>
    @else
        {{-- Empty Cart State --}}
        <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 text-center mt-4">
            <div class="w-40 h-40 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-8 relative">
                <div class="absolute inset-0 bg-primary/20 rounded-full animate-ping opacity-20"></div>
                <span class="material-symbols-outlined text-7xl text-slate-300 dark:text-slate-600 relative z-10">shopping_cart</span>
                
                {{-- Decorative floating elements --}}
                <span class="material-symbols-outlined absolute top-4 -right-4 text-rose-300 text-2xl -rotate-12 animate-bounce">pets</span>
                <span class="material-symbols-outlined absolute bottom-4 -left-4 text-emerald-300 text-3xl rotate-12 animate-pulse">spa</span>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Giỏ hàng của bạn đang trống</h2>
            <p class="text-slate-500 mb-8 max-w-md">Có vẻ như bạn chưa chọn sản phẩm hay dịch vụ nào. Khám phá ngay các dịch vụ làm đẹp thú cưng tuyệt vời của chúng tôi nhé!</p>
            
            <div class="flex gap-4">
                <a href="{{ route('shop') }}" class="bg-primary hover:bg-primary-dark text-slate-900 font-bold py-3 px-8 rounded-full shadow-md hover:-translate-y-1 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">shopping_bag</span>
                    Mua sản phẩm
                </a>
                <a href="{{ route('services.index') }}" class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-bold py-3 px-8 rounded-full transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">spa</span>
                    Xem dịch vụ
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
