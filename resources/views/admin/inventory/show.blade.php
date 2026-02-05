@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $product->ProductName }}</h1>
                    <p class="text-sm text-gray-500">Mã: {{ $product->ProductCode }}</p>
                </div>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateStockModal">
                <span class="material-symbols-outlined me-1 align-middle">edit</span>
                Cập nhật kho
            </button>
        </div>

        <div class="row g-4">
            <!-- Product Info -->
            <div class="col-lg-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        Thông tin sản phẩm
                    </h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Mã sản phẩm</span>
                            <span class="fw-bold">{{ $product->ProductCode }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tên sản phẩm</span>
                            <span>{{ $product->ProductName }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Danh mục</span>
                            <span>{{ $product->category?->CategoryName ?? '--' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Giá bán</span>
                            <span class="fw-bold text-success">{{ number_format($product->Price, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Trọng lượng</span>
                            <span>{{ $product->Weight ? $product->Weight . ' kg' : '--' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Kích thước</span>
                            <span>{{ $product->Size ?? '--' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Đã bán</span>
                            <span class="fw-bold">{{ $product->PurchaseCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Tồn kho</span>
                            <span class="fw-bold fs-4 {{ $product->Stock <= 10 ? 'text-danger' : 'text-success' }}">{{ $product->Stock }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Trạng thái</span>
                            @if($product->Stock == 0)
                                <span class="badge bg-danger">Hết hàng</span>
                            @elseif($product->Stock <= 10)
                                <span class="badge bg-warning">Sắp hết</span>
                            @else
                                <span class="badge bg-success">Còn hàng</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Info -->
            <div class="col-lg-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-success">inventory</span>
                        Thông tin kho
                    </h5>
                    <div class="alert {{ $product->Stock <= 10 ? 'alert-warning' : 'alert-success' }} d-flex align-items-start gap-3">
                        <span class="material-symbols-outlined" style="font-size: 32px;">
                            {{ $product->Stock <= 10 ? 'warning' : 'check_circle' }}
                        </span>
                        <div>
                            <h6 class="mb-1">Số lượng tồn kho: {{ $product->Stock }}</h6>
                            @if($product->Stock <= 10)
                                <small class="mb-0">Sản phẩm đang ở mức thấp. Cần nhập thêm hàng!</small>
                            @else
                                <small class="mb-0">Sản phẩm còn nhiều trong kho.</small>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 mt-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Giá trị tồn kho</span>
                            <span class="fw-bold">{{ number_format($product->Stock * $product->Price, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tỷ lệ bán</span>
                            <span>{{ $product->PurchaseCount > 0 ? round(($product->PurchaseCount / ($product->Stock + $product->PurchaseCount)) * 100, 1) : 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="col-lg-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">description</span>
                        Mô tả
                    </h5>
                    <p class="text-muted mb-0">{{ $product->Description ?? 'Không có mô tả' }}</p>
                </div>
            </div>
        </div>

        <!-- Stock History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-info">history</span>
                    Lịch sử bán hàng gần đây
                </h5>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Ngày đặt</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Khách hàng</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Số lượng</th>
                        <th class="px-4 py-3 text-right text-gray-600 fw-semibold">Đơn giá</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Trạng thái đơn</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($stockHistory as $history)
                        <tr>
                            <td class="px-4 py-3">
                                {{ $history->order_date ? date('d/m/Y H:i', strtotime($history->order_date)) : '--' }}
                            </td>
                            <td class="px-4 py-3">{{ $history->customer_name ?? 'Khách' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-danger">-{{ $history->Quantity }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">{{ number_format($history->UnitPrice, 0, ',', '.') }} đ</td>
                            <td class="px-4 py-3">
                                @switch($history->order_status)
                                    @case('completed')
                                        <span class="badge bg-success">Hoàn thành</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-success">Đã giao</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $history->order_status }}</span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Chưa có lịch sử bán hàng
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Update Stock Modal -->
    <div class="modal fade" id="updateStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header bg-success text-white border-0" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined">inventory</span>
                        Cập nhật tồn kho
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.inventory.update-stock', $product->ProductID) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-gray-700">Sản phẩm</label>
                            <input type="text" class="form-control" value="{{ $product->ProductName }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-gray-700">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" name="Stock" class="form-control form-control-lg text-center" value="{{ $product->Stock }}" min="0" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-success">
                                <span class="material-symbols-outlined me-1 align-middle">save</span>
                                Lưu
                            </button>
                        </div>
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








