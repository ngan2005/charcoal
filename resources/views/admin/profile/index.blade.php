@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hồ sơ cá nhân</h1>
            <p class="text-sm text-gray-500">Cập nhật thông tin tài khoản đang đăng nhập</p>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-4">
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
                                class="h-16 w-16 rounded-full border border-gray-200"
                                src="{{ $avatarUrl ?? ('https://ui-avatars.com/api/?name=' . urlencode($user->FullName) . '&background=E2E8F0&color=1F2937') }}"
                                onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->FullName) }}&background=E2E8F0&color=1F2937';"
                                alt="{{ $user->FullName }}">
                            <div>
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" name="AvatarFile" class="form-control" accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Dung lượng tối đa 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="FullName" class="form-control" value="{{ old('FullName', $user->FullName) }}" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="Email" class="form-control" value="{{ old('Email', $user->Email) }}" required>
                    </div>
                    <div>
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="Phone" class="form-control" value="{{ old('Phone', $user->Phone) }}">
                    </div>
                    <div>
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="Address" class="form-control" value="{{ old('Address', $user->Address) }}">
                    </div>
                    <div>
                        <label class="form-label">Vai trò</label>
                        <input type="text" class="form-control" value="{{ $user->role?->RoleName ?? 'N/A' }}" disabled>
                    </div>
                    <div>
                        <label class="form-label">Trạng thái</label>
                        <input type="text" class="form-control" value="{{ $user->IsActive ? 'Hoạt động' : 'Bị khóa' }}" disabled>
                    </div>
                    <div>
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="Password" class="form-control" placeholder="Để trống nếu không đổi">
                    </div>
                    <div>
                        <label class="form-label">Nhập lại mật khẩu</label>
                        <input type="password" name="Password_confirmation" class="form-control" placeholder="Để trống nếu không đổi">
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
