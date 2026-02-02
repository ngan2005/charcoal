@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý sản phẩm</h1>
                <p class="text-sm text-gray-500">Theo dõi và cập nhật danh sách sản phẩm</p>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createProductModal">
                Thêm sản phẩm
            </button>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form class="flex flex-col gap-3 md:flex-row md:items-end" method="GET" action="{{ route('admin.products.index') }}">
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-700">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] }}"
                        class="mt-1 w-full rounded-lg border-gray-200 focus:border-primary focus:ring-primary"
                        placeholder="Tên hoặc mã sản phẩm">
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-primary">Lọc dữ liệu</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Sản phẩm</th>
                        <th class="px-4 py-3 text-left">Ảnh</th>
                        <th class="px-4 py-3 text-left">Danh mục</th>
                        <th class="px-4 py-3 text-left">Giá</th>
                        <th class="px-4 py-3 text-left">Tồn kho</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $product->ProductName }}</div>
                                <div class="text-xs text-gray-500">Mã: {{ $product->ProductCode ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @php($images = $product->images ?? collect())
                                <div class="flex items-center gap-2">
                                    @foreach ($images->take(3) as $image)
                                        <img
                                            src="{{ $image->ImageUrl }}"
                                            alt="{{ $product->ProductName }}"
                                            class="h-10 w-10 rounded border border-gray-200 object-cover cursor-pointer hover:scale-110 transition-transform img-preview-trigger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#imagePreviewModal"
                                            data-src="{{ $image->ImageUrl }}">
                                    @endforeach
                                    @if ($images->count() > 3)
                                        <span class="text-xs text-gray-500">+{{ $images->count() - 3 }}</span>
                                    @endif
                                    @if ($images->isEmpty())
                                        <span class="text-xs text-gray-400">Chưa có</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $product->category?->CategoryName ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ number_format((float) ($product->Price ?? 0), 0, ',', '.') }} đ</td>
                            <td class="px-4 py-3 text-gray-600">{{ $product->Stock ?? 0 }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $product->status?->StatusName ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex gap-2">
                                    <button
                                        class="btn btn-outline-secondary btn-sm edit-product-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editProductModal"
                                        data-action="{{ route('admin.products.update', $product->ProductID) }}"
                                        data-name="{{ $product->ProductName }}"
                                        data-code="{{ $product->ProductCode }}"
                                        data-category="{{ $product->CategoryID }}"
                                        data-price="{{ $product->Price }}"
                                        data-weight="{{ $product->Weight }}"
                                        data-size="{{ $product->Size }}"
                                        data-stock="{{ $product->Stock }}"
                                        data-status="{{ $product->StatusID }}"
                                        data-description="{{ $product->Description }}">
                                        Sửa
                                    </button>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product->ProductID) }}" data-confirm class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Không có sản phẩm nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $products->links() }}
        </div>
    </div>

    <div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm sản phẩm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên sản phẩm</label>
                                <input type="text" name="ProductName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mã sản phẩm</label>
                                <input type="text" name="ProductCode" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Danh mục</label>
                                <select name="CategoryID" class="form-select" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="StatusID" class="form-select" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->StatusID }}">{{ $status->StatusName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Giá</label>
                                <input type="number" name="Price" class="form-control" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cân nặng (kg)</label>
                                <input type="number" step="0.01" name="Weight" class="form-control" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kích thước</label>
                                <input type="text" name="Size" class="form-control" placeholder="Ví dụ: S / M / L">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tồn kho</label>
                                <input type="number" name="Stock" class="form-control" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ảnh chính</label>
                                <input type="file" name="MainImage" class="form-control" accept="image/*" required>
                                <p class="text-xs text-gray-500 mt-1">Chọn 1 ảnh đại diện chính.</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ảnh phụ</label>
                                <input type="file" name="GalleryImages[]" class="form-control" accept="image/*" multiple>
                                <p class="text-xs text-gray-500 mt-1">Có thể chọn nhiều ảnh.</p>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mô tả</label>
                                <textarea name="Description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold">Xác nhận thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form id="edit-product-form" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật thông tin sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên sản phẩm</label>
                                <input type="text" name="ProductName" id="edit-product-name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mã sản phẩm</label>
                                <input type="text" name="ProductCode" id="edit-product-code" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Danh mục</label>
                                <select name="CategoryID" id="edit-product-category" class="form-select" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="StatusID" id="edit-product-status" class="form-select" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->StatusID }}">{{ $status->StatusName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Giá</label>
                                <input type="number" name="Price" id="edit-product-price" class="form-control" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cân nặng (kg)</label>
                                <input type="number" step="0.01" name="Weight" id="edit-product-weight" class="form-control" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kích thước</label>
                                <input type="text" name="Size" id="edit-product-size" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tồn kho</label>
                                <input type="number" name="Stock" id="edit-product-stock" class="form-control" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ảnh chính (nếu muốn đổi)</label>
                                <input type="file" name="MainImage" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ảnh phụ (thêm mới)</label>
                                <input type="file" name="GalleryImages[]" class="form-control" accept="image/*" multiple>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mô tả</label>
                                <textarea name="Description" id="edit-product-description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Xác nhận cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-header border-0 p-0 mb-2">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img src="" id="preview-image-element" class="img-fluid rounded shadow-lg mx-auto" style="max-height: 85vh;">
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
            const confirmWithSwal = (title, text) => {
                return Swal.fire({
                    title,
                    text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy',
                    confirmButtonColor: '#dc2626',
                });
            };

            document.querySelectorAll('form[data-confirm]').forEach(form => {
                form.addEventListener('submit', async event => {
                    event.preventDefault();
                    const result = await confirmWithSwal('Xóa sản phẩm?', 'Thao tác này không thể hoàn tác.');
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            document.querySelectorAll('.edit-product-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const form = document.getElementById('edit-product-form');
                    form.action = button.dataset.action;
                    document.querySelector('#editProductModal [name="ProductName"]').value = button.dataset.name || '';
                    document.querySelector('#editProductModal [name="ProductCode"]').value = button.dataset.code || '';
                    document.querySelector('#editProductModal [name="CategoryID"]').value = button.dataset.category || '';
                    document.querySelector('#editProductModal [name="StatusID"]').value = button.dataset.status || '';
                    document.querySelector('#editProductModal [name="Price"]').value = button.dataset.price || '';
                    document.querySelector('#editProductModal [name="Weight"]').value = button.dataset.weight || '';
                    document.querySelector('#editProductModal [name="Size"]').value = button.dataset.size || '';
                    document.querySelector('#editProductModal [name="Stock"]').value = button.dataset.stock || '';
                    document.querySelector('#editProductModal [name="Description"]').value = button.dataset.description || '';
                });
            });

            // Image Preview Modal Logic
            document.querySelectorAll('.img-preview-trigger').forEach(img => {
                img.addEventListener('click', function() {
                    const fullSrc = this.getAttribute('data-src');
                    document.getElementById('preview-image-element').src = fullSrc;
                });
            });
        });
    </script>
@endpush
