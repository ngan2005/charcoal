@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Khách hàng</h1>
                <p class="text-sm text-gray-500">Tổng quan đơn hàng và lịch hẹn của khách hàng</p>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
                Thêm khách hàng
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form class="flex flex-col gap-3 md:flex-row md:items-end" method="GET" action="{{ route('admin.users.customers') }}">
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-700">Tìm kiếm</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] }}"
                        class="mt-1 w-full rounded-lg border-gray-200 focus:border-primary focus:ring-primary"
                        placeholder="Tên hoặc Email">
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-primary">Lọc dữ liệu</button>
                    <a href="{{ route('admin.users.customers') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Khách hàng</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-center">Thú cưng</th>
                        <th class="px-4 py-3 text-left">Đơn hàng</th>
                        <th class="px-4 py-3 text-left">Tổng tiền</th>
                        <th class="px-4 py-3 text-left">Trạng thái</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @php
                                        $avatarPath = str_replace('\\', '/', (string) $user->Avatar);
                                        if ($avatarPath) {
                                            if (preg_match('/^https?:\/\//', $avatarPath)) {
                                                $avatarUrl = $avatarPath;
                                            } elseif (str_starts_with($avatarPath, '/storage') || str_starts_with($avatarPath, 'storage/')) {
                                                $avatarUrl = asset($avatarPath);
                                            } else {
                                                $avatarUrl = asset('storage/' . ltrim($avatarPath, '/'));
                                            }
                                        } else {
                                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->FullName) . '&background=E2E8F0&color=1F2937';
                                        }
                                    @endphp
                                    <img
                                        class="h-9 w-9 rounded-full"
                                        src="{{ $avatarUrl ?? ('https://ui-avatars.com/api/?name=' . urlencode($user->FullName) . '&background=E2E8F0&color=1F2937') }}"
                                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->FullName) }}&background=E2E8F0&color=1F2937';"
                                        alt="{{ $user->FullName }}">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->FullName }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $user->UserID }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->Email }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-info text-white">{{ $user->pets_count ?? 0 }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->orders_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ number_format((float) ($user->orders_total ?? 0), 0, ',', '.') }} đ
                            </td>
                            <td class="px-4 py-3">
                                @if ($user->IsActive)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Hoạt động</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Bị khóa</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex gap-2">
                                    <button
                                        class="btn btn-outline-primary btn-sm view-user-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewUserModal"
                                        data-id="{{ $user->UserID }}">
                                        Xem
                                    </button>
                                    <form method="POST" action="{{ route('admin.users.reset-password', $user->UserID) }}" class="inline-block reset-password-form" data-user="{{ $user->FullName }}">
                                        @csrf
                                        <button class="btn btn-outline-warning btn-sm" type="submit">Reset mật khẩu</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->UserID) }}" data-confirm class="inline-block">
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
                                Không có khách hàng phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $users->links() }}
        </div>
    </div>

    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ tên</label>
                                <input type="text" name="FullName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="Email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mật khẩu</label>
                                <input type="password" name="Password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nhập lại mật khẩu</label>
                                <input type="password" name="Password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="Phone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" name="Address" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vai trò</label>
                                <select name="RoleID" class="form-select" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->RoleID }}" @selected($role->RoleID == $defaultRoleId)>
                                            {{ $role->RoleName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="IsActive" class="form-select" required>
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Bị khóa</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" name="AvatarFile" class="form-control" accept="image/*">
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

    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <!-- Header với Avatar -->
                <div class="modal-header bg-primary text-white border-0 pb-0" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex align-items-center gap-3 w-100">
                        <div class="position-relative">
                            <img id="view-avatar" class="rounded-circle object-fit-cover border border-white border-4 shadow" width="80" height="80" src="" alt="" style="background: white;">
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="modal-title mb-1 fw-bold fs-4" id="view-name">--</h5>
                            <span class="badge bg-white text-primary fw-semibold">
                                <span class="material-symbols-outlined me-1 align-middle" style="font-size: 16px;">person</span>
                                Khách hàng
                            </span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white position-absolute" data-bs-dismiss="modal" style="top: 16px; right: 16px;"></button>
                </div>

                <div class="modal-body p-4">
                    <!-- Thống kê nhanh -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 text-center">
                                <span class="material-symbols-outlined text-primary fs-2">shopping_cart</span>
                                <div class="fw-bold fs-4 text-primary" id="view-orders-count">0</div>
                                <small class="text-muted">Đơn hàng</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3 text-center">
                                <span class="material-symbols-outlined text-success fs-2">payments</span>
                                <div class="fw-bold fs-4 text-success" id="view-orders-total">0 đ</div>
                                <small class="text-muted">Tổng chi tiêu</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3 text-center">
                                <span class="material-symbols-outlined text-info fs-2">calendar_month</span>
                                <div class="fw-bold fs-4 text-info" id="view-appointments-count">0</div>
                                <small class="text-muted">Lịch hẹn</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Thông tin liên hệ -->
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">contact_mail</span>
                                Thông tin liên hệ
                            </h6>
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="bg-light rounded-3 p-2">
                                            <span class="material-symbols-outlined text-primary">mail</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Email</small>
                                            <span class="fw-medium" id="view-email">--</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="bg-light rounded-3 p-2">
                                            <span class="material-symbols-outlined text-success">phone</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Số điện thoại</small>
                                            <span class="fw-medium" id="view-phone">--</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="bg-light rounded-3 p-2">
                                            <span class="material-symbols-outlined text-warning">location_on</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Địa chỉ</small>
                                            <span class="fw-medium" id="view-address">--</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light rounded-3 p-2">
                                            <span class="material-symbols-outlined text-info">verified</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Trạng thái</small>
                                            <span id="view-status-badge-display"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin tài khoản -->
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">info</span>
                                Thông tin tài khoản
                            </h6>
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="bg-light rounded-3 p-2">
                                            <span class="material-symbols-outlined text-secondary">badge</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Mã khách hàng</small>
                                            <span class="fw-medium" id="view-user-id">--</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="bg-light rounded-3 p-2">
                                            <span class="material-symbols-outlined text-secondary">calendar_today</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Ngày tham gia</small>
                                            <span class="fw-medium" id="view-created">--</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light rounded-3 p-2">
                                            <span class="material-symbols-outlined text-secondary">schedule</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Đăng nhập gần nhất</small>
                                            <span class="fw-medium" id="view-last-login">--</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thú cưng -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold text-uppercase text-muted mb-0 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">pets</span>
                                Thú cưng của khách hàng
                            </h6>
                            <span id="pets-count" class="badge bg-primary">0 thú cưng</span>
                        </div>
                        <div id="pets-container" class="row g-3">
                            <!-- Pets loaded via AJAX -->
                            <div class="col-12 text-center py-5 text-muted">
                                <span class="material-symbols-outlined d-block mb-2" style="font-size: 48px; opacity: 0.5;">cruelty_free</span>
                                <p class="mb-0">Chưa có thú cưng nào</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer chỉ có nút Đóng -->
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">
                        <span class="material-symbols-outlined me-1 align-middle">close</span>
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Helper function to format avatar URL
        function formatAvatarUrl(avatarPath) {
            if (!avatarPath) {
                return 'https://ui-avatars.com/api/?name=User&background=E2E8F0&color=1F2937&size=128';
            }
            const path = avatarPath.replace(/\\/g, '/');
            if (/^https?:\/\//.test(path)) {
                return path;
            }
            if (path.startsWith('/storage') || path.startsWith('storage/')) {
                return '{{ asset('') }}' + path;
            }
            return '{{ asset('') }}storage/' + path;
        }

        // Helper function to get species icon
        function getSpeciesIcon(species) {
            const icons = {
                'Chó': 'cruelty_free',
                'Mèo': 'pets',
                'Hamster': 'cruelty_free',
                'Chim': 'flutter_dash',
                'Cá': 'water',
                'Thỏ': 'cruelty_free',
            };
            return icons[species] || 'pets';
        }

        // Helper function to get species color
        function getSpeciesColor(species) {
            const colors = {
                'Chó': '#8B5CF6',
                'Mèo': '#F59E0B',
                'Hamster': '#EC4899',
                'Chim': '#06B6D4',
                'Cá': '#3B82F6',
                'Thỏ': '#F97316',
            };
            return colors[species] || '#6B7280';
        }

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
                    const result = await confirmWithSwal('Xóa khách hàng?', 'Thao tác này không thể hoàn tác.');
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            document.querySelectorAll('.reset-password-form').forEach(form => {
                form.addEventListener('submit', async event => {
                    event.preventDefault();
                    const userName = form.dataset.user || 'khách hàng';
                    const result = await confirmWithSwal('Reset mật khẩu?', `Bạn muốn đặt lại mật khẩu cho ${userName}?`);
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // View customer details with AJAX
            document.querySelectorAll('.view-user-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const userId = button.dataset.id;
                    const modalEl = document.getElementById('viewUserModal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

                    // Reset modal content
                    document.getElementById('view-name').textContent = 'Đang tải...';
                    document.getElementById('pets-container').innerHTML = `
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `;

                    try {
                        const response = await fetch(`/admin/users/customer/${userId}/details`);
                        const data = await response.json();

                        if (!response.ok) throw new Error(data.message || 'Lỗi khi tải dữ liệu');

                        // Update user info
                        document.getElementById('view-name').textContent = data.user.FullName;
                        document.getElementById('view-user-id').textContent = '#' + data.user.UserID;
                        document.getElementById('view-email').textContent = data.user.Email || 'Chưa cập nhật';
                        document.getElementById('view-phone').textContent = data.user.Phone || 'Chưa cập nhật';
                        document.getElementById('view-address').textContent = data.user.Address || 'Chưa cập nhật';
                        document.getElementById('view-created').textContent = data.user.CreatedAt ?
                            new Date(data.user.CreatedAt).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' }) : '--';
                        document.getElementById('view-last-login').textContent = data.user.LastLogin ?
                            new Date(data.user.LastLogin).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'Chưa đăng nhập';

                        // Avatar
                        document.getElementById('view-avatar').src = formatAvatarUrl(data.user.Avatar);
                        document.getElementById('view-avatar').onerror = function() {
                            this.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.user.FullName) + '&background=E2E8F0&color=1F2937&size=128';
                        };

                        // Status badge
                        const statusBadge = document.getElementById('view-status-badge-display');
                        if (data.user.IsActive) {
                            statusBadge.innerHTML = '<span class="badge bg-success">Hoạt động</span>';
                        } else {
                            statusBadge.innerHTML = '<span class="badge bg-danger">Bị khóa</span>';
                        }

                        // Orders info
                        document.getElementById('view-orders-count').textContent = data.orders_count;
                        document.getElementById('view-orders-total').textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', maximumFractionDigits: 0 }).format(data.orders_total || 0);
                        document.getElementById('view-appointments-count').textContent = data.appointments_count;

                        // Render pets
                        const petsContainer = document.getElementById('pets-container');
                        document.getElementById('pets-count').textContent = `${data.pets.length} thú cưng`;

                        if (data.pets.length === 0) {
                            petsContainer.innerHTML = `
                                <div class="col-12 text-center py-5 text-muted">
                                    <span class="material-symbols-outlined d-block mb-2" style="font-size: 48px; opacity: 0.5;">cruelty_free</span>
                                    <p class="mb-0">Chưa có thú cưng nào</p>
                                </div>
                            `;
                        } else {
                            petsContainer.innerHTML = data.pets.map(pet => `
                                <div class="col-md-6">
                                    <div class="pet-card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden; transition: all 0.3s ease;">
                                        <div class="p-3" style="background: linear-gradient(135deg, ${getSpeciesColor(pet.Species)}15, ${getSpeciesColor(pet.Species)}08);">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="pet-avatar rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 48px; height: 48px; font-size: 24px; background: ${getSpeciesColor(pet.Species)};">
                                                    <span class="material-symbols-outlined">${getSpeciesIcon(pet.Species)}</span>
                                                </div>
                                                <div class="flex-grow-1 min-width-0">
                                                    <h6 class="mb-0 text-truncate fw-bold">${pet.PetName || 'Chưa đặt tên'}</h6>
                                                    <small class="text-muted">${pet.Species || '--'}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3">
                                            ${pet.Breed ? `<div class="d-flex align-items-center gap-2 mb-2 text-secondary">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">breaking_bad</span>
                                                <span class="small">${pet.Breed}</span>
                                            </div>` : ''}
                                            ${pet.Size ? `<div class="d-flex align-items-center gap-2 mb-2 text-secondary">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">straighten</span>
                                                <span class="small">${pet.Size}</span>
                                            </div>` : ''}
                                            ${pet.Age ? `<div class="d-flex align-items-center gap-2 mb-2 text-secondary">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">cake</span>
                                                <span class="small">${pet.Age} năm</span>
                                            </div>` : ''}
                                            ${pet.Notes ? `<div class="d-flex align-items-start gap-2 mt-3 text-secondary bg-light p-2 rounded" style="font-size: 12px;">
                                                <span class="material-symbols-outlined flex-shrink-0" style="font-size: 16px;">notes</span>
                                                <span class="text-truncate d-block">${pet.Notes}</span>
                                            </div>` : ''}
                                        </div>
                                    </div>
                                </div>
                            `).join('');
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        document.getElementById('pets-container').innerHTML = `
                            <div class="col-12 text-center py-5 text-danger">
                                <span class="material-symbols-outlined d-block mb-2" style="font-size: 48px;">error</span>
                                <p class="mb-0">Lỗi khi tải dữ liệu: ${error.message}</p>
                            </div>
                        `;
                    }
                });
            });

            const resetInfo = @json(session('reset_password'));
            if (resetInfo) {
                Swal.fire({
                    title: 'Mật khẩu mới đã được tạo',
                    html: `<div class="text-left">` +
                        `<p><strong>Họ tên:</strong> ${resetInfo.name}</p>` +
                        `<p><strong>Email:</strong> ${resetInfo.email}</p>` +
                        `<p><strong>Mật khẩu:</strong> <span class="font-mono">${resetInfo.password}</span></p>` +
                        `</div>`,
                    icon: 'info',
                    confirmButtonText: 'Đã hiểu',
                });
            }

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
