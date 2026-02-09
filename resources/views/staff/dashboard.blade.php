@extends('layouts.staff')

@section('title', 'Bảng điều khiển Nhân viên')

@section('header_title', 'Bảng điều khiển')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-[#111827] rounded-2xl p-6 border border-[#f3e8ea] dark:border-gray-800 shadow-sm flex flex-col gap-2 transition-all hover:shadow-md">
        <div class="flex justify-between items-start">
            <p class="text-[#64748b] dark:text-gray-400 text-sm font-medium">Ca đã hoàn thành</p>
        </div>
        <p class="text-[#1e293b] dark:text-white text-3xl font-bold">{{ sprintf('%02d', $completedShiftsCount) }}</p>
        <div class="flex items-center gap-1 text-green-600 text-xs font-medium">
            <span class="material-symbols-outlined text-sm">trending_up</span>
            <span>Tổng số ca đã làm</span>
        </div>
    </div>

    <div class="bg-white dark:bg-[#111827] rounded-2xl p-6 border border-[#f3e8ea] dark:border-gray-800 shadow-sm flex flex-col gap-2 transition-all hover:shadow-md">
        <div class="flex justify-between items-start">
            <p class="text-[#64748b] dark:text-gray-400 text-sm font-medium">Lịch chăm sóc sắp tới</p>
        </div>
        <p class="text-[#1e293b] dark:text-white text-3xl font-bold">{{ sprintf('%02d', $upcomingAppointmentsCount) }}</p>
        <div class="flex items-center gap-1 text-primary text-xs font-medium">
            <span class="material-symbols-outlined text-sm">priority_high</span>
            <span>Cần lưu ý chuẩn bị</span>
        </div>
    </div>

    <div class="bg-white dark:bg-[#111827] rounded-2xl p-6 border border-[#f3e8ea] dark:border-gray-800 shadow-sm flex flex-col gap-2 transition-all hover:shadow-md">
        <div class="flex justify-between items-start">
            <p class="text-[#64748b] dark:text-gray-400 text-sm font-medium">Thú cưng hôm nay</p>
        </div>
        <p class="text-[#1e293b] dark:text-white text-3xl font-bold">{{ sprintf('%02d', $newPetsCount) }}</p>
        <div class="flex items-center gap-1 text-blue-600 text-xs font-medium">
            <span class="material-symbols-outlined text-sm">potted_plant</span>
            <span>Số bé cần chăm sóc hôm nay</span>
        </div>
    </div>

    <div class="bg-white dark:bg-[#111827] rounded-2xl p-6 border border-[#f3e8ea] dark:border-gray-800 shadow-sm flex flex-col gap-2 transition-all hover:shadow-md">
        <div class="flex justify-between items-start">
            <p class="text-[#64748b] dark:text-gray-400 text-sm font-medium">Thông báo mới</p>
        </div>
        <p class="text-[#1e293b] dark:text-white text-3xl font-bold">{{ sprintf('%02d', $unreadNotificationsCount) }}</p>
        <div class="flex items-center gap-1 text-orange-600 text-xs font-medium">
            <span class="material-symbols-outlined text-sm">notifications</span>
            <span>Thông báo chưa đọc</span>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <div class="xl:col-span-2 space-y-8">
        <!-- Today's Schedule -->
        <div class="bg-white dark:bg-[#111827] rounded-2xl border border-[#f3e8ea] dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-[#f3e8ea] dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">calendar_month</span>
                    Lịch làm việc hôm nay
                </h3>
                <a href="{{ route('staff.shifts') }}" class="text-primary text-sm font-semibold hover:underline">Toàn bộ lịch</a>
            </div>
            <div class="p-6">
                <!-- Thông tin ca trực (Nếu có) -->
                @if($todayShift)
                    <div class="mb-6 p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">work_history</span>
                            <div>
                                <p class="text-sm font-bold text-blue-800 dark:text-blue-200">Ca trực của bạn hôm nay</p>
                                <p class="text-xs text-blue-700 dark:text-blue-400">Thời gian: {{ date('H:i', strtotime($todayShift->StartTime)) }} - {{ date('H:i', strtotime($todayShift->EndTime)) }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-blue-600 text-white uppercase tracking-wider">Đang trong ca</span>
                    </div>
                @endif

                @if($todayAppointments->count() > 0)
                    <div class="grid grid-cols-[80px_1fr] gap-x-4">
                        @foreach($todayAppointments as $appointment)
                            <div class="flex flex-col items-center pt-1">
                                <span class="text-sm font-bold text-[#111318] dark:text-white">{{ $appointment->AppointmentTime->format('H:i') }}</span>
                                <div class="w-[2px] bg-[#f3e8ea] dark:bg-gray-800 h-full my-2 relative">
                                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full {{ $loop->first ? 'bg-primary' : 'bg-blue-500' }} border-2 border-white dark:border-[#111827]"></div>
                                </div>
                            </div>
                            <div class="pb-10">
                                <div class="bg-rose-50/50 dark:bg-rose-900/10 rounded-2xl p-5 flex items-center justify-between border border-rose-100/50 dark:border-rose-900/30 hover:border-primary/30 transition-all cursor-pointer group">
                                    <div class="flex items-center gap-4">
                                        <div class="size-12 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center text-primary shadow-sm group-hover:scale-105 transition-transform">
                                            <span class="material-symbols-outlined text-3xl">pets</span>
                                        </div>
                                        <div>
                                            <p class="text-base font-bold text-gray-800 dark:text-white">
                                                {{ $appointment->services->pluck('ServiceName')->implode(', ') }} cho {{ $appointment->pet?->PetName ?? 'Thú cưng' }} ({{ $appointment->pet?->Breed ?? 'N/A' }})
                                            </p>
                                            <p class="text-sm text-[#64748b] dark:text-gray-400">Khách hàng: {{ $appointment->customer?->FullName }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border
                                            @if($appointment->Status == 'pending') bg-yellow-100 text-yellow-700 border-yellow-200
                                            @elseif($appointment->Status == 'confirmed') bg-blue-100 text-blue-700 border-blue-200
                                            @elseif($appointment->Status == 'completed') bg-green-100 text-green-700 border-green-200
                                            @else bg-gray-100 text-gray-700 border-gray-200 @endif">
                                            {{ ucfirst($appointment->Status) }}
                                        </span>
                                        <button class="material-symbols-outlined text-[#94a3b8] hover:text-primary transition-colors">more_vert</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <span class="material-symbols-outlined text-5xl text-gray-300">event_busy</span>
                        <p class="text-gray-500 mt-2">Hôm nay bạn không có lịch chăm sóc thú cưng nào cụ thể.</p>
                        @if($todayShift)
                            <p class="text-xs text-primary font-medium mt-1">Hãy sẵn sàng đón các bé khách vãng lai trong ca trực của mình nhé!</p>
                        @endif
                    </div>
                @endif
            </div>

        </div>

        <!-- Care Notes -->
        <div class="bg-white dark:bg-[#111827] rounded-2xl border border-[#f3e8ea] dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-[#f3e8ea] dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Ghi chú chăm sóc</h3>
            </div>
            <div class="p-6 space-y-4">
                @forelse($careNotes as $note)
                    <div class="flex gap-4 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30">
                        <span class="material-symbols-outlined text-amber-500">warning</span>
                        <div>
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-200">Lưu ý cho bé {{ $note->PetName }}</p>
                            <p class="text-xs text-amber-700 dark:text-amber-400 mt-1">{{ $note->Notes }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center italic">Không có ghi chú quan trọng nào cho ngày hôm nay.</p>
                @endforelse
            </div>
        </div>
    </div>


    <!-- Right Sidebar -->
    <div class="space-y-6">
        <!-- Theme Customization -->
        <div class="bg-white dark:bg-[#111827] rounded-2xl p-6 border border-[#f3e8ea] dark:border-gray-800 shadow-sm">
            <h3 class="text-base font-bold mb-5 flex items-center gap-2 text-gray-800 dark:text-white">
                <span class="material-symbols-outlined text-primary">format_paint</span>
                Tùy chỉnh không gian
            </h3>
            <div class="grid grid-cols-2 gap-3">
                <button class="flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-primary transition-all bg-[#fdf8f9]" onclick="setTheme('light')">
                    <div class="w-full h-10 rounded-lg bg-white border border-rose-100"></div>
                    <span class="text-[11px] font-bold text-gray-500">Sáng sạch</span>
                </button>
                <button class="flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-primary transition-all bg-[#101622]" onclick="setTheme('dark')">
                    <div class="w-full h-10 rounded-lg bg-gray-900 border border-gray-800"></div>
                    <span class="text-[11px] font-bold text-gray-400">Chế độ tối</span>
                </button>
                <button class="flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-primary transition-all bg-[#fff1f2]" onclick="setTheme('pink')">
                    <div class="w-full h-10 rounded-lg bg-rose-100 border border-rose-200"></div>
                    <span class="text-[11px] font-bold text-rose-500">Hồng Pastel</span>
                </button>
                <button class="flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-primary transition-all bg-[#eff6ff]" onclick="setTheme('blue')">
                    <div class="w-full h-10 rounded-lg bg-blue-100 border border-blue-200"></div>
                    <span class="text-[11px] font-bold text-blue-500">Xanh Dương</span>
                </button>
            </div>
        </div>

        <!-- Important Reminder -->
        <div class="bg-primary rounded-2xl p-6 text-white shadow-lg shadow-primary/20 relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-9xl opacity-10">pets</span>
            <h4 class="font-bold text-lg mb-2 relative z-10">Nhắc nhở quan trọng</h4>
            <p class="text-sm text-white/80 leading-relaxed mb-4 relative z-10">Đừng quên kiểm tra kỹ nhiệt độ nước tắm cho các bé thú cưng trong ngày hôm nay.</p>
            <button class="w-full py-2 bg-white/20 hover:bg-white/30 rounded-xl text-sm font-bold transition-all backdrop-blur-md">Đã hiểu</button>
        </div>
    </div>
</div>

<script>
    function setTheme(theme) {
        const html = document.documentElement;
        const mainContent = document.getElementById('main-content');
        
        html.classList.remove('dark');
        mainContent.style.backgroundColor = '';
        
        switch(theme) {
            case 'dark':
                html.classList.add('dark');
                break;
            case 'pink':
                mainContent.style.backgroundColor = '#fff1f2';
                break;
            case 'blue':
                mainContent.style.backgroundColor = '#eff6ff';
                break;
            default:
                mainContent.style.backgroundColor = '#fdf8f9';
        }
        
        localStorage.setItem('theme', theme);
    }

    // Initialize theme from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        setTheme(savedTheme);
    });
</script>
@endsection
