@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Lịch hẹn #{{ $appointment->AppointmentID }}</h1>
                    <p class="text-sm text-gray-500">Ngày hẹn: {{ $appointment->AppointmentTime->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                @switch($appointment->Status)
                    @case('pending')
                        <a href="{{ route('admin.appointments.update-status', $appointment->AppointmentID) }}?Status=confirmed" class="btn btn-success">
                            <span class="material-symbols-outlined me-1 align-middle">check</span>
                            Xác nhận
                        </a>
                        @break
                    @case('confirmed')
                        <a href="{{ route('admin.appointments.update-status', $appointment->AppointmentID) }}?Status=in_progress" class="btn btn-primary">
                            <span class="material-symbols-outlined me-1 align-middle">play_arrow</span>
                            Bắt đầu
                        </a>
                        @break
                    @case('in_progress')
                        <a href="{{ route('admin.appointments.update-status', $appointment->AppointmentID) }}?Status=completed" class="btn btn-success">
                            <span class="material-symbols-outlined me-1 align-middle">check_circle</span>
                            Hoàn thành
                        </a>
                        @break
                @endswitch
                @if(!in_array($appointment->Status, ['cancelled', 'completed', 'no_show']))
                    <a href="{{ route('admin.appointments.update-status', $appointment->AppointmentID) }}?Status=cancelled" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn hủy lịch hẹn này?')">
                        <span class="material-symbols-outlined me-1 align-middle">cancel</span>
                        Hủy lịch
                    </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- Appointment Info -->
            <div class="col-lg-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">event</span>
                        Thông tin lịch hẹn
                    </h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Mã lịch</span>
                            <span class="fw-bold">#{{ $appointment->AppointmentID }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Ngày hẹn</span>
                            <span>{{ $appointment->AppointmentTime->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Giờ hẹn</span>
                            <span>{{ $appointment->AppointmentTime->format('H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Trạng thái</span>
                            @switch($appointment->Status)
                                @case('pending')
                                    <span class="badge bg-warning">Chờ xác nhận</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-info">Đã xác nhận</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-primary">Đang thực hiện</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success">Hoàn thành</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                    @break
                                @case('no_show')
                                    <span class="badge bg-secondary">Vắng mặt</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="col-lg-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Thông tin khách hàng
                    </h5>
                    @if($appointment->customer)
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <span class="material-symbols-outlined text-primary" style="font-size: 24px;">person</span>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $appointment->customer->FullName }}</div>
                                    <small class="text-muted">{{ $appointment->customer->Email }}</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Điện thoại</span>
                                <span>{{ $appointment->customer->Phone ?? '--' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Địa chỉ</span>
                                <span class="text-end" style="max-width: 200px;">{{ $appointment->customer->Address ?? '--' }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Không có thông tin khách hàng</p>
                    @endif
                </div>
            </div>

            <!-- Pet Info -->
            <div class="col-lg-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-warning">pets</span>
                        Thông tin thú cưng
                    </h5>
                    @if($appointment->pet)
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <span class="material-symbols-outlined text-warning" style="font-size: 24px;">pets</span>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $appointment->pet->PetName }}</div>
                                    <small class="text-muted">{{ $appointment->pet->Species }} - {{ $appointment->pet->Breed }}</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Kích thước</span>
                                <span>{{ $appointment->pet->Size ?? '--' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Tuổi</span>
                                <span>{{ $appointment->pet->Age ? $appointment->pet->Age . ' tuổi' : '--' }}</span>
                            </div>
                            @if($appointment->pet->Notes)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Ghi chú</span>
                                <span class="text-end" style="max-width: 200px;">{{ $appointment->pet->Notes }}</span>
                            </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Không có thông tin thú cưng</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Staff Assignment -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-success">badge</span>
                    Phân công nhân viên
                </h5>
                <button class="btn btn-outline-primary btn-sm" onclick="loadStaffSuggestions()">
                    <span class="material-symbols-outlined me-1 align-middle" style="font-size: 16px;">refresh</span>
                    Gợi ý nhân viên
                </button>
            </div>
            <form action="{{ route('admin.appointments.assign-staff', $appointment->AppointmentID) }}" method="POST" class="d-flex gap-3 align-items-end">
                @csrf
                <div class="flex-grow-1">
                    <label class="form-label text-muted small mb-1">Chọn nhân viên phụ trách</label>
                    <select name="StaffID" class="form-select" required>
                        <option value="">-- Chọn nhân viên --</option>
                        @php
                            $staffs = \App\Models\User::where('RoleID', 2)->where('IsActive', 1)->get();
                        @endphp
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->UserID }}" @selected($appointment->StaffID == $staff->UserID)>
                                {{ $staff->FullName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined me-1 align-middle">assignment_ind</span>
                    Phân công
                </button>
            </form>
            <div id="staff-suggestions" class="mt-3" style="display: none;">
                <!-- Staff suggestions will be loaded here -->
            </div>
        </div>

        <!-- Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-info">content_cut</span>
                    Dịch vụ đã đặt
                </h5>
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                    <span class="material-symbols-outlined me-1 align-middle" style="font-size: 16px;">add</span>
                    Thêm dịch vụ
                </button>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600">STT</th>
                        <th class="px-4 py-3 text-left text-gray-600">Dịch vụ</th>
                        <th class="px-4 py-3 text-left text-gray-600">Giá cơ bản</th>
                        <th class="px-4 py-3 text-left text-gray-600">Giá thành viên</th>
                        <th class="px-4 py-3 text-left text-gray-600">Thời gian</th>
                        <th class="px-4 py-3 text-right text-gray-600">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointment->services as $index => $service)
                        <tr>
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="material-symbols-outlined text-info">content_cut</span>
                                    <span class="fw-medium">{{ $service->service->ServiceName }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ number_format($service->service->BasePrice, 0, ',', '.') }} đ</td>
                            <td class="px-4 py-3 text-success">{{ number_format($service->service->MemberPrice, 0, ',', '.') }} đ</td>
                            <td class="px-4 py-3">{{ $service->service->Duration }} phút</td>
                            <td class="px-4 py-3 text-end">
                                <a href="{{ route('admin.appointments.remove-service', [$appointment->AppointmentID, $service->ServiceID]) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Xóa dịch vụ này?')">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Chưa có dịch vụ nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined">add_circle</span>
                        Thêm dịch vụ
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.appointments.add-service', $appointment->AppointmentID) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-gray-700">Chọn dịch vụ <span class="text-danger">*</span></label>
                            <select name="ServiceID" class="form-select form-select-lg" required>
                                <option value="">-- Chọn dịch vụ --</option>
                                @foreach($allServices as $service)
                                    <option value="{{ $service->ServiceID }}">{{ $service->ServiceName }} ({{ number_format($service->BasePrice, 0, ',', '.') }} đ)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="material-symbols-outlined me-1 align-middle">add</span>
                                Thêm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function loadStaffSuggestions() {
            const container = document.getElementById('staff-suggestions');
            container.style.display = 'block';
            container.innerHTML = '<div class="text-center py-3"><span class="material-symbols-outlined animate-spin">sync</span> Đang tải...</div>';
            try {
                const response = await fetch(`/admin/appointments/{{ $appointment->AppointmentID }}/staff-suggestions`);
                const data = await response.json();
                if (response.ok) {
                    if (data.staff.length > 0) {
                        container.innerHTML = `
                            <div class="alert alert-info d-flex align-items-start gap-3">
                                <div>
                                    <h6 class="mb-2">Nhân viên gợi ý:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        ${data.staff.map(s => `
                                            <span class="badge bg-light text-dark border">${s.FullName} ${s.Position ? '- ' + s.Position : ''}</span>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        container.innerHTML = '<div class="alert alert-warning">Không có nhân viên gợi ý</div>';
                    }
                } else {
                    container.innerHTML = '<div class="alert alert-danger">Lỗi khi tải dữ liệu</div>';
                }
            } catch (error) {
                container.innerHTML = '<div class="alert alert-danger">Lỗi kết nối</div>';
            }
        }

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












