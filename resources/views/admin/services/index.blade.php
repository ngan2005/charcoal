@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                                <div class="flex items-center gap-2">
                                    @foreach ($images->take(3) as $image)
                                        <img
                                            src="{{ $image->ImageUrl }}"
                                            alt="{{ $service->ServiceName }}"
                                            class="h-8 w-8 rounded border border-gray-200 object-cover cursor-pointer hover:scale-110 transition-transform img-preview-trigger"
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
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm dịch vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên dịch vụ</label>
                                <input type="text" name="ServiceName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Giá</label>
                                <input type="number" name="BasePrice" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Thời lượng (phút)</label>
                                <input type="number" name="Duration" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mô tả</label>
                                <textarea name="Description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Ảnh dịch vụ</label>
                                <input type="file" name="Images[]" class="form-control" accept="image/*" multiple>
                                <p class="text-xs text-gray-500 mt-1">Có thể chọn nhiều ảnh.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật dịch vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-service-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên dịch vụ</label>
                                <input type="text" name="ServiceName" id="edit-service-name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Giá</label>
                                <input type="number" name="BasePrice" id="edit-service-price" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Thời lượng (phút)</label>
                                <input type="number" name="Duration" id="edit-service-duration" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Mô tả</label>
                                <textarea name="Description" id="edit-service-description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Ảnh dịch vụ (thêm mới)</label>
                                <input type="file" name="Images[]" class="form-control" accept="image/*" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
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
                    const result = await confirmWithSwal('Xóa dịch vụ?', 'Thao tác này không thể hoàn tác.');
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            document.querySelectorAll('.edit-service-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const form = document.getElementById('edit-service-form');
                    form.action = button.dataset.action;
                    document.getElementById('edit-service-name').value = button.dataset.name || '';
                    document.getElementById('edit-service-price').value = button.dataset.price || '';
                    document.getElementById('edit-service-duration').value = button.dataset.duration || '';
                    document.getElementById('edit-service-description').value = button.dataset.description || '';
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
