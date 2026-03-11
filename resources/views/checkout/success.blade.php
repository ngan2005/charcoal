@extends('layouts.shop')

@section('title', 'Đặt hàng thành công - Pink Charcoal')

@push('styles')
<style>
    .success-animation {
        animation: scaleIn 0.5s ease-out forwards;
    }
    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }
    .floating-item {
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-6 max-w-4xl mx-auto">
    {{-- Success Animation --}}
    <div class="text-center mb-8">
        <div class="success-animation w-32 h-32 bg-gradient-to-br from-primary/20 to-pink-200 dark:to-pink-900/30 rounded-full flex items-center justify-center mx-auto mb-6 relative">
            <span class="material-symbols-outlined text-6xl text-primary">check_circle</span>
            <span class="absolute -top-2 -right-2 w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white text-lg animate-bounce">✓</span>
        </div>
        
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-3">Đặt hàng thành công!</h1>
        <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto">Cảm ơn bạn đã đặt hàng tại Pink Charcoal. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
    </div>

    {{-- Order Info Cards --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-lg border border-slate-100 dark:border-slate-800 p-6 md:p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Order Details --}}
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>
                    Thông tin đơn hàng
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Mã đơn hàng</span>
                        <span class="font-bold text-primary">{{ $order->OrderCode }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Ngày đặt</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($order->CreatedAt)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Trạng thái</span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                            Đang chờ xử lý
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Thanh toán</span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $order->PaymentStatus === 'paid' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                            <span class="material-symbols-outlined text-sm">{{ $order->PaymentStatus === 'paid' ? 'check_circle' : 'schedule' }}</span>
                            {{ $order->PaymentStatus === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Shipping Info --}}
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">local_shipping</span>
                    Thông tin giao hàng
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block mb-1">Người nhận</span>
                        <span class="font-medium text-slate-900 dark:text-white">{{ $order->ShippingName }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block mb-1">Số điện thoại</span>
                        <span class="font-medium text-slate-900 dark:text-white">{{ $order->ShippingPhone }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block mb-1">Địa chỉ</span>
                        <span class="font-medium text-slate-900 dark:text-white">{{ $order->ShippingAddress }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-lg border border-slate-100 dark:border-slate-800 p-6 md:p-8 mb-8">
        <h3 class="font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">inventory_2</span>
            Sản phẩm đã đặt ({{ $orderDetails->count() }})
        </h3>

        <div class="space-y-4">
            @foreach($orderDetails as $item)
                <div class="flex gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <div class="w-16 h-16 rounded-lg bg-white dark:bg-slate-700 flex items-center justify-center shrink-0">
                        @if($item->ProductID)
                            <span class="material-symbols-outlined text-slate-400">inventory_2</span>
                        @else
                            <span class="material-symbols-outlined text-slate-400">spa</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-slate-900 dark:text-white">{{ $item->ProductName ?? $item->ServiceName }}</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $item->ProductID ? 'Sản phẩm' : 'Dịch vụ' }} | SL: {{ $item->Quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-primary">{{ number_format($item->UnitPrice * $item->Quantity, 0, ',', '.') }}đ</p>
                        <p class="text-xs text-slate-400">{{ number_format($item->UnitPrice, 0, ',', '.') }}đ x {{ $item->Quantity }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border-t border-dashed border-slate-200 dark:border-slate-700 mt-6 pt-6">
            <div class="flex justify-between items-center text-lg">
                <span class="font-bold text-slate-900 dark:text-white">Tổng cộng</span>
                <span class="font-extrabold text-primary text-2xl">{{ number_format($order->TotalAmount, 0, ',', '.') }}đ</span>
            </div>
        </div>
    </div>

    {{-- Email Notification --}}
    <div class="bg-gradient-to-r from-primary/10 to-pink-100 dark:from-primary/5 dark:to-pink-900/20 rounded-2xl p-6 text-center mb-8">
        <div class="flex items-center justify-center gap-2 mb-3">
            <span class="material-symbols-outlined text-primary">email</span>
            <span class="font-medium text-slate-900 dark:text-white">Kiểm tra email của bạn</span>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Chúng tôi đã gửi xác nhận đơn hàng qua email. Bạn có thể theo dõi trạng thái đơn hàng trong phần <a href="{{ route('orders.index') }}" class="text-primary hover:underline font-medium">đơn hàng của tôi</a>.</p>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('orders.show', $order->OrderID) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary-dark text-slate-900 font-bold rounded-full transition-all hover:-translate-y-1">
            <span class="material-symbols-outlined text-sm">visibility</span>
            Xem chi tiết đơn
        </a>
        <a href="{{ route('shop') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-bold rounded-full transition-all">
            <span class="material-symbols-outlined text-sm">shopping_bag</span>
            Tiếp tục mua sắm
        </a>
    </div>
</div>
@endsection
