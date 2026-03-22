@extends('layouts.shop')

@section('title', 'Thanh toán đơn hàng - Pink Charcoal')

@push('styles')
<style>
    .checkout-step {
        @apply flex items-center gap-3;
    }
    .checkout-step-number {
        @apply w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all;
    }
    .checkout-step.active .checkout-step-number {
        @apply bg-primary text-slate-900;
    }
    .checkout-step.completed .checkout-step-number {
        @apply bg-emerald-500 text-white;
    }
    .checkout-step.pending .checkout-step-number {
        @apply bg-slate-200 dark:bg-slate-700 text-slate-400;
    }
    .checkout-step-label {
        @apply font-medium text-sm hidden sm:block;
    }
    .checkout-step.active .checkout-step-label {
        @apply text-slate-900 dark:text-white;
    }
    .checkout-step.completed .checkout-step-label {
        @apply text-emerald-600 dark:text-emerald-400;
    }
    .checkout-step.pending .checkout-step-label {
        @apply text-slate-400;
    }
    .payment-card {
        @apply relative cursor-pointer border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-4 hover:border-primary dark:hover:border-primary transition-all;
    }
    .payment-card.selected {
        @apply border-primary bg-primary/5;
    }
    .payment-card input:checked + div {
        @apply border-primary bg-primary/5;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-6 max-w-7xl mx-auto">
    {{-- Breadcrumbs --}}
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('shop') }}" class="text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white inline-flex items-center text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-base mr-2">home</span>
                    Trang chủ
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                    <a href="{{ route('cart.index') }}" class="text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white text-sm font-medium ml-1 md:ml-2 transition-colors">
                        Giỏ hàng
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-slate-400 text-sm mx-1">chevron_right</span>
                    <span class="text-slate-900 dark:text-white text-sm font-bold ml-1 md:ml-2">Thanh toán</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Page Title --}}
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-2">Thanh toán đơn hàng</h1>
        <p class="text-slate-500 dark:text-slate-400">Vui lòng điền thông tin để hoàn tất đơn hàng của bạn</p>
    </div>

    {{-- Checkout Steps --}}
    <div class="flex items-center justify-center gap-4 md:gap-8 mb-10 pb-6 border-b border-slate-200 dark:border-slate-700">
        <div class="checkout-step completed">
            <div class="checkout-step-number">
                <span class="material-symbols-outlined text-sm">check</span>
            </div>
            <span class="checkout-step-label">Giỏ hàng</span>
        </div>
        <div class="w-8 md:w-16 h-0.5 bg-emerald-500"></div>
        <div class="checkout-step active">
            <div class="checkout-step-number">2</div>
            <span class="checkout-step-label">Thanh toán</span>
        </div>
        <div class="w-8 md:w-16 h-0.5 bg-slate-200 dark:bg-slate-700"></div>
        <div class="checkout-step pending">
            <div class="checkout-step-number">3</div>
            <span class="checkout-step-label">Hoàn tất</span>
        </div>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Column - Form --}}
            <div class="lg:col-span-7 space-y-6">
                {{-- Shipping Information --}}
                <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">local_shipping</span>
                        Thông tin giao hàng
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="shipping_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="shipping_name" 
                                   id="shipping_name" 
                                   value="{{ old('shipping_name', $user->FullName ?? '') }}"
                                   class="form-control form-control-lg rounded-xl @error('shipping_name') is-invalid @enderror"
                                   placeholder="Nhập họ và tên">
                            @error('shipping_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="shipping_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" 
                                   name="shipping_phone" 
                                   id="shipping_phone"
                                   value="{{ old('shipping_phone', $user->Phone ?? '') }}"
                                   class="form-control form-control-lg rounded-xl @error('shipping_phone') is-invalid @enderror"
                                   placeholder="Nhập số điện thoại">
                            @error('shipping_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="shipping_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   name="shipping_email" 
                                   id="shipping_email"
                                   value="{{ old('shipping_email', $user->Email ?? '') }}"
                                   class="form-control form-control-lg rounded-xl bg-slate-50 dark:bg-slate-800"
                                   readonly>
                        </div>

                        <div class="md:col-span-2">
                            <label for="shipping_address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Địa chỉ giao hàng <span class="text-red-500">*</span>
                            </label>
                            <textarea name="shipping_address" 
                                      id="shipping_address" 
                                      rows="3"
                                      class="form-control form-control-lg rounded-xl @error('shipping_address') is-invalid @enderror"
                                      placeholder="Nhập địa chỉ đầy đủ (số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố)">{{ old('shipping_address', $user->Address ?? '') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="order_note" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Ghi chú đơn hàng
                            </label>
                            <textarea name="order_note" 
                                      id="order_note" 
                                      rows="2"
                                      class="form-control rounded-xl"
                                      placeholder="Ghi chú thêm cho đơn hàng (không bắt buộc)">{{ old('order_note') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">payments</span>
                        Phương thức thanh toán
                    </h2>

                    {{-- Thanh toán khi nhận hàng --}}
                    <label class="payment-card block mb-3 {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}">
                        <input type="radio" name="payment_method" value="cash" class="hidden" {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }}>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">payments</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-900 dark:text-white">Thanh toán khi nhận hàng</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Trả tiền mặt khi nhận được sản phẩm</p>
                            </div>
                            <span class="material-symbols-outlined text-emerald-500 payment-check">check_circle</span>
                        </div>
                    </label>

                    {{-- MoMo (disabled for now) --}}
                    <label class="payment-card block opacity-50 cursor-not-allowed">
                        <input type="radio" name="payment_method" value="momo" class="hidden" disabled>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center shrink-0">
                                <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="24" height="24" rx="4" fill="#FF0080"/>
                                    <circle cx="12" cy="12" r="6" fill="white"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-900 dark:text-white">MoMo</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Sắp có mặt</p>
                            </div>
                            <span class="material-symbols-outlined text-slate-400">lock</span>
                        </div>
                    </label>

                    @error('payment_method')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Right Column - Order Summary --}}
            <div class="lg:col-span-5">
                <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-lg shadow-primary/5 border border-primary/20 dark:border-slate-800 p-6 md:p-8 sticky top-24">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">receipt_long</span>
                        Đơn hàng của bạn
                    </h2>

                    {{-- Cart Items --}}
                    <div class="space-y-4 mb-6 max-h-[300px] overflow-y-auto custom-scrollbar">
                        @foreach($cartItems as $item)
                            <div class="flex gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                <div class="w-16 h-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center overflow-hidden shrink-0">
                                    @if($item->ProductID)
                                        <span class="material-symbols-outlined text-slate-400">inventory_2</span>
                                    @else
                                        <span class="material-symbols-outlined text-slate-400">spa</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-slate-900 dark:text-white text-sm line-clamp-1">
                                        {{ $item->ProductName ?? $item->ServiceName }}
                                    </h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ $item->ProductID ? 'Sản phẩm' : 'Dịch vụ' }} | SL: {{ $item->Quantity }}
                                    </p>
                                    <p class="font-bold text-primary text-sm mt-1">
                                        {{ number_format(($item->ProductPrice ?? $item->ServicePrice ?? 0) * $item->Quantity, 0, ',', '.') }}đ
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Voucher Input --}}
                    <div class="border-t border-dashed border-slate-200 dark:border-slate-700 pt-4 pb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            <span class="material-symbols-outlined text-sm align-middle mr-1">redeem</span>
                            Mã giảm giá
                        </label>
                        <div class="flex gap-2">
                            <input type="text" 
                                   name="voucher_code" 
                                   id="voucher_code"
                                   class="form-control rounded-xl flex-1 @error('voucher_code') is-invalid @enderror"
                                   placeholder="Nhập mã...">
                            <button type="button" 
                                    id="apply_voucher_btn"
                                    class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-xl transition-colors whitespace-nowrap">
                                Áp dụng
                            </button>
                        </div>
                        <div id="voucher_message" class="mt-2 text-sm hidden"></div>
                        <div id="voucher_info" class="mt-2 p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg hidden">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span id="voucher_desc" class="text-sm font-medium text-emerald-700 dark:text-emerald-400"></span>
                                </div>
                                <button type="button" id="remove_voucher" class="text-slate-400 hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="applied_voucher_id" id="applied_voucher_id" value="">
                    </div>

                    {{-- Summary --}}
                    <div class="border-t border-dashed border-slate-200 dark:border-slate-700 pt-4 space-y-3">
                        <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                            <span class="text-sm">Tạm tính ({{ $cartItems->count() }} món)</span>
                            <span class="font-medium" id="summary_subtotal">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                            <span class="text-sm">Phí vận chuyển</span>
                            <span class="font-medium text-emerald-600" id="shipping_fee">Miễn phí</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                            <span class="text-sm">Giảm giá</span>
                            <span class="font-medium text-emerald-600" id="discount_amount">- 0đ</span>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="border-t border-dashed border-slate-200 dark:border-slate-700 pt-4 mt-4">
                        <div class="flex justify-between items-end">
                            <span class="font-bold text-slate-900 dark:text-white">Tổng cộng</span>
                            <span class="font-extrabold text-primary text-2xl" id="order_total">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1 text-right">(Đã bao gồm VAT nếu có)</p>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full mt-6 bg-primary hover:bg-primary-dark text-slate-900 font-bold text-lg py-4 rounded-2xl shadow-[0_4px_14px_0_rgba(244,194,195,0.39)] hover:shadow-[0_6px_20px_rgba(244,194,195,0.23)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 group">
                        <span class="material-symbols-outlined">lock</span>
                        Đặt hàng ngay
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>

                    {{-- Security Note --}}
                    <div class="flex items-center justify-center gap-2 text-slate-400 text-xs mt-4">
                        <span class="material-symbols-outlined text-sm">verified_user</span>
                        Thanh toán an toàn & bảo mật
                    </div>

                    {{-- Back to Cart --}}
                    <a href="{{ route('cart.index') }}" class="flex items-center justify-center gap-2 text-slate-500 hover:text-primary font-medium text-sm mt-4 transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Quay về giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method selection
    const paymentCards = document.querySelectorAll('.payment-card input[type="radio"]');
    paymentCards.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove selected from all cards
            document.querySelectorAll('.payment-card').forEach(card => {
                card.classList.remove('selected');
                const check = card.querySelector('.payment-check');
                if (check) check.classList.add('hidden');
            });
            
            // Add selected to current card
            if (this.checked) {
                this.closest('.payment-card').classList.add('selected');
                const check = this.closest('.payment-card').querySelector('.payment-check');
                if (check) check.classList.remove('hidden');
            }
        });
    });

    // Initialize first payment method as selected
    const checkedPayment = document.querySelector('.payment-card input[type="radio"]:checked');
    if (checkedPayment) {
        checkedPayment.closest('.payment-card').classList.add('selected');
        const check = checkedPayment.closest('.payment-card').querySelector('.payment-check');
        if (check) check.classList.remove('hidden');
    }

    // Voucher handling
    const subtotal = {{ $subtotal }};
    let currentDiscount = 0;
    let appliedVoucher = null;

    const applyBtn = document.getElementById('apply_voucher_btn');
    const voucherInput = document.getElementById('voucher_code');
    const voucherMessage = document.getElementById('voucher_message');
    const voucherInfo = document.getElementById('voucher_info');
    const voucherDesc = document.getElementById('voucher_desc');
    const removeVoucherBtn = document.getElementById('remove_voucher');
    const appliedVoucherId = document.getElementById('applied_voucher_id');
    const discountEl = document.getElementById('discount_amount');
    const totalEl = document.getElementById('order_total');

    applyBtn.addEventListener('click', function() {
        const code = voucherInput.value.trim();
        if (!code) {
            showMessage('Vui lòng nhập mã giảm giá.', 'error');
            return;
        }

        applyBtn.disabled = true;
        applyBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm">sync</span>';

        fetch('{{ route('checkout.apply-voucher') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: code,
                subtotal: subtotal
            })
        })
        .then(res => res.json())
        .then(data => {
            applyBtn.disabled = false;
            applyBtn.innerHTML = 'Áp dụng';

            if (data.success) {
                appliedVoucher = data.voucher;
                currentDiscount = data.voucher.discount;
                appliedVoucherId.value = data.voucher.id;

                // Show discount info
                let discountText = data.voucher.discount_percent 
                    ? `Giảm ${data.voucher.discount_percent}%` 
                    : `Giảm ${data.voucher.discount_display}đ`;
                voucherDesc.textContent = `${data.voucher.code} - ${discountText}`;

                // Update UI
                discountEl.textContent = `- ${data.voucher.discount_display}đ`;
                const newTotal = Math.max(0, subtotal - currentDiscount);
                totalEl.textContent = new Intl.NumberFormat('vi-VN').format(newTotal) + 'đ';

                voucherInfo.classList.remove('hidden');
                showMessage(data.message, 'success');
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(err => {
            applyBtn.disabled = false;
            applyBtn.innerHTML = 'Áp dụng';
            showMessage('Đã xảy ra lỗi. Vui lòng thử lại.', 'error');
        });
    });

    // Remove voucher
    removeVoucherBtn.addEventListener('click', function() {
        voucherInput.value = '';
        appliedVoucherId.value = '';
        currentDiscount = 0;
        appliedVoucher = null;

        discountEl.textContent = '- 0đ';
        totalEl.textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ';
        voucherInfo.classList.add('hidden');
        voucherMessage.classList.add('hidden');
    });

    function showMessage(message, type) {
        voucherMessage.textContent = message;
        voucherMessage.classList.remove('hidden', 'text-red-500', 'text-emerald-500');
        
        if (type === 'error') {
            voucherMessage.classList.add('text-red-500');
        } else {
            voucherMessage.classList.add('text-emerald-500');
        }
    }

    // Enter key to apply voucher
    voucherInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyBtn.click();
        }
    });

    // Form validation
    const form = document.getElementById('checkout-form');
    form.addEventListener('submit', function(e) {
        let valid = true;
        const required = ['shipping_name', 'shipping_phone', 'shipping_address'];
        
        required.forEach(id => {
            const el = document.getElementById(id);
            if (!el.value.trim()) {
                el.classList.add('is-invalid');
                valid = false;
            } else {
                el.classList.remove('is-invalid');
            }
        });

        // Validate payment method is selected
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!paymentMethod) {
            valid = false;
            alert('Vui lòng chọn phương thức thanh toán.');
        }

        if (!valid) {
            e.preventDefault();
        }
    });

    // Remove invalid class on input
    ['shipping_name', 'shipping_phone', 'shipping_address'].forEach(id => {
        const el = document.getElementById(id);
        el.addEventListener('input', () => el.classList.remove('is-invalid'));
    });
});
</script>
@endpush
