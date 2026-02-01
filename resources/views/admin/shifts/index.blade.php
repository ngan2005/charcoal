@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Phân công ca làm việc</h1>
                <p class="text-sm text-gray-500">Quản lý lịch làm việc của nhân viên</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.shifts.export', ['week_start' => $filters['week_start'] ?? date('Y-m-d', strtotime('monday this week'))]) }}" class="btn btn-success d-flex align-items-center gap-2" target="_blank">
                    <span class="material-symbols-outlined">download</span>
                    Xuất Excel
                </a>
                <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createShiftModal">
                    <span class="material-symbols-outlined">add</span>
                    Thêm ca làm
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="material-symbols-outlined">error</span>
                    <span class="fw-bold">Có lỗi xảy ra</span>
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('admin.shifts.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small mb-1">Nhân viên</label>
                    <select name="staff_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả nhân viên</option>
                        @foreach ($staffMembers as $staff)
                            <option value="{{ $staff->UserID }}" @selected((int)$filters['staff_id'] === $staff->UserID)>
                                {{ $staff->FullName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small mb-1">Ngày</label>
                    <input type="date" name="date" class="form-control" value="{{ $filters['date'] }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline-secondary flex-fill">
                        <span class="material-symbols-outlined me-1 align-middle" style="font-size: 18px;">filter_alt_off</span>
                        Xóa lọc
                    </a>
                </div>
            </form>
        </div>

        <!-- Week Navigation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('admin.shifts.index', ['week_start' => date('Y-m-d', strtotime($filters['week_start'] . ' -7 days'))]) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined">chevron_left</span>
                    Tuần trước
                </a>
                <h5 class="mb-0 fw-bold text-primary">
                    <span class="material-symbols-outlined me-2 align-middle">calendar_month</span>
                    {{ date('d/m/Y', strtotime($filters['week_start'])) }} - {{ date('d/m/Y', strtotime($filters['week_start'] . ' +6 days')) }}
                </h5>
                <a href="{{ route('admin.shifts.index', ['week_start' => date('Y-m-d', strtotime($filters['week_start'] . ' +7 days'))]) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                    Tuần sau
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>
        </div>

        <!-- Shifts Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Ngày</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Nhân viên</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Ca làm</th>
                        <th class="px-4 py-3 text-left text-gray-600 fw-semibold">Trạng thái</th>
                        <th class="px-4 py-3 text-right text-gray-600 fw-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($shifts as $shift)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">calendar_today</span>
                                    <span class="fw-medium">{{ date('d/m/Y', strtotime($shift->StartTime)) }}</span>
                                </div>
                                <small class="text-muted">{{ date('l', strtotime($shift->StartTime)) }}</small>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    @php
                                        $avatarPath = str_replace('\\', '/', (string) $shift->staff_avatar);
                                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($shift->staff_name) . '&background=E2E8F0&color=1F2937&size=32';
                                        if ($avatarPath) {
                                            if (preg_match('/^https?:\/\//', $avatarPath)) {
                                                $avatarUrl = $avatarPath;
                                            } elseif (str_starts_with($avatarPath, '/storage') || str_starts_with($avatarPath, 'storage/')) {
                                                $avatarUrl = asset($avatarPath);
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $avatarUrl }}" class="rounded-circle" width="32" height="32" alt="">
                                    <div>
                                        <div class="fw-medium">{{ $shift->staff_name }}</div>
                                        <small class="text-muted">{{ $shift->staff_email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="material-symbols-outlined text-success">schedule</span>
                                    <span>{{ date('H:i', strtotime($shift->StartTime)) }} - {{ date('H:i', strtotime($shift->EndTime)) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($shift->ShiftStatusID == 1)
                                    <span class="badge bg-success d-flex align-items-center gap-1" style="width: fit-content;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">check_circle</span>
                                        {{ $shift->ShiftStatusName }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary d-flex align-items-center gap-1" style="width: fit-content;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">cancel</span>
                                        {{ $shift->ShiftStatusName }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-outline-primary btn-sm edit-shift-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editShiftModal"
                                        data-shift-id="{{ $shift->ShiftID }}"
                                        data-staff-id="{{ $shift->StaffID }}"
                                        data-shift-date="{{ date('Y-m-d', strtotime($shift->StartTime)) }}"
                                        data-start-time="{{ date('H:i', strtotime($shift->StartTime)) }}"
                                        data-end-time="{{ date('H:i', strtotime($shift->EndTime)) }}"
                                        data-status-id="{{ $shift->ShiftStatusID }}">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                    </button>
                                    <form action="{{ route('admin.shifts.destroy', $shift->ShiftID) }}" method="POST" data-confirm class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="material-symbols-outlined mb-2" style="font-size: 48px; opacity: 0.5;">event_busy</span>
                                    <p class="mb-0">Chưa có ca làm nào được phân công</p>
                                    <small class="text-muted">Nhấn "Thêm ca làm" để bắt đầu</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Shift Modal -->
    <div class="modal fade" id="createShiftModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined">add_task</span>
                        Thêm ca làm việc
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="create-shift-form" method="POST" action="{{ route('admin.shifts.store') }}">
                        @csrf
                        <div class="row g-4">
                            <!-- Chọn nhân viên -->
                            <div class="col-12">
                                <label class="form-label fw-semibold text-gray-700">Nhân viên <span class="text-danger">*</span></label>
                                <select name="StaffID" id="create-StaffID" class="form-select form-select-lg" required onchange="loadStaffSuggestions(this.value, 'create')">
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->UserID }}">{{ $staff->FullName }} ({{ $staff->role?->RoleName ?? 'Nhân viên' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Staff Suggestions (ẩn mặc định, hiện khi chọn nhân viên) -->
                            <div class="col-12" id="create-staff-suggestions" style="display: none;">
                                <div class="alert alert-info d-flex align-items-start gap-3">
                                    <img id="create-staff-avatar" src="" class="rounded-circle" width="48" height="48" alt="">
                                    <div>
                                        <h6 class="mb-1" id="create-staff-name"></h6>
                                        <div class="d-flex gap-3 small text-muted">
                                            <span><i class="material-symbols-outlined align-middle" style="font-size: 16px;">work</i> <span id="create-staff-position"></span></span>
                                            <span><i class="material-symbols-outlined align-middle" style="font-size: 16px;">star</i> <span id="create-staff-rating"></span></span>
                                        </div>
                                        <div class="mt-2 d-flex gap-3">
                                            <small class="text-primary"><span id="create-staff-total-shifts">0</span> ca đã làm</small>
                                            <small class="text-success"><span id="create-staff-week-shifts">0</span> ca tuần này</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ngày làm việc -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-gray-700">Ngày làm việc <span class="text-danger">*</span></label>
                                <input type="date" name="ShiftDate" class="form-control form-control-lg" required min="{{ date('Y-m-d') }}">
                            </div>

                            <!-- Trạng thái -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-gray-700">Trạng thái <span class="text-danger">*</span></label>
                                <select name="ShiftStatusID" class="form-select form-select-lg" required>
                                    @foreach($shiftStatuses as $status)
                                        <option value="{{ $status->ShiftStatusID }}" @selected($status->ShiftStatusID == 1)>
                                            {{ $status->ShiftStatusName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Giờ bắt đầu -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-gray-700">Giờ bắt đầu <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-success">play_circle</span></span>
                                    <input type="time" name="StartTime" class="form-control" required>
                                </div>
                            </div>

                            <!-- Giờ kết thúc -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-gray-700">Giờ kết thúc <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-danger">stop_circle</span></span>
                                    <input type="time" name="EndTime" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" form="create-shift-form" class="btn btn-primary px-5 fw-bold">
                        <span class="material-symbols-outlined me-1 align-middle">add</span>
                        Thêm ca làm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Shift Modal -->
    <div class="modal fade" id="editShiftModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined">edit</span>
                        Chỉnh sửa ca làm việc
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="edit-shift-form" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <!-- Chọn nhân viên -->
                            <div class="col-12">
                                <label class="form-label fw-semibold text-gray-700">Nhân viên <span class="text-danger">*</span></label>
                                <select name="StaffID" id="edit-StaffID" class="form-select form-select-lg" required onchange="loadStaffSuggestions(this.value, 'edit')">
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->UserID }}">{{ $staff->FullName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Staff Suggestions (ẩn mặc định, hiện khi chọn nhân viên) -->
                            <div class="col-12" id="edit-staff-suggestions" style="display: none;">
                                <div class="alert alert-info d-flex align-items-start gap-3">
                                    <img id="edit-staff-avatar" src="" class="rounded-circle" width="48" height="48" alt="">
                                    <div>
                                        <h6 class="mb-1" id="edit-staff-name"></h6>
                                        <div class="d-flex gap-3 small text-muted">
                                            <span><i class="material-symbols-outlined align-middle" style="font-size: 16px;">work</i> <span id="edit-staff-position"></span></span>
                                            <span><i class="material-symbols-outlined align-middle" style="font-size: 16px;">star</i> <span id="edit-staff-rating"></span></span>
                                        </div>
                                        <div class="mt-2 d-flex gap-3">
                                            <small class="text-primary"><span id="edit-staff-total-shifts">0</span> ca đã làm</small>
                                            <small class="text-success"><span id="edit-staff-week-shifts">0</span> ca tuần này</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ngày làm việc -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-gray-700">Ngày làm việc <span class="text-danger">*</span></label>
                                <input type="date" name="ShiftDate" id="edit-ShiftDate" class="form-control form-control-lg" required>
                            </div>

                            <!-- Trạng thái -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-gray-700">Trạng thái <span class="text-danger">*</span></label>
                                <select name="ShiftStatusID" id="edit-ShiftStatusID" class="form-select form-select-lg" required>
                                    @foreach($shiftStatuses as $status)
                                        <option value="{{ $status->ShiftStatusID }}">{{ $status->ShiftStatusName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Giờ bắt đầu -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-gray-700">Giờ bắt đầu <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-success">play_circle</span></span>
                                    <input type="time" name="StartTime" id="edit-StartTime" class="form-control" required>
                                </div>
                            </div>

                            <!-- Giờ kết thúc -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-gray-700">Giờ kết thúc <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0"><span class="material-symbols-outlined text-danger">stop_circle</span></span>
                                    <input type="time" name="EndTime" id="edit-EndTime" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" form="edit-shift-form" class="btn btn-primary px-5 fw-bold">
                        <span class="material-symbols-outlined me-1 align-middle">save</span>
                        Lưu thay đổi
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
        function formatAvatarUrl(avatarPath, name) {
            if (!avatarPath) {
                return 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) + '&background=E2E8F0&color=1F2937&size=128';
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

        // Load staff suggestions
        async function loadStaffSuggestions(staffId, prefix) {
            if (!staffId) {
                document.getElementById(prefix + '-staff-suggestions').style.display = 'none';
                return;
            }

            try {
                const response = await fetch(`/admin/shifts/staff/${staffId}/suggestions`);
                const data = await response.json();

                if (response.ok) {
                    // Update avatar
                    const avatarUrl = formatAvatarUrl(data.staff.Avatar, data.staff.FullName);
                    document.getElementById(prefix + '-staff-avatar').src = avatarUrl;
                    document.getElementById(prefix + '-staff-avatar').onerror = function() {
                        this.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.staff.FullName) + '&background=E2E8F0&color=1F2937&size=128';
                    };

                    // Update info
                    document.getElementById(prefix + '-staff-name').textContent = data.staff.FullName;
                    document.getElementById(prefix + '-staff-position').textContent = data.staff.Position || 'Chưa cập nhật';
                    document.getElementById(prefix + '-staff-rating').textContent = data.staff.Rating ? data.staff.Rating + '/5' : 'Chưa có';
                    document.getElementById(prefix + '-staff-total-shifts').textContent = data.stats.total_shifts;
                    document.getElementById(prefix + '-staff-week-shifts').textContent = data.stats.this_week_shifts;

                    // Show suggestions
                    document.getElementById(prefix + '-staff-suggestions').style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading staff suggestions:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Confirm delete
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
                    const result = await confirmWithSwal('Xóa ca làm?', 'Thao tác này không thể hoàn tác.');
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Edit shift button click
            document.querySelectorAll('.edit-shift-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const form = document.getElementById('edit-shift-form');
                    form.action = `/admin/shifts/${button.dataset.shiftId}`;

                    document.getElementById('edit-ShiftID').value = button.dataset.shiftId;
                    document.getElementById('edit-StaffID').value = button.dataset.staffId;
                    document.getElementById('edit-ShiftDate').value = button.dataset.shiftDate;
                    document.getElementById('edit-StartTime').value = button.dataset.startTime;
                    document.getElementById('edit-EndTime').value = button.dataset.endTime;
                    document.getElementById('edit-ShiftStatusID').value = button.dataset.statusId;

                    // Load staff suggestions
                    loadStaffSuggestions(button.dataset.staffId, 'edit');
                });
            });

            // Success message
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

