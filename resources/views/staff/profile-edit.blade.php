@extends('layouts.staff')

@section('header_title', 'Chỉnh sửa hồ sơ')
@section('header_subtitle', 'Cập nhật thông tin cá nhân')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-[#111827] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
        <form action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar Upload -->
            <div class="flex flex-col items-center gap-4">
                <div class="relative group">
                    @if($user->Avatar)
                        <img id="avatar-preview" src="{{ Str::startsWith($user->Avatar, 'http') ? $user->Avatar : asset('storage/' . $user->Avatar) }}" alt="{{ $user->FullName }}" class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md">
                    @else
                        <img id="avatar-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->FullName ?? 'User') }}&background=fce7f3&color=ec4899&size=128" alt="{{ $user->FullName }}" class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md">
                    @endif
                    
                    <label for="avatar-upload" class="absolute bottom-0 right-0 p-2 bg-primary text-white rounded-full cursor-pointer hover:bg-primary-hover transition-colors shadow-lg">
                        <span class="material-symbols-outlined text-xl">photo_camera</span>
                    </label>
                    <input type="file" id="avatar-upload" name="AvatarFile" class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>
                <p class="text-xs text-gray-500">Nhấn vào icon máy ảnh để thay đổi ảnh đại diện</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                <!-- Full Name -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Họ và tên <span class="text-red-500">*</span></label>
                    <input type="text" name="FullName" value="{{ old('FullName', $user->FullName) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-sm transition-all" required>
                    @error('FullName') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Số điện thoại</label>
                    <input type="text" name="Phone" value="{{ old('Phone', $user->Phone) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-sm transition-all">
                    @error('Phone') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Address -->
                <div class="col-span-1 md:col-span-2 space-y-2">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Địa chỉ</label>
                    <input type="text" name="Address" value="{{ old('Address', $user->Address) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-sm transition-all">
                    @error('Address') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Password Change Section -->
                <div class="col-span-1 md:col-span-2 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">lock_reset</span>
                        Đổi mật khẩu (Bỏ trống nếu không đổi)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Mật khẩu mới</label>
                            <input type="password" name="NewPassword" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-sm transition-all">
                            @error('NewPassword') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Xác nhận mật khẩu mới</label>
                            <input type="password" name="NewPassword_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-sm transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Errors -->
            @if ($errors->any())
                <div class="p-4 bg-red-50 text-red-600 rounded-xl text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('staff.profile') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl font-medium hover:bg-primary-hover transition-colors shadow-lg shadow-primary/30 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
