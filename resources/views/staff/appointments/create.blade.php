@extends('layouts.staff')

@section('title', 'Tạo lịch hẹn - Nhân viên')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('header_title', 'Tạo lịch hẹn mới')

@section('content')
<div class="container-fluid py-2">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark">Tạo lịch hẹn mới</h2>
            <p class="text-secondary mb-0">Đặt lịch hẹn dịch vụ cho thú cưng</p>
        </div>
    </div>

    <!-- Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-primary">edit_calendar</span>
                Thông tin lịch hẹn
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('staff.appointments.store') }}" method="POST">
                @csrf
                
                <!-- Customer & Pet Selection -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Khách hàng <span class="text-danger">*</span></label>
                        <select name="CustomerID" class="form-select @error('CustomerID') is-invalid @enderror" required>
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->UserID }}" {{ old('CustomerID') == $customer->UserID ? 'selected' : '' }}>
                                    {{ $customer->FullName }} - {{ $customer->Phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('CustomerID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Thú cưng <span class="text-danger">*</span></label>
                        <select name="PetID" class="form-select @error('PetID') is-invalid @enderror" required>
                            <option value="">-- Chọn thú cưng --</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->PetID }}" {{ old('PetID') == $pet->PetID ? 'selected' : '' }}>
                                    {{ $pet->PetName }} ({{ $pet->Species }}) - Chủ: {{ $pet->owner->FullName }}
                                </option>
                            @endforeach
                        </select>
                        @error('PetID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Services & DateTime -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Dịch vụ <span class="text-danger">*</span></label>
                        <div class="border rounded-3 p-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach($services as $service)
                                <div class="form-check mb-2 p-3 border rounded-2 @error('services') border-danger @enderror">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="{{ $service->ServiceID }}" id="service{{ $service->ServiceID }}" {{ in_array($service->ServiceID, old('services', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-start w-100" for="service{{ $service->ServiceID }}">
                                        <span class="material-symbols-outlined text-primary me-2">spa</span>
                                        <div class="flex-grow-1">
                                            <p class="mb-0 fw-semibold">{{ $service->ServiceName }}</p>
                                            <small class="text-muted">{{ number_format($service->BasePrice) }}đ - {{ $service->Duration }} phút</small>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('services')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ngày hẹn <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date', date('Y-m-d')) }}" required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Giờ hẹn <span class="text-danger">*</span></label>
                            <select name="appointment_time" class="form-select @error('appointment_time') is-invalid @enderror" required>
                                <option value="">-- Chọn giờ --</option>
                                @for($hour = 8; $hour <= 17; $hour++)
                                    <option value="{{ sprintf('%02d:00', $hour) }}" {{ old('appointment_time') == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                        {{ sprintf('%02d:00', $hour) }}
                                    </option>
                                @endfor
                            </select>
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Ghi chú</label>
                    <textarea name="notes" rows="4" class="form-control" placeholder="Nhập ghi chú hoặc yêu cầu đặc biệt...">{{ old('notes') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                    <a href="{{ route('staff.pets') }}" class="btn btn-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <span class="material-symbols-outlined align-middle fs-6">check</span>
                        Tạo lịch hẹn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush


