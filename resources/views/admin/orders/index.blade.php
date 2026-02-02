@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        .status-badge { font-size: 0.75rem; padding: 0.35em 0.65em; }
        .table th { font-size: 0.813rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table td { font-size: 0.875rem; vertical-align: middle; }
        .hover-row:hover { background-color: #f8f9fa !important; }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý đơn hàng</h1>
                <p class="text-sm text-gray-500">Theo dõi và xử lý đơn hàng của khách</p>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Mã đơn, tên khách..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($orderStatuses as $key => $status)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Từ ngày</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Đến ngày</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <span class="material-symbols-outlined me-1 align-middle" style="font-size: 18px;">search</span>
                        Tìm kiếm
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                        <span class="material-symbols-outlined" style="font-size: 18px;">filter_alt_off</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Mã đơn</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Khách hàng</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Ngày đặt</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Tổng tiền</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Thanh toán</th>
                        <th class="px-4 py-3 text-right text-gray-600 fw-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover-row">
                            <td class="px-4 py-3">
                                <span class="fw-bold text-primary">#{{ $order->OrderID }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <span class="material-symbols-outlined text-primary" style="font-size: 20px;">person</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $order->user?->FullName ?? 'Khách' }}</div>
                                        <small class="text-muted">{{ $order->user?->Email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary" style="font-size: 18px;">calendar_today</span>
                                    <span>{{ $order->CreatedAt->format('d/m/Y H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="fw-bold text-success">{{ number_format($order->TotalAmount, 0, ',', '.') }} đ</span>
                            </td>
                            <td class="px-4 py-3">
                                @switch($order->Status)
                                    @case('pending')
                                        <span class="badge bg-warning status-badge">Chờ xác nhận</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-info status-badge">Đã xác nhận</span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-primary status-badge">Đang xử lý</span>
                                        @break
                                    @case('shipping')
                                        <span class="badge bg-secondary status-badge">Đang giao</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-success status-badge">Đã giao</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success status-badge">Hoàn thành</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger status-badge">Đã hủy</span>
                                        @break
                                    @case('refunded')
                                        <span class="badge bg-dark status-badge">Hoàn tiền</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary status-badge">{{ $order->Status }}</span>
                                @endswitch
                            </td>
                            <td class="px-4 py-3">
                                @if($order->payment)
                                    @switch($order->payment->StatusID)
                                        @case(1)
                                            <span class="badge bg-warning status-badge">Chưa thanh toán</span>
                                            @break
                                        @case(2)
                                            <span class="badge bg-info status-badge">Đang xử lý</span>
                                            @break
                                        @case(3)
                                            <span class="badge bg-success status-badge">Đã thanh toán</span>
                                            @break
                                        @case(4)
                                            <span class="badge bg-danger status-badge">Thất bại</span>
                                            @break
                                        @case(5)
                                            <span class="badge bg-dark status-badge">Đã hoàn tiền</span>
                                            @break
                                        @case(6)
                                            <span class="badge bg-secondary status-badge">Bị hủy</span>
                                            @break
                                        @case(7)
                                            <span class="badge bg-secondary status-badge">Hết hạn</span>
                                            @break
                                    @endswitch
                                @else
                                    <span class="badge bg-secondary status-badge">Chưa có</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <a href="{{ route('admin.orders.show', $order->OrderID) }}" class="btn btn-outline-primary btn-sm">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="material-symbols-outlined mb-2" style="font-size: 48px; opacity: 0.5;">shopping_cart</span>
                                    <p class="mb-0">Chưa có đơn hàng nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const successMessage = @json(session('success'));
            if (successMessage) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: successMessage,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        });
    </script>
@endpush





