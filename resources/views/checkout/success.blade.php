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
                        @php
                            $statusLabel = match($order->Status ?? 'pending') {
                                'pending' => ['Đang chờ xử lý', 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400', 'bg-yellow-500'],
                                'confirmed' => ['Đã xác nhận', 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'bg-blue-500'],
                                'processing' => ['Đang xử lý', 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'bg-amber-500'],
                                'shipping' => ['Đang giao hàng', 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400', 'bg-indigo-500'],
                                'delivered', 'completed' => ['Hoàn thành', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'bg-emerald-500'],
                                'cancelled' => ['Đã hủy', 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300', 'bg-slate-500'],
                                default => [$order->Status ?? 'Đang chờ xử lý', 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400', 'bg-yellow-500'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $statusLabel[1] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusLabel[2] }} {{ $order->Status === 'pending' ? 'animate-pulse' : '' }}"></span>
                            {{ $statusLabel[0] }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Thanh toán</span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $order->PaymentStatus === 'paid' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                            <span class="material-symbols-outlined text-sm">{{ $order->PaymentStatus === 'paid' ? 'check_circle' : 'schedule' }}</span>
                            {{ $order->PaymentStatus === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Phương thức</span>
                        <span class="font-medium">
                            @if(($order->PaymentMethod ?? 'cod') === 'vnpay')
                                <span class="inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-red-500">account_balance</span>
                                    VNPay
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-emerald-500">payments</span>
                                    COD
                                </span>
                            @endif
                        </span>
                    </div>
                    @if($order->PaymentTransactionRef)
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Mã giao dịch</span>
                            <span class="font-mono text-xs text-slate-600 dark:text-slate-300">{{ $order->PaymentTransactionRef }}</span>
                        </div>
                    @endif
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

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center flex-wrap">
        <a href="{{ route('profile.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary-dark text-slate-900 font-bold rounded-full transition-all hover:-translate-y-1">
            <span class="material-symbols-outlined text-sm">visibility</span>
            Xem đơn hàng của tôi
        </a>
        @if(in_array($order->Status ?? '', ['pending', 'confirmed']))
            <form action="{{ route('profile.orders.cancel', $order->OrderID) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này? Tồn kho sẽ được hoàn lại.');">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 font-bold rounded-full transition-all border border-red-200 dark:border-red-800">
                    <span class="material-symbols-outlined text-sm">cancel</span>
                    Hủy đơn
                </button>
            </form>
        @endif
        <a href="{{ route('shop') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-bold rounded-full transition-all">
            <span class="material-symbols-outlined text-sm">shopping_bag</span>
            Tiếp tục mua sắm
        </a>
    </div>
</div>
@endsection
