@extends('layouts.staff')

@section('title', 'Chấm công - Nhân viên')

@section('header_title', 'Chấm công')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Chấm công</h1>
            <p class="text-sm text-gray-500 mt-1">Theo dõi thời gian làm việc hàng ngày</p>
        </div>
    </div>

    <!-- Current Status -->
    <div class="bg-gradient-to-r from-primary to-primary-hover rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-pink-100 text-sm mb-1">Trạng thái hôm nay</p>
                <h2 class="text-2xl font-bold">Đang làm việc</h2>
                <p class="text-pink-100 text-sm mt-2">Ca: 08:00 - 12:00</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-center px-4 py-2 bg-white/20 rounded-xl">
                    <p class="text-3xl font-bold" id="work-hours">03:42:15</p>
                    <p class="text-xs text-pink-100">Thời gian làm hôm nay</p>
                </div>
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-white/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-400">check_circle</span>
                    <span class="text-sm">Đã check-in lúc 07:55</span>
                </div>
                <button class="px-4 py-2 bg-white text-primary rounded-lg font-medium hover:bg-pink-50 transition-colors">
                    Check-out
                </button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">22</p>
                    <p class="text-xs text-gray-500">Ngày làm việc</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">schedule</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">176h</p>
                    <p class="text-xs text-gray-500">Tổng giờ làm</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">timer</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">8h</p>
                    <p class="text-xs text-gray-500">Giờ TB/ngày</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">event_available</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">4</p>
                    <p class="text-xs text-gray-500">Ngày nghỉ phép</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="bg-white dark:bg-[#111827] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                Lịch sử chấm công tháng này
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ngày</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ca</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check-in</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check-out</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tổng giờ</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800 dark:text-white">08/02 (CN)</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">Sáng</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">07:55</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">12:05</td>
                        <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">4h 10m</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Đủ
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800 dark:text-white">07/02 (T7)</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">Sáng</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">08:02</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">12:00</td>
                        <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">3h 58m</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Đủ
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800 dark:text-white">06/02 (T6)</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-700">Chiều</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">13:55</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">18:02</td>
                        <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">4h 07m</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Đủ
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Live counter for work hours
    document.addEventListener('DOMContentLoaded', function() {
        let seconds = 3 * 3600 + 42 * 60 + 15; // 03:42:15
        
        function updateCounter() {
            seconds++;
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            const display = String(hours).padStart(2, '0') + ':' +
                           String(minutes).padStart(2, '0') + ':' +
                           String(secs).padStart(2, '0');
            
            document.getElementById('work-hours').textContent = display;
        }
        
        setInterval(updateCounter, 1000);
    });
</script>
@endsection


