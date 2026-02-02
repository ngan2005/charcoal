@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary me-3">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="h3 fw-bold text-gray-900">Chi tiết mã giảm giá</h1>
                <p class="text-muted small mb-0">Xem thông tin chi tiết và lịch sử sử dụng</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Voucher Info Card -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <span class="material-symbols-outlined text-warning" style="font-size: 40px;">local_offer</span>
                            </div>
                            <h3 class="fw-bold text-warning">{{ $voucher->Code }}</h3>
                            <span class="badge {{ $voucher->isValid() ? 'bg-success' : 'bg-danger' }} fs-6">
                                {{ $voucher->isValid() ? 'Đang hoạt động' : 'Đã hết hạn' }}
                            </span>
                        </div>

                        <div class="voucher-detail-info">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Phần trăm giảm:</span>
                                <span class="fw-bold text-success">{{ $voucher->DiscountPercent }}%</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Số lượng:</span>
                                <span class="fw-bold">{{ $voucher->Quantity }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Đã sử dụng:</span>
                                <span class="fw-bold">{{ $voucher->orders()->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Còn lại:</span>
                                <span class="fw-bold">{{ $voucher->Quantity - $voucher->orders()->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Ngày hết hạn:</span>
                                <span class="fw-bold">{{ $voucher->ExpiredAt->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Trạng thái:</span>
                                <span class="fw-bold">{{ $voucher->IsActive ? 'Kích hoạt' : 'Vô hiệu hóa' }}</span>
                            </div>
                            @if($voucher->MinOrderAmount > 0)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Đơn tối thiểu:</span>
                                <span class="fw-bold">{{ number_format($voucher->MinOrderAmount, 0, ',', '.') }}₫</span>
                            </div>
                            @endif
                            @if($voucher->MaxDiscountAmount > 0)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Giảm tối đa:</span>
                                <span class="fw-bold">{{ number_format($voucher->MaxDiscountAmount, 0, ',', '.') }}₫</span>
                            </div>
                            @endif
                            @if($voucher->Description)
                            <div class="py-2">
                                <span class="text-muted d-block mb-1">Mô tả:</span>
                                <p class="mb-0">{{ $voucher->Description }}</p>
                            </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('admin.vouchers.edit', $voucher->VoucherID) }}" class="btn btn-primary flex-grow-1">
                                <span class="material-symbols-outlined me-1">edit</span>
                                Chỉnh sửa
                            </a>
                            <form action="{{ route('admin.vouchers.toggle', $voucher->VoucherID) }}" method="POST" class="flex-grow-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn {{ $voucher->IsActive ? 'btn-warning' : 'btn-success' }} w-100">
                                    <span class="material-symbols-outlined me-1">
                                        {{ $voucher->IsActive ? 'pause_circle' : 'play_circle' }}
                                    </span>
                                    {{ $voucher->IsActive ? 'Vô hiệu hóa' : 'Kích hoạt' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage History -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-outlined text-primary me-2">history</span>
                            <h5 class="mb-0 fw-semibold">Lịch sử sử dụng</h5>
                            <span class="badge bg-secondary ms-2">{{ $voucher->orders()->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($voucher->orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-4 py-3">Đơn hàng</th>
                                            <th class="px-4 py-3">Khách hàng</th>
                                            <th class="px-4 py-3">Ngày đặt</th>
                                            <th class="px-4 py-3 text-end">Tổng tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($voucher->orders as $order)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('admin.orders.show', $order->OrderID) }}" class="text-primary fw-medium">
                                                        #{{ $order->OrderID }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="material-symbols-outlined me-2 text-muted">person</span>
                                                        {{ $order->user->FullName ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="material-symbols-outlined me-2 text-muted">schedule</span>
                                                        {{ $order->CreatedAt->format('d/m/Y H:i') }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-end fw-medium">
                                                    {{ number_format($order->TotalAmount, 0, ',', '.') }}₫
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <span class="material-symbols-outlined text-muted" style="font-size: 64px;">receipt_long</span>
                                <h5 class="mt-3 text-muted">Chưa có lịch sử sử dụng</h5>
                                <p class="text-muted">Mã giảm giá này chưa được sử dụng bởi khách hàng nào.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush

