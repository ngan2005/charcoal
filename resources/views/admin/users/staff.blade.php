@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nhân viên</h1>
                <p class="text-sm text-gray-500">Theo dõi lịch làm và dịch vụ của nhân viên</p>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
                Thêm nhân viên
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form class="flex flex-col gap-3 md:flex-row md:items-end" method="GET" action="{{ route('admin.users.staff') }}">
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
                    <a href="{{ route('admin.users.staff') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Nhân viên</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Số điện thoại</th>
                        <th class="px-4 py-3 text-center">Số ca làm</th>
                        <th class="px-4 py-3 text-center">Số dịch vụ</th>
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
                            <td class="px-4 py-3 text-gray-600">{{ $user->Phone ?? 'Chưa cập nhật' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-info text-white">{{ $user->shifts_count ?? 0 }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge bg-warning text-dark">{{ $user->services_count ?? 0 }}</span>
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
                                Không có nhân viên phù hợp.
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
                    <h5 class="modal-title">Thêm nhân viên</h5>
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
                                <span class="material-symbols-outlined me-1 align-middle" style="font-size: 16px;">work</span>
                                Nhân viên
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
                                <span class="material-symbols-outlined text-primary fs-2">schedule</span>
                                <div class="fw-bold fs-4 text-primary" id="view-shifts-count">0</div>
                                <small class="text-muted">Ca làm việc</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3 text-center">
                                <span class="material-symbols-outlined text-success fs-2">spa</span>
                                <div class="fw-bold fs-4 text-success" id="view-services-count">0</div>
                                <small class="text-muted">Dịch vụ đã làm</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3 text-center">
                                <span class="material-symbols-outlined text-info fs-2">star</span>
                                <div class="fw-bold fs-4 text-info" id="view-rating">--</div>
                                <small class="text-muted">Đánh giá</small>
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

                        <!-- Thông tin công việc -->
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                                <span class="material-symbols-outlined me-2">badge</span>
                                Thông tin công việc
                            </h6>
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="bg-light rounded-3 p-2">
                                            <span class="material-symbols-outlined text-secondary">badge</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Mã nhân viên</small>
                                            <span class="fw-medium" id="view-user-id">--</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="bg-light rounded-3 p-2">
                                            <span class="material-symbols-outlined text-secondary">work</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Chức vụ</small>
                                            <span class="fw-medium" id="view-position">--</span>
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
                    const result = await confirmWithSwal('Xóa nhân viên?', 'Thao tác này không thể hoàn tác.');
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            document.querySelectorAll('.reset-password-form').forEach(form => {
                form.addEventListener('submit', async event => {
                    event.preventDefault();
                    const userName = form.dataset.user || 'nhân viên';
                    const result = await confirmWithSwal('Reset mật khẩu?', `Bạn muốn đặt lại mật khẩu cho ${userName}?`);
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // View staff details with AJAX
            document.querySelectorAll('.view-user-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const userId = button.dataset.id;
                    const modalEl = document.getElementById('viewUserModal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

                    // Reset modal content
                    document.getElementById('view-name').textContent = 'Đang tải...';

                    try {
                        const response = await fetch(`/admin/users/staff/${userId}/details`);
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

                        // Staff info
                        document.getElementById('view-shifts-count').textContent = data.shifts_count;
                        document.getElementById('view-services-count').textContent = data.services_count;
                        document.getElementById('view-rating').textContent = data.staff_profile && data.staff_profile.Rating ? data.staff_profile.Rating + '/5' : '--';
                        document.getElementById('view-position').textContent = data.staff_profile && data.staff_profile.Position ? data.staff_profile.Position : 'Chưa cập nhật';

                    } catch (error) {
                        console.error('Error:', error);
                        alert('Lỗi khi tải dữ liệu: ' + error.message);
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
