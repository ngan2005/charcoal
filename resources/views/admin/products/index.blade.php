@extends('layouts.admin')

@php
    use Milon\Barcode\DNS1D;
@endphp

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/sortable.min.css">
    <style>
        .barcode-container svg {
            max-width: 150px;
            height: auto;
            margin: 0 auto;
        }
    </style>
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
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
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
                        <th class="px-4 py-3 text-left">Mã vạch</th>
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
                                <div class="flex items-center gap-1">
                                    @foreach ($images->take(4) as $index => $image)
                                        <img
                                            src="{{ $image->ImageUrl }}"
                                            alt="{{ $product->ProductName }}"
                                            class="h-10 w-10 rounded border border-gray-200 object-cover cursor-pointer hover:scale-110 transition-transform img-preview-trigger {{ $index === 0 ? 'ring-2 ring-primary' : '' }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#imagePreviewModal"
                                            data-src="{{ $image->ImageUrl }}">
                                    @endforeach
                                    @if ($images->count() > 4)
                                        <span class="text-xs text-gray-500 ml-1">+{{ $images->count() - 4 }}</span>
                                    @endif
                                    @if ($images->isEmpty())
                                        <span class="text-xs text-gray-400">Chưa có ảnh</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($product->ProductCode)
                                    <div class="barcode-container">
                                        <svg class="barcode-display" data-code="{{ $product->ProductCode }}"></svg>
                                        <div class="text-[10px] text-center mt-1">{{ $product->ProductCode }}</div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">N/A</span>
                                @endif
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
                                        data-id="{{ $product->ProductID }}"
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
                                    <button 
                                        class="btn btn-outline-primary btn-sm print-barcode-btn"
                                        onclick="printBarcode('{{ $product->ProductCode }}')">
                                        In
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
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
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

    <!-- Create Product Modal -->
    <div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="create-product-form" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm sản phẩm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Thông tin cơ bản -->
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">info</span>
                                Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="ProductName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mã sản phẩm</label>
                            <input type="text" name="ProductCode" class="form-control" placeholder="Tự động nếu để trống">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="CategoryID" class="form-select" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select name="StatusID" class="form-select" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->StatusID }}">{{ $status->StatusName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Giá & Kho -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">inventory_2</span>
                                Giá & Kho hàng
                            </h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Giá (VNĐ)</label>
                            <input type="number" name="Price" class="form-control" min="0" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cân nặng (kg)</label>
                            <input type="number" step="0.01" name="Weight" class="form-control" min="0" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kích thước</label>
                            <input type="text" name="Size" class="form-control" placeholder="S / M / L">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tồn kho</label>
                            <input type="number" name="Stock" class="form-control" min="0" placeholder="0">
                        </div>

                        <!-- Hình ảnh -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">image</span>
                                Hình ảnh sản phẩm
                            </h6>
                        </div>
                        <div class="col-12">
                            <!-- Dropzone -->
                            <div class="dropzone-container" id="create-dropzone">
                                <div class="dropzone-area">
                                    <div class="dropzone-content">
                                        <span class="material-symbols-outlined dropzone-icon">cloud_upload</span>
                                        <p class="dropzone-text mb-2">Kéo thả ảnh vào đây hoặc</p>
                                        <label for="create-image-input" class="btn btn-outline-primary btn-sm">Chọn ảnh</label>
                                        <input type="file" id="create-image-input" name="images[]" class="d-none" accept="image/*" multiple>
                                        <p class="text-xs text-muted mt-2 mb-0">Hỗ trợ JPG, PNG, WEBP. Tối đa 5MB/ảnh</p>
                                    </div>
                                </div>
                                <div class="gallery-container mt-3">
                                    <p class="text-sm text-muted mb-2">Thứ tự ảnh đầu tiên sẽ là ảnh đại diện (⭐)</p>
                                    <div class="gallery-grid" id="create-gallery">
                                        <!-- Images will be added here -->
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="MainImageIndex" id="create-main-image-index" value="0">
                        </div>

                        <!-- Mô tả -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">description</span>
                                Mô tả
                            </h6>
                        </div>
                        <div class="col-12">
                            <textarea name="Description" class="form-control" rows="3" placeholder="Mô tả sản phẩm..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold">Thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <form id="edit-product-form" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ProductID" id="edit-product-id">
                    <div class="row g-4">
                        <!-- Thông tin cơ bản -->
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">info</span>
                                Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="ProductName" id="edit-product-name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mã sản phẩm</label>
                            <input type="text" name="ProductCode" id="edit-product-code" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="CategoryID" id="edit-product-category" class="form-select" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select name="StatusID" id="edit-product-status" class="form-select" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->StatusID }}">{{ $status->StatusName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Giá & Kho -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">inventory_2</span>
                                Giá & Kho hàng
                            </h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Giá (VNĐ)</label>
                            <input type="number" name="Price" id="edit-product-price" class="form-control" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cân nặng (kg)</label>
                            <input type="number" step="0.01" name="Weight" id="edit-product-weight" class="form-control" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kích thước</label>
                            <input type="text" name="Size" id="edit-product-size" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tồn kho</label>
                            <input type="number" name="Stock" id="edit-product-stock" class="form-control" min="0">
                        </div>

                        <!-- Hình ảnh -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">image</span>
                                Hình ảnh sản phẩm
                            </h6>
                        </div>
                        <div class="col-12">
                            <!-- Existing Images -->
                            <div class="mb-4">
                                <p class="text-sm text-muted mb-2">Ảnh hiện tại (ảnh đầu tiên là ảnh đại diện)</p>
                                <div class="existing-images-grid" id="edit-existing-images">
                                    @php($images = isset($editProduct) ? ($editProduct->images ?? collect()) : collect())
                                    @if($images->count() > 0)
                                        @foreach($images as $index => $image)
                                            <div class="image-item {{ $index === 0 ? 'main-image' : '' }}" data-image-id="{{ $image->ImageID }}">
                                                <img src="{{ $image->ImageUrl }}" alt="Ảnh {{ $index + 1 }}">
                                                <div class="image-actions">
                                                    <button type="button" class="btn-star" title="Đặt làm ảnh chính" onclick="setAsMain(this)">
                                                        <span class="material-symbols-outlined">{{ $index === 0 ? 'star' : 'star_border' }}</span>
                                                    </button>
                                                    <button type="button" class="btn-delete" title="Xóa ảnh" onclick="deleteExistingImage(this)">
                                                        <span class="material-symbols-outlined">close</span>
                                                    </button>
                                                </div>
                                                @if($index === 0)
                                                    <div class="main-badge">Ảnh chính</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted text-center py-4 w-100">Chưa có ảnh nào</p>
                                    @endif
                                </div>
                                <input type="hidden" name="DeleteImageIDs" id="delete-image-ids" value="">
                                <input type="hidden" name="MainImageID" id="main-image-id" value="">
                            </div>

                            <!-- Dropzone for new images -->
                            <div class="dropzone-container" id="edit-dropzone">
                                <div class="dropzone-area">
                                    <div class="dropzone-content">
                                        <span class="material-symbols-outlined dropzone-icon">cloud_upload</span>
                                        <p class="dropzone-text mb-2">Kéo thả ảnh mới vào đây hoặc</p>
                                        <label for="edit-image-input" class="btn btn-outline-primary btn-sm">Chọn ảnh</label>
                                        <input type="file" id="edit-image-input" name="new_images[]" class="d-none" accept="image/*" multiple>
                                        <p class="text-xs text-muted mt-2 mb-0">Hỗ trợ JPG, PNG, WEBP. Tối đa 5MB/ảnh</p>
                                    </div>
                                </div>
                                <div class="gallery-container mt-3">
                                    <p class="text-sm text-muted mb-2">Ảnh mới thêm (kéo thả để sắp xếp)</p>
                                    <div class="gallery-grid" id="edit-gallery">
                                        <!-- New images will be added here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả -->
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">description</span>
                                Mô tả
                            </h6>
                        </div>
                        <div class="col-12">
                            <textarea name="Description" id="edit-product-description" class="form-control" rows="3" placeholder="Mô tả sản phẩm..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Cập nhật</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        // Image preview on click
        document.querySelectorAll('.img-preview-trigger').forEach(img => {
            img.addEventListener('click', function() {
                const fullSrc = this.getAttribute('data-src');
                document.getElementById('preview-image-element').src = fullSrc;
            });
        });

        // Gallery Image Handler Class
        class GalleryImageHandler {
            constructor(containerId, inputId, mainIndexId) {
                this.container = document.getElementById(containerId);
                this.input = document.getElementById(inputId);
                this.mainIndexInput = document.getElementById(mainIndexId);
                this.files = [];
                
                if (this.container) {
                    new Sortable(this.container, {
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        onEnd: () => {
                            this.reorderFiles();
                            this.updateMainIndex();
                        }
                    });
                }

                if (this.input) {
                    this.input.addEventListener('change', (e) => this.handleFiles(e.target.files));
                }
            }

            handleFiles(fileList) {
                const newFiles = Array.from(fileList);
                let loadedCount = 0;

                newFiles.forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.files.push({
                            file: file,
                            data: e.target.result
                        });
                        loadedCount++;
                        if (loadedCount === newFiles.length) {
                            this.render();
                            this.syncInput();
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }

            reorderFiles() {
                const newOrder = [];
                this.container.querySelectorAll('.image-item').forEach(el => {
                    const index = parseInt(el.dataset.index);
                    newOrder.push(this.files[index]);
                });
                this.files = newOrder;
                this.render();
                this.syncInput();
            }

            removeFile(index) {
                this.files.splice(index, 1);
                this.render();
                this.syncInput();
                this.updateMainIndex();
            }

            setAsMain(index) {
                if (index === 0) return;
                const [target] = this.files.splice(index, 1);
                this.files.unshift(target);
                this.render();
                this.syncInput();
            }

            syncInput() {
                if (!this.input) return;
                const dt = new DataTransfer();
                this.files.forEach(f => {
                    if (f.file) dt.items.add(f.file);
                });
                this.input.files = dt.files;
            }

            updateMainIndex() {
                if (this.mainIndexInput) {
                    this.mainIndexInput.value = 0;
                }
            }

            formatSize(bytes) {
                if (!bytes) return '0 B';
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
            }

            render() {
                this.container.innerHTML = this.files.map((file, index) => `
                    <div class="image-item ${index === 0 ? 'main-image' : ''}" data-index="${index}">
                        <img src="${file.data}" alt="Ảnh ${index + 1}">
                        <div class="image-actions">
                            <button type="button" class="btn-star" title="${index === 0 ? 'Ảnh chính' : 'Đặt làm ảnh chính'}" onclick="galleryHandler_${this.container.id}.setAsMain(${index})">
                                <span class="material-symbols-outlined">${index === 0 ? 'star' : 'star_border'}</span>
                            </button>
                            <button type="button" class="btn-delete" title="Xóa ảnh" onclick="galleryHandler_${this.container.id}.removeFile(${index})">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        ${index === 0 ? '<div class="main-badge">Ảnh chính</div>' : ''}
                        <div class="image-info">${this.formatSize(file.file?.size)}</div>
                    </div>
                `).join('');
                
                this.updateMainIndex();
            }
        }

        // Initialize handlers
        let createGalleryHandler, editGalleryHandler;
        let existingImages = [];
        let deletedImageIds = [];
        let mainImageId = null;

        document.addEventListener('DOMContentLoaded', () => {
            // Generate all barcodes on page
            document.querySelectorAll('.barcode-display').forEach(svg => {
                const code = svg.dataset.code;
                if (code) {
                    try {
                        JsBarcode(svg, code, {
                            format: "CODE128",
                            width: 1.5,
                            height: 35,
                            displayValue: false,
                            margin: 5
                        });
                    } catch (e) {
                        console.warn('Cannot generate barcode for:', code);
                    }
                }
            });

            // Initialize create gallery
            createGalleryHandler = new GalleryImageHandler('create-gallery', 'create-image-input', 'create-main-image-index');
            window.galleryHandler_create_gallery = createGalleryHandler;

            // Initialize edit gallery
            editGalleryHandler = new GalleryImageHandler('edit-gallery', 'edit-image-input', null);
            window.galleryHandler_edit_gallery = editGalleryHandler;

            // Handle edit button click
            document.querySelectorAll('.edit-product-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const form = document.getElementById('edit-product-form');
                    form.action = button.dataset.action;
                    
                    document.getElementById('edit-product-id').value = button.dataset.id || '';
                    document.getElementById('edit-product-name').value = button.dataset.name || '';
                    document.getElementById('edit-product-code').value = button.dataset.code || '';
                    document.getElementById('edit-product-category').value = button.dataset.category || '';
                    document.getElementById('edit-product-status').value = button.dataset.status || '';
                    document.getElementById('edit-product-price').value = button.dataset.price || '';
                    document.getElementById('edit-product-weight').value = button.dataset.weight || '';
                    document.getElementById('edit-product-size').value = button.dataset.size || '';
                    document.getElementById('edit-product-stock').value = button.dataset.stock || '';
                    document.getElementById('edit-product-description').value = button.dataset.description || '';

                    // Load existing images via AJAX
                    const productId = button.dataset.id;
                    await loadExistingImages(productId);
                });
            });

            // Dropzone drag and drop
            document.querySelectorAll('.dropzone-area').forEach(area => {
                area.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    area.classList.add('dragover');
                });
                
                area.addEventListener('dragleave', () => {
                    area.classList.remove('dragover');
                });
                
                area.addEventListener('drop', (e) => {
                    e.preventDefault();
                    area.classList.remove('dragover');
                    
                    const files = e.dataTransfer.files;
                    const input = area.parentElement.querySelector('input[type="file"]');
                    if (input) {
                        const dataTransfer = new DataTransfer();
                        Array.from(files).forEach(f => dataTransfer.items.add(f));
                        input.files = dataTransfer.files;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            });
        });

        // Load existing images via AJAX
        async function loadExistingImages(productId) {
            try {
                const response = await fetch(`/admin/products/${productId}/images`);
                if (response.ok) {
                    const data = await response.json();
                    existingImages = data.images || [];
                    deletedImageIds = [];
                    // Set first image as main if not set
                    mainImageId = existingImages.length > 0 ? existingImages.find(img => img.IsMain)?.ImageID || existingImages[0]?.ImageID : null;
                    renderExistingImages();
                }
            } catch (error) {
                console.error('Error loading images:', error);
            }
        }

        // Render existing images
        function renderExistingImages() {
            const container = document.getElementById('edit-existing-images');
            if (!container) return;

            if (existingImages.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-4 w-100">Chưa có ảnh nào</p>';
                return;
            }

            container.innerHTML = existingImages.map((img, index) => `
                <div class="image-item ${img.IsMain || index === 0 ? 'main-image' : ''}" data-image-id="${img.ImageID}">
                    <img src="${img.ImageUrl}" alt="Ảnh ${index + 1}">
                    <div class="image-actions">
                        <button type="button" class="btn-star" title="Đặt làm ảnh chính" onclick="setExistingAsMain(${img.ImageID})">
                            <span class="material-symbols-outlined">${img.IsMain || index === 0 ? 'star' : 'star_border'}</span>
                        </button>
                        <button type="button" class="btn-delete" title="Xóa ảnh" onclick="deleteExistingImage(${img.ImageID})">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    ${img.IsMain || index === 0 ? '<div class="main-badge">Ảnh chính</div>' : ''}
                </div>
            `).join('');

            // Update hidden inputs
            updateHiddenInputs();
        }

        // Set existing image as main
        function setExistingAsMain(imageId) {
            existingImages.forEach(img => {
                img.IsMain = img.ImageID === imageId;
            });
            mainImageId = imageId;
            renderExistingImages();
        }

        // Delete existing image
        function deleteExistingImage(imageId) {
            Swal.fire({
                title: 'Xóa ảnh?',
                text: 'Bạn có chắc muốn xóa ảnh này không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#dc3545',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add to deleted IDs
                    if (!deletedImageIds.includes(imageId)) {
                        deletedImageIds.push(imageId);
                    }
                    // Remove from existing images
                    existingImages = existingImages.filter(img => img.ImageID !== imageId);
                    
                    // If deleted image was main, set new main
                    if (mainImageId === imageId && existingImages.length > 0) {
                        existingImages[0].IsMain = true;
                        mainImageId = existingImages[0].ImageID;
                    }
                    
                    renderExistingImages();
                }
            });
        }

        function updateHiddenInputs() {
            const deleteInput = document.getElementById('delete-image-ids');
            const mainInput = document.getElementById('main-image-id');
            if (deleteInput) deleteInput.value = deletedImageIds.join(',');
            if (mainInput) mainInput.value = mainImageId || '';
        }

        // Make functions globally accessible
        window.setExistingAsMain = setExistingAsMain;
        window.deleteExistingImage = deleteExistingImage;
        window.updateHiddenInputs = updateHiddenInputs;

        // Confirm delete product
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

        // Form submit with images
        document.getElementById('create-product-form').addEventListener('submit', (e) => {
            // Files are handled automatically via the form
        });
        
        function printBarcode(code) {
            if (!code) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Sản phẩm chưa có mã vạch!'
                });
                return;
            }
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>In mã vạch - ${code}</title>
                        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
                        <style>
                            * { box-sizing: border-box; }
                            body { 
                                display: flex; 
                                flex-direction: column; 
                                align-items: center; 
                                justify-content: center; 
                                min-height: 100vh; 
                                margin: 0; 
                                padding: 20px;
                                font-family: monospace;
                            }
                            .barcode-wrapper { 
                                text-align: center; 
                                border: 1px dashed #ccc;
                                padding: 20px;
                                border-radius: 8px;
                            }
                            .barcode-wrapper svg { 
                                max-width: 300px; 
                                height: auto; 
                            }
                            .code { 
                                font-size: 20px; 
                                margin-top: 10px; 
                                font-weight: bold;
                                letter-spacing: 2px;
                            }
                            @media print {
                                @page { size: auto; margin: 0mm; }
                                body { height: auto; padding: 10px; }
                                .no-print { display: none !important; }
                            }
                        </style>
                    </head>
                    <body onload="window.print();">
                        <div class="barcode-wrapper">
                            <svg id="barcode"></svg>
                            <div class="code">${code}</div>
                        </div>
                        <script>
                            JsBarcode("#barcode", "${code}", {
                                format: "CODE128",
                                width: 2,
                                height: 60,
                                displayValue: false,
                                margin: 10
                            });
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>

    <style>
        /* Dropzone Styles */
        .dropzone-container {
            width: 100%;
        }
        .dropzone-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        .dropzone-area:hover,
        .dropzone-area.dragover {
            border-color: #135bec;
            background: #eef2ff;
        }
        .dropzone-icon {
            font-size: 48px;
            color: #adb5bd;
            margin-bottom: 12px;
        }
        .dropzone-text {
            color: #495057;
            font-size: 14px;
        }
        
        /* Gallery Grid Styles */
        .gallery-container {
            width: 100%;
        }
        .gallery-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .image-item {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
            cursor: grab;
        }
        .image-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .image-item.sortable-ghost {
            opacity: 0.4;
        }
        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-item.main-image {
            ring: 2px solid #135bec;
            box-shadow: 0 0 0 2px #135bec;
        }
        .image-actions {
            position: absolute;
            top: 4px;
            right: 4px;
            display: flex;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .image-item:hover .image-actions {
            opacity: 1;
        }
        .btn-star, .btn-delete {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
            transition: all 0.2s;
        }
        .btn-star {
            background: rgba(255,255,255,0.9);
            color: #ffc107;
        }
        .btn-star:hover {
            background: #ffc107;
            color: #fff;
        }
        .btn-delete {
            background: rgba(255,255,255,0.9);
            color: #dc3545;
        }
        .btn-delete:hover {
            background: #dc3545;
            color: #fff;
        }
        .main-badge {
            position: absolute;
            bottom: 4px;
            left: 4px;
            background: #135bec;
            color: white;
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }
        .image-info {
            position: absolute;
            bottom: 4px;
            right: 4px;
            background: rgba(0,0,0,0.6);
            color: white;
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Existing Images Grid */
        .existing-images-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
            min-height: 120px;
        }

        /* Modal enhancements */
        .modal-xl {
            max-width: 900px;
        }
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
@endpush
