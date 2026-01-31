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
                        <th class="px-4 py-3 text-left">Số ca làm</th>
                        <th class="px-4 py-3 text-left">Số dịch vụ</th>
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
                            <td class="px-4 py-3 text-gray-600">{{ $user->shifts_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->services_count ?? 0 }}</td>
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
                                        data-id="{{ $user->UserID }}"
                                        data-name="{{ $user->FullName }}"
                                        data-email="{{ $user->Email }}"
                                        data-role="{{ $user->role?->RoleName ?? 'N/A' }}"
                                        data-status="{{ $user->IsActive ? 'Hoạt động' : 'Bị khóa' }}"
                                        data-created="{{ optional($user->CreatedAt)->format('d/m/Y H:i') }}">
                                        Xem
                                    </button>
                                    <form method="POST" action="{{ route('admin.users.reset-password', $user->UserID) }}" class="inline-block reset-password-form" data-user="{{ $user->FullName }}">
                                        @csrf
                                        <button class="btn btn-outline-warning btn-sm" type="submit">Reset mật khẩu</button>
                                    </form>
                                    <button
                                        class="btn btn-outline-secondary btn-sm edit-user-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal"
                                        data-action="{{ route('admin.users.update', $user->UserID) }}"
                                        data-id="{{ $user->UserID }}"
                                        data-name="{{ $user->FullName }}"
                                        data-email="{{ $user->Email }}"
                                        data-phone="{{ $user->Phone }}"
                                        data-address="{{ $user->Address }}"
                                        data-role-id="{{ $user->RoleID }}"
                                        data-active="{{ $user->IsActive ? 1 : 0 }}">
                                        Sửa
                                    </button>
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
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
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

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-user-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ tên</label>
                                <input type="text" name="FullName" id="edit-full-name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="Email" id="edit-email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="Password" class="form-control" placeholder="Để trống nếu không đổi">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nhập lại mật khẩu</label>
                                <input type="password" name="Password_confirmation" class="form-control" placeholder="Để trống nếu không đổi">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="Phone" id="edit-phone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" name="Address" id="edit-address" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vai trò</label>
                                <select name="RoleID" id="edit-role" class="form-select" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->RoleID }}">{{ $role->RoleName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="IsActive" id="edit-status" class="form-select" required>
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Bị khóa</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" name="AvatarFile" class="form-control" accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Để trống nếu không đổi ảnh</p>
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

    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body space-y-2">
                    <div><strong>ID:</strong> <span id="view-id"></span></div>
                    <div><strong>Họ tên:</strong> <span id="view-name"></span></div>
                    <div><strong>Email:</strong> <span id="view-email"></span></div>
                    <div><strong>Vai trò:</strong> <span id="view-role"></span></div>
                    <div><strong>Trạng thái:</strong> <span id="view-status"></span></div>
                    <div><strong>Ngày tạo:</strong> <span id="view-created"></span></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
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

            document.querySelectorAll('.edit-user-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const form = document.getElementById('edit-user-form');
                    form.action = button.dataset.action;
                    document.getElementById('edit-full-name').value = button.dataset.name || '';
                    document.getElementById('edit-email').value = button.dataset.email || '';
                    document.getElementById('edit-phone').value = button.dataset.phone || '';
                    document.getElementById('edit-address').value = button.dataset.address || '';
                    document.getElementById('edit-role').value = button.dataset.roleId || '';
                    document.getElementById('edit-status').value = button.dataset.active || '1';
                });
            });

            document.querySelectorAll('.view-user-btn').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('view-id').textContent = button.dataset.id || '';
                    document.getElementById('view-name').textContent = button.dataset.name || '';
                    document.getElementById('view-email').textContent = button.dataset.email || '';
                    document.getElementById('view-role').textContent = button.dataset.role || '';
                    document.getElementById('view-status').textContent = button.dataset.status || '';
                    document.getElementById('view-created').textContent = button.dataset.created || '';
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
