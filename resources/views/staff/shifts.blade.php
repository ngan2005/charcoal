@extends('layouts.staff')

@section('title', 'Ca trực - Nhân viên')

@section('header_title', 'Ca trực')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Lịch trực của tôi</h1>
            <p class="text-sm text-gray-500 mt-1">Ca trực đã được phân công</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-primary text-white rounded-xl font-medium hover:bg-primary-hover transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">today</span>
                Hôm nay: {{ date('d/m/Y') }}
            </button>
        </div>
    </div>

    @php
        $totalShifts = $shifts->count();
        $upcomingShifts = $shifts->where('StartTime', '>=', now())->take(5);
        
        // Tính toán thống kê đơn giản
        $workingCount = $shifts->where('ShiftStatusID', 1)->count(); // 1: Đang làm
        $offCount = $shifts->where('ShiftStatusID', 2)->count(); // 2: Nghỉ
        
        $totalHours = 0;
        foreach($shifts as $shift) {
            $start = \Carbon\Carbon::parse($shift->StartTime);
            $end = \Carbon\Carbon::parse($shift->EndTime);
            $totalHours += $end->diffInHours($start);
        }
    @endphp

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">calendar_month</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalShifts }}</p>
                    <p class="text-xs text-gray-500">Tổng số ca</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">work</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $workingCount }}</p>
                    <p class="text-xs text-gray-500">Ca làm việc</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">event_busy</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $offCount }}</p>
                    <p class="text-xs text-gray-500">Ca nghỉ</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-rose-100 dark:bg-rose-900/30 text-rose-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">schedule</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalHours }}h</p>
                    <p class="text-xs text-gray-500">Tổng giờ làm</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Upcoming Shifts -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-[#111827] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">event</span>
                        Ca làm sắp tới
                    </h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800 max-h-[500px] overflow-y-auto">
                    @forelse($upcomingShifts as $shift)
                        @php
                            $start = \Carbon\Carbon::parse($shift->StartTime);
                            $end = \Carbon\Carbon::parse($shift->EndTime);
                            $duration = $end->diffInHours($start);
                            
                            // Xác định buổi
                            $hour = $start->hour;
                            if($hour < 12) $session = 'Ca Sáng';
                            elseif($hour < 18) $session = 'Ca Chiều';
                            else $session = 'Ca Tối';
                            
                            $isToday = $start->isToday();
                        @endphp
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors {{ $isToday ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl {{ $shift->ShiftStatusID == 1 ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30' : 'bg-gray-100 text-gray-600 dark:bg-gray-700' }} flex flex-col items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold">{{ $start->format('d') }}</span>
                                    <span class="text-[10px] uppercase">{{ $start->format('M') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-semibold text-gray-800 dark:text-white">{{ $session }}</p>
                                        @if($isToday)
                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Hôm nay</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        <span class="material-symbols-outlined text-[14px] align-text-bottom">schedule</span>
                                        {{ $start->format('H:i') }} - {{ $end->format('H:i') }} ({{ $duration }}h)
                                    </p>
                                    <p class="text-xs {{ $shift->ShiftStatusID == 1 ? 'text-green-600' : 'text-gray-500' }}">
                                        {{ $shift->shiftStatus->ShiftStatusName ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            Không có ca trực sắp tới
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: All Shifts Table -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#111827] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">event_note</span>
                        Lịch làm việc
                    </h3>
                </div>
                <!-- Calendar Grid -->
                <div class="p-6">
                    <!-- Weekdays Header -->
                    <div class="grid grid-cols-7 gap-2 mb-4">
                        <div class="text-center text-xs font-semibold text-gray-500 py-2">T2</div>
                        <div class="text-center text-xs font-semibold text-gray-500 py-2">T3</div>
                        <div class="text-center text-xs font-semibold text-gray-500 py-2">T4</div>
                        <div class="text-center text-xs font-semibold text-gray-500 py-2">T5</div>
                        <div class="text-center text-xs font-semibold text-gray-500 py-2">T6</div>
                        <div class="text-center text-xs font-semibold text-gray-500 py-2">T7</div>
                        <div class="text-center text-xs font-semibold text-gray-500 py-2">CN</div>
                    </div>
                    
                    @php
                        $startOfMonth = now()->startOfMonth();
                        $endOfMonth = now()->endOfMonth();
                        $daysInMonth = $startOfMonth->daysInMonth;
                        $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sun) - 6 (Sat)
                        // Adjust to Mon (0) - Sun (6) usually, but Carbon dayOfWeek returns 0 for Sunday.
                        // Let's assume layout is T2 (Mon) -> CN (Sun).
                        // So Mon=1, Sun=7.
                        // Carbon: 0=Sun, 1=Mon, ...
                        // Adjusted offset: Mon(1)->0, Tue(2)->1 ... Sun(0)->6
                        $offset = ($firstDayOfWeek == 0) ? 6 : $firstDayOfWeek - 1;
                        
                        $shiftsByDate = $shifts->groupBy(function($date) {
                            return \Carbon\Carbon::parse($date->StartTime)->format('Y-m-d');
                        });
                    @endphp

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 gap-2">
                        <!-- Empty cells for padding -->
                        @for($i = 0; $i < $offset; $i++)
                            <div class="aspect-square"></div>
                        @endfor
                        
                        <!-- Days -->
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $currentDate = $startOfMonth->copy()->day($day);
                                $dateString = $currentDate->format('Y-m-d');
                                $hasShift = $shiftsByDate->has($dateString);
                                $dayShifts = $hasShift ? $shiftsByDate[$dateString] : collect();
                                $isToday = $currentDate->isToday();
                            @endphp
                            
                            <div class="aspect-square rounded-xl border {{ $isToday ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700' }} p-1 cursor-pointer hover:border-primary transition-colors relative group">
                                <span class="text-sm font-medium {{ $isToday ? 'text-primary font-bold' : 'text-gray-800 dark:text-white' }}">{{ $day }}</span>
                                
                                @if($hasShift)
                                    <div class="absolute bottom-1 left-1 right-1 flex gap-0.5 justify-center flex-wrap">
                                        @foreach($dayShifts as $s)
                                            <div class="h-1.5 w-1.5 rounded-full {{ $s->ShiftStatusID == 1 ? 'bg-green-500' : 'bg-gray-400' }}" title="{{ $s->ShiftStatusID == 1 ? 'Đang làm' : 'Nghỉ' }}"></div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                    
                    <!-- Legend -->
                    <div class="flex items-center justify-center gap-6 mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-500">Ca làm</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                            <span class="text-xs text-gray-500">Nghỉ</span>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">event_note</span>
                        Tất cả danh sách
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ngày</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ca</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Thời gian</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($shifts as $shift)
                                @php
                                    $start = \Carbon\Carbon::parse($shift->StartTime);
                                    $end = \Carbon\Carbon::parse($shift->EndTime);
                                    
                                    $hour = $start->hour;
                                    if($hour < 12) {
                                        $session = 'Ca Sáng';
                                        $icon = 'wb_sunny';
                                        $color = 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900/30';
                                    } elseif($hour < 18) {
                                        $session = 'Ca Chiều';
                                        $icon = 'partly_cloudy_day';
                                        $color = 'text-orange-600 bg-orange-100 dark:bg-orange-900/30';
                                    } else {
                                        $session = 'Ca Tối';
                                        $icon = 'bedtime';
                                        $color = 'text-indigo-600 bg-indigo-100 dark:bg-indigo-900/30';
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 font-bold text-xs">
                                                {{ $start->format('d') }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800 dark:text-white">{{ $start->format('d/m/Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ $start->locale('vi')->dayName }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-sm {{ $color }} rounded-full p-1">{{ $icon }}</span>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $session }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $start->format('H:i') }} - {{ $end->format('H:i') }}</p>
                                        <p class="text-xs text-gray-500">{{ $end->diffInHours($start) }} tiếng</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($shift->ShiftStatusID == 1)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                                Đang làm (Active)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                                {{ $shift->shiftStatus->ShiftStatusName ?? 'Nghỉ' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">event_busy</span>
                                            <p>Chưa có lịch trực nào được phân công.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

