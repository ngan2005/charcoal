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
                    <p class="text-sm text-gray-500">Ngày hẹn: {{ $appointment->AppointmentTime->format('d/m/Y H:i') }} — Chỉ xem, nhân viên thao tác xác nhận</p>
                </div>
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

        <!-- Staff (chỉ xem) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-success">badge</span>
                Nhân viên phụ trách
            </h5>
            @if($appointment->staff)
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <span class="material-symbols-outlined text-success" style="font-size: 24px;">person</span>
                    </div>
                    <div>
                        <div class="fw-bold">{{ $appointment->staff->FullName }}</div>
                        <small class="text-muted">{{ $appointment->staff->Email ?? '' }}</small>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">Chưa phân công</p>
            @endif
        </div>

        <!-- Services (chỉ xem) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-info">content_cut</span>
                    Dịch vụ đã đặt
                </h5>
            </div>
            <table class="table table-hover mb-0">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600">STT</th>
                        <th class="px-4 py-3 text-left text-gray-600">Dịch vụ</th>
                        <th class="px-4 py-3 text-left text-gray-600">Giá cơ bản</th>
                        <th class="px-4 py-3 text-left text-gray-600">Giá thành viên</th>
                        <th class="px-4 py-3 text-left text-gray-600">Thời gian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointment->services as $index => $service)
                        <tr>
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="material-symbols-outlined text-info">content_cut</span>
                                    <span class="fw-medium">{{ $service->ServiceName }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ number_format($service->BasePrice, 0, ',', '.') }} đ</td>
                            <td class="px-4 py-3 text-success">{{ number_format($service->MemberPrice ?? $service->BasePrice, 0, ',', '.') }} đ</td>
                            <td class="px-4 py-3">{{ $service->Duration ?? '--' }} phút</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Chưa có dịch vụ nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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

















