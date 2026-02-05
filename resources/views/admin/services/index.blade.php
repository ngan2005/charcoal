@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý dịch vụ</h1>
                <p class="text-sm text-gray-500">Theo dõi và cập nhật danh sách dịch vụ</p>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createServiceModal">
                Thêm dịch vụ
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
            <form class="grid gap-4 md:grid-cols-2" method="GET" action="{{ route('admin.services.index') }}">
                <div>
                    <label class="text-sm font-medium text-gray-700">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] }}"
                        class="mt-1 w-full rounded-lg border-gray-200 focus:border-primary focus:ring-primary"
                        placeholder="Tên dịch vụ">
                </div>
                <div class="flex items-end gap-3 md:col-span-2">
                    <button class="btn btn-primary">Lọc dữ liệu</button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Dịch vụ</th>
                        <th class="px-4 py-3 text-left">Ảnh</th>
                        <th class="px-4 py-3 text-left">Giá</th>
                        <th class="px-4 py-3 text-left">Thời lượng</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($services as $service)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $service->ServiceName }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $service->ServiceID }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @php($images = $service->images ?? collect())
                                <div class="flex items-center gap-1">
                                    @foreach ($images->take(4) as $index => $image)
                                        <img
                                            src="{{ $image->ImageUrl }}"
                                            alt="{{ $service->ServiceName }}"
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
                            <td class="px-4 py-3 text-gray-600">{{ number_format((float) $service->BasePrice) }} VNĐ</td>
                            <td class="px-4 py-3 text-gray-600">{{ $service->Duration }} phút</td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex gap-2">
                                    <button
                                        class="btn btn-outline-secondary btn-sm edit-service-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editServiceModal"
                                        data-action="{{ route('admin.services.update', $service->ServiceID) }}"
                                        data-name="{{ $service->ServiceName }}"
                                        data-price="{{ $service->BasePrice }}"
                                        data-duration="{{ $service->Duration }}"
                                        data-description="{{ $service->Description }}">
                                        Sửa
                                    </button>
                                    <form method="POST" action="{{ route('admin.services.destroy', $service->ServiceID) }}" data-confirm class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Không có dịch vụ nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $services->links() }}
        </div>
    </div>

    <div class="modal fade" id="createServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm dịch vụ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
                    @csrf
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
                                <label class="form-label">Tên dịch vụ <span class="text-danger">*</span></label>
                                <input type="text" name="ServiceName" class="form-control" placeholder="Nhập tên dịch vụ" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="BasePrice" class="form-control" min="0" placeholder="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Thời lượng (phút) <span class="text-danger">*</span></label>
                                <input type="number" name="Duration" class="form-control" min="0" placeholder="0" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mô tả</label>
                                <textarea name="Description" class="form-control" rows="3" placeholder="Nhập mô tả dịch vụ..."></textarea>
                            </div>
                            
                            <!-- Hình ảnh Dịch vụ -->
                            <div class="col-12 mt-4">
                                <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                    <span class="material-symbols-outlined me-2">image</span>
                                    Hình ảnh dịch vụ
                                </h6>
                            </div>
                            <div class="col-12">
                                <div class="dropzone-container" id="create-service-dropzone">
                                    <div class="dropzone-area">
                                        <div class="dropzone-content">
                                            <span class="material-symbols-outlined dropzone-icon">cloud_upload</span>
                                            <p class="dropzone-text mb-2">Kéo thả ảnh vào đây hoặc</p>
                                            <label for="create-service-image-input" class="btn btn-outline-primary btn-sm">Chọn ảnh</label>
                                            <input type="file" id="create-service-image-input" name="new_images[]" class="d-none" accept="image/*" multiple>
                                            <p class="text-xs text-muted mt-2 mb-0">Hỗ trợ JPG, PNG, WEBP. Tối đa 5MB/ảnh</p>
                                        </div>
                                    </div>
                                    <div class="gallery-container mt-3">
                                        <p class="text-sm text-muted mb-2">Thứ tự ảnh đầu tiên sẽ là ảnh đại diện (⭐)</p>
                                        <div class="gallery-grid" id="create-service-gallery">
                                            <!-- Images will be added here -->
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="MainImageIndex" id="create-service-main-image-index" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success px-4 fw-bold">Thêm dịch vụ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật dịch vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-service-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                <label class="form-label">Tên dịch vụ <span class="text-danger">*</span></label>
                                <input type="text" name="ServiceName" id="edit-service-name" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="BasePrice" id="edit-service-price" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Thời lượng (phút) <span class="text-danger">*</span></label>
                                <input type="number" name="Duration" id="edit-service-duration" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mô tả</label>
                                <textarea name="Description" id="edit-service-description" class="form-control" rows="3"></textarea>
                            </div>

                            <!-- Quản lý Hình ảnh Dịch vụ -->
                            <div class="col-12 mt-4">
                                <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                    <span class="material-symbols-outlined me-2">image</span>
                                    Quản lý hình ảnh
                                </h6>
                            </div>
                            <div class="col-12">
                                <!-- Existing Images -->
                                <div class="mb-4">
                                    <p class="text-sm text-muted mb-2">Ảnh hiện tại (ảnh đầu tiên là ảnh đại diện)</p>
                                    <div class="existing-images-grid" id="edit-service-existing-images">
                                        <!-- Will be loaded via JS -->
                                    </div>
                                    <input type="hidden" name="DeleteImageIDs" id="service-delete-image-ids" value="">
                                    <input type="hidden" name="MainImageID" id="service-main-image-id" value="">
                                </div>

                                <!-- Dropzone for new images -->
                                <div class="dropzone-container" id="edit-service-dropzone">
                                    <div class="dropzone-area">
                                        <div class="dropzone-content">
                                            <span class="material-symbols-outlined dropzone-icon">cloud_upload</span>
                                            <p class="dropzone-text mb-2">Kéo thả ảnh mới vào đây hoặc</p>
                                            <label for="edit-service-image-input" class="btn btn-outline-primary btn-sm">Chọn ảnh</label>
                                            <input type="file" id="edit-service-image-input" name="new_images[]" class="d-none" accept="image/*" multiple>
                                            <p class="text-xs text-muted mt-2 mb-0">Hỗ trợ JPG, PNG, WEBP. Tối đa 5MB/ảnh</p>
                                        </div>
                                    </div>
                                    <div class="gallery-container mt-3">
                                        <p class="text-sm text-muted mb-2">Ảnh mới thêm (kéo thả để sắp xếp)</p>
                                        <div class="gallery-grid" id="edit-service-gallery">
                                            <!-- New images will be added here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Cập nhật dịch vụ</button>
                    </div>
                </form>
            </div>
        </div>
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
    <script>
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

        function updateHiddenInputs() {
            const deleteInput = document.getElementById('service-delete-image-ids');
            const mainInput = document.getElementById('service-main-image-id');
            if (deleteInput) deleteInput.value = deletedImageIds.join(',');
            if (mainInput) mainInput.value = mainImageId || '';
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Initialize galleries
            createGalleryHandler = new GalleryImageHandler('create-service-gallery', 'create-service-image-input', 'create-service-main-image-index');
            window.galleryHandler_create_service_gallery = createGalleryHandler;

            editGalleryHandler = new GalleryImageHandler('edit-service-gallery', 'edit-service-image-input', null);
            window.galleryHandler_edit_service_gallery = editGalleryHandler;

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
                    const result = await confirmWithSwal('Xóa dịch vụ?', 'Thao tác này không thể hoàn tác.');
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            document.querySelectorAll('.edit-service-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const form = document.getElementById('edit-service-form');
                    form.action = button.dataset.action;
                    document.getElementById('edit-service-name').value = button.dataset.name || '';
                    document.getElementById('edit-service-price').value = button.dataset.price || '';
                    document.getElementById('edit-service-duration').value = button.dataset.duration || '';
                    document.getElementById('edit-service-description').value = button.dataset.description || '';

                    // Load existing images
                    const serviceId = button.dataset.action.split('/').pop();
                    await loadExistingImages(serviceId);
                });
            });

            // Image Preview Modal Logic
            document.querySelectorAll('.img-preview-trigger').forEach(img => {
                img.addEventListener('click', function() {
                    const fullSrc = this.getAttribute('data-src');
                    document.getElementById('preview-image-element').src = fullSrc;
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

        async function loadExistingImages(serviceId) {
            try {
                const response = await fetch(`/admin/services/${serviceId}/images`);
                if (response.ok) {
                    const data = await response.json();
                    existingImages = data.images || [];
                    deletedImageIds = [];
                    mainImageId = existingImages.length > 0 ? existingImages.find(img => img.IsMain)?.ImageID || existingImages[0]?.ImageID : null;
                    renderExistingImages();
                }
            } catch (error) {
                console.error('Error loading images:', error);
            }
        }

        function renderExistingImages() {
            const container = document.getElementById('edit-service-existing-images');
            if (!container) return;

            if (existingImages.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-4 w-100">Chưa có ảnh nào</p>';
                return;
            }

            container.innerHTML = existingImages.map((img, index) => `
                <div class="image-item ${img.IsMain || (mainImageId === img.ImageID) ? 'main-image' : ''}" data-image-id="${img.ImageID}">
                    <img src="${img.ImageUrl}" alt="Ảnh ${index + 1}">
                    <div class="image-actions">
                        <button type="button" class="btn-star" title="Đặt làm ảnh chính" onclick="setExistingAsMain(${img.ImageID})">
                            <span class="material-symbols-outlined">${img.IsMain || (mainImageId === img.ImageID) ? 'star' : 'star_border'}</span>
                        </button>
                        <button type="button" class="btn-delete" title="Xóa ảnh" onclick="deleteExistingImage(${img.ImageID})">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    ${img.IsMain || (mainImageId === img.ImageID) ? '<div class="main-badge">Ảnh chính</div>' : ''}
                </div>
            `).join('');

            updateHiddenInputs();
        }

        function setExistingAsMain(imageId) {
            existingImages.forEach(img => {
                img.IsMain = img.ImageID === imageId;
            });
            mainImageId = imageId;
            renderExistingImages();
        }

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
                    if (!deletedImageIds.includes(imageId)) {
                        deletedImageIds.push(imageId);
                    }
                    existingImages = existingImages.filter(img => img.ImageID !== imageId);
                    if (mainImageId === imageId && existingImages.length > 0) {
                        existingImages[0].IsMain = true;
                        mainImageId = existingImages[0].ImageID;
                    }
                    renderExistingImages();
                }
            });
        }

        window.setExistingAsMain = setExistingAsMain;
        window.deleteExistingImage = deleteExistingImage;
        window.updateHiddenInputs = updateHiddenInputs;
    </script>
@endpush
