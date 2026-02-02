@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Hồ sơ cá nhân</h1>
                <p class="text-sm text-gray-500">Quản lý thông tin tài khoản của bạn</p>
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

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Profile Header with Avatar -->
            <div class="bg-primary bg-opacity-10 p-6">
                <div class="d-flex align-items-center gap-4">
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
                    <div class="position-relative">
                        <img
                            class="rounded-circle object-fit-cover border border-white border-4 shadow"
                            src="{{ $avatarUrl }}"
                            onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->FullName) }}&background=E2E8F0&color=1F2937&size=128'"
                            alt="{{ $user->FullName }}"
                            width="80" height="80"
                            style="background: white;">
                        {{-- Tạm đóng để sửa lỗi sau
                        <label for="avatar-input" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 28px; height: 28px; cursor: pointer;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">photo_camera</span>
                        </label>
                        <input type="file" id="avatar-input" name="AvatarFile" class="d-none" accept="image/*" onchange="this.form.submit()">
                        --}}
                    </div>
                    <div>
                        <h2 class="fw-bold fs-4 text-gray-900 mb-1">{{ $user->FullName }}</h2>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary">
                                <span class="material-symbols-outlined me-1 align-middle" style="font-size: 14px;">person</span>
                                {{ $user->role?->RoleName ?? 'N/A' }}
                            </span>
                            @if ($user->IsActive)
                                <span class="badge bg-success">
                                    <span class="material-symbols-outlined me-1 align-middle" style="font-size: 14px;">check_circle</span>
                                    Hoạt động
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <span class="material-symbols-outlined me-1 align-middle" style="font-size: 14px;">cancel</span>
                                    Bị khóa
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Thông tin cá nhân -->
                    <div class="col-md-6">
                        <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                            <span class="material-symbols-outlined me-2">person</span>
                            Thông tin cá nhân
                        </h6>
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Họ tên</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 rounded-start-4">
                                            <span class="material-symbols-outlined text-primary">badge</span>
                                        </span>
                                        <input type="text" name="FullName" class="form-control border-0 bg-light rounded-end-4 focus-shadow" value="{{ old('FullName', $user->FullName) }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 rounded-start-4">
                                            <span class="material-symbols-outlined text-success">mail</span>
                                        </span>
                                        <input type="email" name="Email" class="form-control border-0 bg-light rounded-end-4 focus-shadow" value="{{ old('Email', $user->Email) }}" required>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label text-muted small mb-1">Số điện thoại</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 rounded-start-4">
                                            <span class="material-symbols-outlined text-info">phone</span>
                                        </span>
                                        <input type="text" name="Phone" class="form-control border-0 bg-light rounded-end-4 focus-shadow" value="{{ old('Phone', $user->Phone) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin liên hệ -->
                    <div class="col-md-6">
                        <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                            <span class="material-symbols-outlined me-2">contact_mail</span>
                            Thông tin liên hệ
                        </h6>
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Địa chỉ</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 rounded-start-4">
                                            <span class="material-symbols-outlined text-warning">location_on</span>
                                        </span>
                                        <input type="text" name="Address" class="form-control border-0 bg-light rounded-end-4 focus-shadow" value="{{ old('Address', $user->Address) }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Ngày tham gia</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 rounded-start-4">
                                            <span class="material-symbols-outlined text-secondary">calendar_today</span>
                                        </span>
                                        <input type="text" class="form-control border-0 bg-light rounded-end-4" value="{{ $user->CreatedAt ? $user->CreatedAt->format('d/m/Y H:i') : '--' }}" disabled>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label text-muted small mb-1">Đăng nhập gần nhất</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 rounded-start-4">
                                            <span class="material-symbols-outlined text-secondary">schedule</span>
                                        </span>
                                        <input type="text" class="form-control border-0 bg-light rounded-end-4" value="{{ $user->LastLogin ? $user->LastLogin->format('d/m/Y H:i') : 'Chưa đăng nhập' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Đổi mật khẩu -->
                    <div class="col-12">
                        <h6 class="fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
                            <span class="material-symbols-outlined me-2">lock</span>
                            Đổi mật khẩu
                        </h6>
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Mật khẩu mới</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0 rounded-start-4">
                                                <span class="material-symbols-outlined text-primary">key</span>
                                            </span>
                                            <input type="password" name="Password" class="form-control border-0 bg-light rounded-end-4 focus-shadow" placeholder="Để trống nếu không đổi">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Nhập lại mật khẩu</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0 rounded-start-4">
                                                <span class="material-symbols-outlined text-success">key</span>
                                            </span>
                                            <input type="password" name="Password_confirmation" class="form-control border-0 bg-light rounded-end-4 focus-shadow" placeholder="Để trống nếu không đổi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <button type="submit" class="btn btn-primary px-5 py-2.5 fw-bold d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined">save</span>
                                Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .focus-shadow:focus {
        box-shadow: 0 0 0 2px rgba(255, 159, 28, 0.2) !important;
    }
    .input-group-text {
        padding: 0.75rem 1rem;
    }
    .form-control, .form-select {
        padding: 0.75rem 1rem;
    }
    .rounded-start-4 {
        border-top-left-radius: 1rem !important;
        border-bottom-left-radius: 1rem !important;
    }
    .rounded-end-4 {
        border-top-right-radius: 1rem !important;
        border-bottom-right-radius: 1rem !important;
    }
</style>
@endpush
