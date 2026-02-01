@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        .stock-badge { font-size: 0.75rem; padding: 0.35em 0.65em; }
        .stock-low { background-color: #ffc107; color: #000; }
        .stock-out { background-color: #dc3545; color: #fff; }
        .stock-in { background-color: #198754; color: #fff; }
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
                <h1 class="text-2xl font-bold text-gray-900">Quản lý kho</h1>
                <p class="text-sm text-gray-500">Theo dõi tồn kho sản phẩm</p>
            </div>
            <a href="{{ route('admin.inventory.statistics') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">analytics</span>
                Thống kê
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row g-4">
            <div class="col-md-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-primary" style="font-size: 24px;">inventory_2</span>
                        </div>
                        <div>
                            <div class="text-muted small">Tổng sản phẩm</div>
                            <div class="fw-bold fs-4">{{ number_format($stats['total_products']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-success" style="font-size: 24px;">inventory</span>
                        </div>
                        <div>
                            <div class="text-muted small">Tổng tồn kho</div>
                            <div class="fw-bold fs-4">{{ number_format($stats['total_stock']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-warning" style="font-size: 24px;">warning</span>
                        </div>
                        <div>
                            <div class="text-muted small">Sắp hết hàng</div>
                            <div class="fw-bold fs-4 text-warning">{{ number_format($stats['low_stock']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-danger" style="font-size: 24px;">remove_shopping_cart</span>
                        </div>
                        <div>
                            <div class="text-muted small">Hết hàng</div>
                            <div class="fw-bold fs-4 text-danger">{{ number_format($stats['out_of_stock']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small mb-1">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Mã SP, tên sản phẩm..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->CategoryID }}" @selected((int)request('category_id') === $category->CategoryID)>
                                {{ $category->CategoryName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Tồn kho</label>
                    <select name="stock_status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="in" @selected(request('stock_status') === 'in')>Còn nhiều (>10)</option>
                        <option value="low" @selected(request('stock_status') === 'low')>Sắp hết (1-10)</option>
                        <option value="out" @selected(request('stock_status') === 'out')>Hết hàng (0)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <span class="material-symbols-outlined me-1 align-middle" style="font-size: 18px;">search</span>
                    </button>
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
                        <span class="material-symbols-outlined" style="font-size: 18px;">filter_alt_off</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Inventory Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Mã SP</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Sản phẩm</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Danh mục</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Giá</th>
                        <th class="px-4 py-3 text-center text-gray-600 fw-semibold">Tồn kho</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Trạng thái</th>
                        <th class="px-4 py-3 text-right text-gray-600 fw-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover-row">
                            <td class="px-4 py-3">
                                <span class="fw-medium text-primary">{{ $product->ProductCode }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="material-symbols-outlined text-secondary">inventory_2</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $product->ProductName }}</div>
                                        <small class="text-muted">Đã bán: {{ $product->PurchaseCount }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-light text-dark">{{ $product->category?->CategoryName ?? '--' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="fw-medium">{{ number_format($product->Price, 0, ',', '.') }} đ</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="fw-bold fs-5 {{ $product->Stock <= 10 ? 'text-warning' : ($product->Stock == 0 ? 'text-danger' : 'text-success') }}">
                                    {{ $product->Stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($product->Stock == 0)
                                    <span class="badge stock-badge stock-out">Hết hàng</span>
                                @elseif($product->Stock <= 10)
                                    <span class="badge stock-badge stock-low">Sắp hết</span>
                                @else
                                    <span class="badge stock-badge stock-in">Còn hàng</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.inventory.show', $product->ProductID) }}" class="btn btn-outline-primary btn-sm" title="Chi tiết">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                    </a>
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateStockModal{{ $product->ProductID }}" title="Cập nhật kho">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Update Stock Modal -->
                        <div class="modal fade" id="updateStockModal{{ $product->ProductID }}" tabindex="-1" aria-hidden="true">
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
                                        <div class="text-center mb-4">
                                            <div class="fw-bold fs-5">{{ $product->ProductName }}</div>
                                            <small class="text-muted">{{ $product->ProductCode }}</small>
                                        </div>
                                        <form action="{{ route('admin.inventory.update-stock', $product->ProductID) }}" method="POST">
                                            @csrf
                                            @method('PUT')
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
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="material-symbols-outlined mb-2" style="font-size: 48px; opacity: 0.5;">inventory_2</span>
                                    <p class="mb-0">Không tìm thấy sản phẩm nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $products->appends(request()->query())->links() }}
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

