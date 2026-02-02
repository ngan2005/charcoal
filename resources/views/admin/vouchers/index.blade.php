@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold text-gray-900">Quản lý mã giảm giá</h1>
                <p class="text-muted small mb-0">Tạo và quản lý các mã khuyến mãi cho khách hàng</p>
            </div>
            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">add</span>
                Thêm mã giảm giá
            </a>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <span class="material-symbols-outlined me-2">check_circle</span>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <span class="material-symbols-outlined me-2">error</span>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.vouchers.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="form-control" placeholder="Nhập mã giảm giá...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Đã hết hạn</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 me-2">
                            <span class="material-symbols-outlined me-1" style="font-size: 18px;">search</span>
                            Lọc dữ liệu
                        </button>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary w-100">
                            <span class="material-symbols-outlined me-1" style="font-size: 18px;">clear</span>
                            Xóa lọc
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vouchers Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex align-items-center">
                    <span class="material-symbols-outlined text-primary me-2">local_offer</span>
                    <h5 class="mb-0 fw-semibold">Danh sách mã giảm giá</h5>
                    <span class="badge bg-secondary ms-2">{{ $vouchers->total() }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($vouchers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3 fw-semibold text-nowrap">Mã giảm giá</th>
                                    <th class="px-4 py-3 fw-semibold text-nowrap">Phần trăm giảm</th>
                                    <th class="px-4 py-3 fw-semibold text-nowrap">Số lượng</th>
                                    <th class="px-4 py-3 fw-semibold text-nowrap">Ngày hết hạn</th>
                                    <th class="px-4 py-3 fw-semibold text-nowrap">Trạng thái</th>
                                    <th class="px-4 py-3 fw-semibold text-nowrap text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($vouchers as $voucher)
                                    @php
                                        $isValid = $voucher->isValid();
                                        $isExpired = $voucher->ExpiredAt->isPast();
                                        $isOutOfStock = $voucher->Quantity <= 0;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-warning bg-opacity-10 rounded px-2 py-1 me-2">
                                                    <code class="fw-bold text-warning-emphasis">{{ $voucher->Code }}</code>
                                                </div>
                                            </div>
                                            @if ($voucher->Description)
                                                <p class="text-muted small mb-0 mt-1 text-truncate" style="max-width: 200px;">
                                                    {{ $voucher->Description }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-success fs-6">
                                                <span class="material-symbols-outlined me-1" style="font-size: 16px;">percent</span>
                                                {{ $voucher->DiscountPercent }}%
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined me-1 text-muted" style="font-size: 18px;">inventory_2</span>
                                                {{ $voucher->Quantity - $voucher->orders()->count() }} / {{ $voucher->Quantity }}
                                            </div>
                                            <div class="progress mt-1" style="height: 4px; width: 80px;">
                                                @php
                                                    $usedPercent = min(100, ($voucher->orders()->count() / $voucher->Quantity) * 100);
                                                @endphp
                                                <div class="progress-bar {{ $usedPercent >= 90 ? 'bg-danger' : 'bg-primary' }}" 
                                                     style="width: {{ 100 - $usedPercent }}%"></div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined me-1 text-muted" style="font-size: 18px;">schedule</span>
                                                {{ $voucher->ExpiredAt->format('d/m/Y H:i') }}
                                            </div>
                                            @if ($isExpired)
                                                <span class="text-danger small">
                                                    <span class="material-symbols-outlined" style="font-size: 14px;">error</span>
                                                    Đã hết hạn
                                                </span>
                                            @else
                                                <span class="text-success small">
                                                    Còn {{ $voucher->ExpiredAt->diffForHumans() }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($isValid)
                                                <span class="badge bg-success">
                                                    <span class="material-symbols-outlined me-1" style="font-size: 14px;">check_circle</span>
                                                    Hoạt động
                                                </span>
                                            @elseif($isExpired)
                                                <span class="badge bg-danger">
                                                    <span class="material-symbols-outlined me-1" style="font-size: 14px;">cancel</span>
                                                    Hết hạn
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <span class="material-symbols-outlined me-1" style="font-size: 14px;">block</span>
                                                    Hết lượt
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.vouchers.edit', $voucher->VoucherID) }}" 
                                                   class="btn btn-outline-primary" title="Chỉnh sửa">
                                                    <span class="material-symbols-outlined">edit</span>
                                                </a>
                                                <a href="{{ route('admin.vouchers.show', $voucher->VoucherID) }}" 
                                                   class="btn btn-outline-info" title="Xem chi tiết">
                                                    <span class="material-symbols-outlined">visibility</span>
                                                </a>
                                                <form action="{{ route('admin.vouchers.toggle', $voucher->VoucherID) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn {{ $voucher->IsActive ? 'outline-warning' : 'outline-success' }}" 
                                                            title="{{ $voucher->IsActive ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                                        <span class="material-symbols-outlined">
                                                            {{ $voucher->IsActive ? 'pause_circle' : 'play_circle' }}
                                                        </span>
                                                    </button>
                                                </form>
                                                <button type="button" 
                                                        class="btn btn-outline-danger delete-btn"
                                                        data-id="{{ $voucher->VoucherID }}"
                                                        data-code="{{ $voucher->Code }}"
                                                        title="Xóa">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <span class="material-symbols-outlined text-muted" style="font-size: 64px;">local_offer</span>
                        <h5 class="mt-3 text-muted">Chưa có mã giảm giá nào</h5>
                        <p class="text-muted">Hãy tạo mã giảm giá đầu tiên cho cửa hàng của bạn</p>
                        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success">
                            <span class="material-symbols-outlined me-1">add</span>
                            Tạo mã giảm giá
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pagination -->
        @if ($vouchers->hasPages())
            <div class="d-flex justify-content-center">
                {{ $vouchers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <span class="material-symbols-outlined me-2">warning</span>
                        Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa mã giảm giá <strong id="deleteCode" class="text-danger"></strong>?</p>
                    <p class="text-muted small mb-0">Thao tác này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <span class="material-symbols-outlined me-1">delete</span>
                            Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation with SweetAlert
            const deleteBtns = document.querySelectorAll('.delete-btn');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteForm = document.getElementById('deleteForm');
            const deleteCode = document.getElementById('deleteCode');

            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const code = this.dataset.code;
                    deleteCode.textContent = code;
                    deleteForm.action = `/admin/vouchers/${id}`;
                    deleteModal.show();
                });
            });

            // Toggle status confirmation
            document.querySelectorAll('form[action*="toggle"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Xác nhận',
                        text: 'Bạn có muốn thay đổi trạng thái của mã giảm giá này?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0d6efd',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush

