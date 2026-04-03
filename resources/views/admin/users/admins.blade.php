@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý admin</h1>
                <p class="text-sm text-gray-500">Tạo và quản lý tài khoản quản trị viên</p>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                Thêm admin
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form class="flex flex-col gap-3 md:flex-row md:items-end" method="GET" action="{{ route('admin.users.admins') }}">
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
                    <a href="{{ route('admin.users.admins') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Quản trị viên</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Số điện thoại</th>
                        <th class="px-4 py-3 text-left">Đăng nhập gần nhất</th>
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
                                        src="{{ $avatarUrl }}"
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
                            <td class="px-4 py-3 text-gray-600">
                                @if ($user->LastLogin)
                                    {{ \Carbon\Carbon::parse($user->LastLogin)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-400">Chưa đăng nhập</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($user->IsActive)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Hoạt động</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Bị khóa</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex gap-2 flex-wrap justify-end">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm view-admin-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewAdminModal"
                                        data-id="{{ $user->UserID }}">
                                        Xem
                                    </button>
                                    <form method="POST" action="{{ route('admin.users.reset-password', $user->UserID) }}" class="inline-block reset-password-form" data-user="{{ $user->FullName }}">
                                        @csrf
                                        <input type="hidden" name="_redirect" value="admin.users.admins">
                                        <button class="btn btn-outline-warning btn-sm" type="submit">Reset mật khẩu</button>
                                    </form>
                                    @if ((int) $user->UserID !== (int) auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->UserID) }}" data-confirm class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="_redirect" value="admin.users.admins">
                                            <button class="btn btn-outline-danger btn-sm" type="submit">Xóa</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 self-center">Tài khoản của bạn</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Chưa có tài khoản admin nào.
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

    <div class="modal fade" id="createAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm tài khoản admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_redirect" value="admin.users.admins">
                    <input type="hidden" name="RoleID" value="1">
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
                        <button type="submit" class="btn btn-success">Tạo admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header bg-dark text-white border-0" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex align-items-center gap-3 w-100">
                        <img id="view-admin-avatar" class="rounded-circle border border-white border-3" width="64" height="64" src="" alt="">
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="view-admin-name">--</h5>
                            <span class="badge bg-light text-dark">Quản trị viên</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <p class="mb-2"><span class="text-muted small">Email</span><br><span id="view-admin-email" class="fw-medium">--</span></p>
                            <p class="mb-2"><span class="text-muted small">Điện thoại</span><br><span id="view-admin-phone" class="fw-medium">--</span></p>
                            <p class="mb-2"><span class="text-muted small">Địa chỉ</span><br><span id="view-admin-address" class="fw-medium">--</span></p>
                            <p class="mb-2"><span class="text-muted small">Ngày tạo</span><br><span id="view-admin-created" class="fw-medium">--</span></p>
                            <p class="mb-0"><span class="text-muted small">Đăng nhập gần nhất</span><br><span id="view-admin-last-login" class="fw-medium">--</span></p>
                        </div>
                    </div>
                    <div class="mt-3" id="view-admin-status"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function formatAvatarUrl(avatarPath) {
            if (!avatarPath) {
                return 'https://ui-avatars.com/api/?name=Admin&background=E2E8F0&color=1F2937&size=128';
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
                    const result = await confirmWithSwal('Xóa tài khoản admin?', 'Thao tác này không thể hoàn tác.');
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            document.querySelectorAll('.reset-password-form').forEach(form => {
                form.addEventListener('submit', async event => {
                    event.preventDefault();
                    const userName = form.dataset.user || 'admin';
                    const result = await confirmWithSwal('Reset mật khẩu?', `Bạn muốn đặt lại mật khẩu cho ${userName}?`);
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            document.querySelectorAll('.view-admin-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const userId = button.dataset.id;
                    try {
                        const response = await fetch(`{{ url('/admin/users/admin') }}/${userId}/details`);
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Lỗi khi tải dữ liệu');

                        document.getElementById('view-admin-name').textContent = data.user.FullName;
                        document.getElementById('view-admin-email').textContent = data.user.Email || '—';
                        document.getElementById('view-admin-phone').textContent = data.user.Phone || '—';
                        document.getElementById('view-admin-address').textContent = data.user.Address || '—';
                        document.getElementById('view-admin-created').textContent = data.user.CreatedAt
                            ? new Date(data.user.CreatedAt).toLocaleString('vi-VN')
                            : '—';
                        document.getElementById('view-admin-last-login').textContent = data.user.LastLogin
                            ? new Date(data.user.LastLogin).toLocaleString('vi-VN')
                            : 'Chưa đăng nhập';

                        document.getElementById('view-admin-avatar').src = formatAvatarUrl(data.user.Avatar);
                        document.getElementById('view-admin-avatar').onerror = function () {
                            this.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.user.FullName) + '&background=E2E8F0&color=1F2937&size=128';
                        };

                        const statusEl = document.getElementById('view-admin-status');
                        statusEl.innerHTML = data.user.IsActive
                            ? '<span class="badge bg-success">Hoạt động</span>'
                            : '<span class="badge bg-danger">Bị khóa</span>';
                    } catch (e) {
                        console.error(e);
                        alert('Không tải được thông tin admin.');
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

            const errorMessage = @json(session('error'));
            if (errorMessage) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: errorMessage,
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                });
            }
        });
    </script>
@endpush
