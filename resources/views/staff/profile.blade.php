@extends('layouts.staff')

@section('header_title', 'Hồ sơ cá nhân')
@section('header_subtitle', 'Quản lý thông tin cá nhân')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <!-- Profile Header -->
    <!-- Profile Header -->
    <div class="">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-center gap-6">
                <!-- Avatar -->
                <div class="relative">
                    @if($user->Avatar)
                        <img src="{{ Str::startsWith($user->Avatar, 'http') ? $user->Avatar : asset('storage/' . $user->Avatar) }}" alt="{{ $user->FullName }}" class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-sm">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->FullName ?? 'User') }}&background=fce7f3&color=ec4899&size=128" alt="{{ $user->FullName }}" class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-sm">
                    @endif
                    <div class="absolute bottom-1 right-1 w-5 h-5 rounded-full border-2 border-white dark:border-gray-800 {{ session('is_working') ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                </div>
                
                <!-- Info -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $user->FullName }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        {{ $user->staffProfile->Position ?? 'Nhân viên' }} - Pink Charcoal Pet Shop
                    </p>
                    <div class="flex items-center gap-2 mt-3">
                        @php($isWorking = session('is_working', false))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $isWorking ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $isWorking ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                            {{ $isWorking ? 'Đang làm việc' : 'Đang nghỉ' }}
                        </span>
                        
                        @if($user->staffProfile && $user->staffProfile->workStatus)
                             <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                {{ $user->staffProfile->workStatus->WorkStatusName }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <a href="{{ route('staff.profile.edit') }}" class="px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-medium hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Chỉnh sửa hồ sơ
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <h3 class="font-bold text-gray-900">Thông tin cá nhân</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-4 p-3 rounded-lg bg-gray-50">
                            <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-400">badge</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Họ và tên</p>
                                <p class="font-semibold text-gray-900">{{ auth()->user()->FullName ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-3 rounded-lg bg-gray-50">
                            <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-400">email</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="font-semibold text-gray-900">{{ auth()->user()->Email ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-3 rounded-lg bg-gray-50">
                            <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-400">phone</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Số điện thoại</p>
                                <p class="font-semibold text-gray-900">{{ auth()->user()->Phone ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-3 rounded-lg bg-gray-50">
                            <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                                <span class="material-symbols-outlined text-gray-400">cake</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Ngày sinh</p>
                                <p class="font-semibold text-gray-900">{{ auth()->user()->DateOfBirth ? auth()->user()->DateOfBirth->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Stats -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">work</span>
                    </div>
                    <h3 class="font-bold text-gray-900">Thông tin công việc</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 rounded-xl bg-pink-50">
                            <p class="text-2xl font-bold text-pink-600">20</p>
                            <p class="text-xs text-gray-500 mt-1">Tổng ca làm</p>
                        </div>
                        <div class="text-center p-4 rounded-xl bg-green-50">
                            <p class="text-2xl font-bold text-green-600">180h</p>
                            <p class="text-xs text-gray-500 mt-1">Giờ làm việc</p>
                        </div>
                        <div class="text-center p-4 rounded-xl bg-blue-50">
                            <p class="text-2xl font-bold text-blue-600">4.8</p>
                            <p class="text-xs text-gray-500 mt-1">Đánh giá TB</p>
                        </div>
                        <div class="text-center p-4 rounded-xl bg-amber-50">
                            <p class="text-2xl font-bold text-amber-600">5</p>
                            <p class="text-xs text-gray-500 mt-1">Năm kinh nghiệm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <h3 class="font-bold text-gray-900 mb-4">Thống kê nhanh</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">hourglass_empty</span>
                            </div>
                            <span class="text-sm text-gray-600">Chờ xác nhận</span>
                        </div>
                        <span class="font-bold text-gray-900">3</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                            </div>
                            <span class="text-sm text-gray-600">Đã xác nhận</span>
                        </div>
                        <span class="font-bold text-gray-900">5</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">task_alt</span>
                            </div>
                            <span class="text-sm text-gray-600">Hoàn thành</span>
                        </div>
                        <span class="font-bold text-gray-900">12</span>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-gradient-to-br from-pink-50 to-white rounded-xl border border-pink-100 p-4">
                <h3 class="font-bold text-gray-900 mb-4">Liên kết nhanh</h3>
                <div class="space-y-2">
                    <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition-colors">
                        <span class="material-symbols-outlined text-pink-600">dashboard</span>
                        <span class="text-sm font-medium text-gray-700">Bảng điều khiển</span>
                    </a>
                    <a href="{{ route('staff.shifts') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition-colors">
                        <span class="material-symbols-outlined text-pink-600">calendar_month</span>
                        <span class="text-sm font-medium text-gray-700">Lịch làm việc</span>
                    </a>
                    <a href="{{ route('staff.shifts') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition-colors">
                        <span class="material-symbols-outlined text-pink-600">schedule</span>
                        <span class="text-sm font-medium text-gray-700">Ca làm của tôi</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



