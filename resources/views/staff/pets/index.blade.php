@extends('layouts.staff')

@section('title', 'Quản lý Thú cưng - Nhân viên')

@push('styles')
    <!-- Tích hợp Bootstrap 5 cho phần nội dung này -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .staff-pet-card {
            border-radius: 1rem;
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }
        .staff-pet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .stat-card {
            border-radius: 0.75rem;
            padding: 1.25rem;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
        }
    </style>
@endpush

@section('header_title', 'Quản lý Thú cưng')

@section('content')
<div class="container-fluid py-2">
    <!-- Bootstrap Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark">Quản lý Thú cưng</h2>
            <p class="text-secondary mb-0">Theo dõi và chăm sóc thú cưng tại cửa hàng</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <form action="{{ route('staff.pets') }}" method="GET">
                <div class="input-group" style="max-width: 300px; margin-left: auto;">
                    <span class="input-group-text bg-white border-end-0">
                        <span class="material-symbols-outlined text-muted">search</span>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Tìm kiếm thú cưng..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-primary-subtle text-primary">
                        <span class="material-symbols-outlined">pets</span>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ sprintf('%02d', $currentlyCaring) }}</h4>
                        <small class="text-muted">Đang chăm sóc</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-success-subtle text-success">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ sprintf('%02d', $completedToday) }}</h4>
                        <small class="text-muted">Đã hoàn thành</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-warning-subtle text-warning">
                        <span class="material-symbols-outlined">schedule</span>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ sprintf('%02d', $pendingToday) }}</h4>
                        <small class="text-muted">Đang chờ</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card bg-gradient-primary h-100 border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-white bg-opacity-25 text-white">
                        <span class="material-symbols-outlined">today</span>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-white">{{ sprintf('%02d', $totalToday) }}</h4>
                        <small class="text-white text-opacity-75">Tổng lịch hôm nay</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pets Grid (Bootstrap Style) -->
    <div class="row g-4">
        @forelse($appointments as $appointment)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card staff-pet-card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-3 rounded-4 bg-light text-primary">
                                    <span class="material-symbols-outlined fs-2">pets</span>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $appointment->pet?->PetName ?? 'Thú cưng' }}</h5>
                                    <small class="text-muted">
                                        {{ $appointment->pet?->Species ?? 'N/A' }} • 
                                        {{ $appointment->pet?->Breed ?? 'Chưa rõ' }}
                                    </small>
                                </div>
                            </div>
                            <span class="badge rounded-pill 
                                @if($appointment->Status == 'pending') bg-warning-subtle text-warning-emphasis
                                @elseif($appointment->Status == 'confirmed') bg-info-subtle text-info-emphasis
                                @elseif($appointment->Status == 'in_progress') bg-primary-subtle text-primary-emphasis
                                @elseif($appointment->Status == 'completed') bg-success-subtle text-success-emphasis
                                @else bg-secondary-subtle text-secondary-emphasis @endif border">
                                {{ $appointment->Status == 'pending' ? 'Đang chờ' : ($appointment->Status == 'confirmed' ? 'Đã xác nhận' : ($appointment->Status == 'in_progress' ? 'Đang làm' : ($appointment->Status == 'completed' ? 'Hoàn thành' : $appointment->Status))) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-2 text-dark">
                                <span class="material-symbols-outlined fs-6 text-muted">person</span>
                                <span class="small fw-semibold">Khách hàng: {{ $appointment->customer?->FullName ?? 'Khách lẻ' }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2 text-dark">
                                <span class="material-symbols-outlined fs-6 text-muted">content_cut</span>
                                <span class="small">Dịch vụ: {{ $appointment->services->pluck('ServiceName')->implode(', ') }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-dark">
                                <span class="material-symbols-outlined fs-6 text-muted">schedule</span>
                                <span class="small">Thời gian: {{ $appointment->AppointmentTime->format('H:i') }}</span>
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-3 border-top">
                            @if($appointment->Status == 'pending' || $appointment->Status == 'confirmed')
                                <button class="btn btn-primary flex-grow-1 fw-semibold rounded-3">Bắt đầu</button>
                            @elseif($appointment->Status == 'in_progress')
                                <button class="btn btn-success flex-grow-1 fw-semibold rounded-3">Hoàn thành</button>
                            @else
                                <button class="btn btn-light flex-grow-1 fw-semibold rounded-3 disabled text-muted">Đã xong</button>
                            @endif
                            <button class="btn btn-outline-secondary rounded-3 px-3">
                                <span class="material-symbols-outlined fs-6 mt-1">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-4 border border-dashed text-muted">
                    <span class="material-symbols-outlined fs-1 mb-3">event_busy</span>
                    <h5 class="fw-bold">Không có thú cưng nào hôm nay</h5>
                    <p class="mb-0">Các lịch hẹn chăm sóc thực tế sẽ hiển thị ở đây.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush



