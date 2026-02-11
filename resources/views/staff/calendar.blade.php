@extends('layouts.staff')

@section('header_title', 'Lịch làm việc')
@section('header_subtitle', 'Xem lịch làm việc của bạn')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lịch làm việc</h1>
            <p class="text-sm text-gray-500">Theo dõi lộ trình làm việc của bạn</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Month Navigation -->
            <div class="flex items-center bg-white rounded-lg border border-gray-200 overflow-hidden">
                <button class="p-2 hover:bg-gray-100 border-r border-gray-200">
                    <span class="material-symbols-outlined text-gray-600">chevron_left</span>
                </button>
                <span class="px-4 py-2 text-sm font-bold text-gray-900 whitespace-nowrap">
                    Tháng {{ now()->format('m') }} / {{ now()->year }}
                </span>
                <button class="p-2 hover:bg-gray-100 border-l border-gray-200">
                    <span class="material-symbols-outlined text-gray-600">chevron_right</span>
                </button>
            </div>
            <button class="px-4 py-2 bg-pink-500 text-white rounded-lg font-medium hover:bg-pink-600 flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">today</span>
                Hôm nay
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center">
                <span class="material-symbols-outlined">hourglass_empty</span>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">3</p>
                <p class="text-xs text-gray-500">Chờ xác nhận</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">5</p>
                <p class="text-xs text-gray-500">Đã xác nhận</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                <span class="material-symbols-outlined">task_alt</span>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">12</p>
                <p class="text-xs text-gray-500">Hoàn thành</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center">
                <span class="material-symbols-outlined">schedule</span>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">20h</p>
                <p class="text-xs text-gray-500">Tổng giờ làm</p>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Calendar Header -->
        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-100">
            <div class="px-4 py-3 text-center">
                <span class="text-xs font-semibold text-gray-500 uppercase">CN</span>
            </div>
            <div class="px-4 py-3 text-center">
                <span class="text-xs font-semibold text-gray-500 uppercase">T2</span>
            </div>
            <div class="px-4 py-3 text-center">
                <span class="text-xs font-semibold text-gray-500 uppercase">T3</span>
            </div>
            <div class="px-4 py-3 text-center">
                <span class="text-xs font-semibold text-gray-500 uppercase">T4</span>
            </div>
            <div class="px-4 py-3 text-center">
                <span class="text-xs font-semibold text-gray-500 uppercase">T5</span>
            </div>
            <div class="px-4 py-3 text-center">
                <span class="text-xs font-semibold text-gray-500 uppercase">T6</span>
            </div>
            <div class="px-4 py-3 text-center">
                <span class="text-xs font-semibold text-gray-500 uppercase">T7</span>
            </div>
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7">
            <!-- Previous month -->
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px] bg-gray-50">
                <span class="text-xs text-gray-400">26</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px] bg-gray-50">
                <span class="text-xs text-gray-400">27</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px] bg-gray-50">
                <span class="text-xs text-gray-400">28</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px] bg-gray-50">
                <span class="text-xs text-gray-400">29</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px] bg-gray-50">
                <span class="text-xs text-gray-400">30</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px] bg-gray-50">
                <span class="text-xs text-gray-400">31</span>
            </div>
            
            <!-- Current month - Feb 2026 -->
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">1</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">2</span>
                <div class="mt-1">
                    <span class="block px-1.5 py-0.5 bg-green-100 text-green-700 text-[10px] font-medium rounded">8-12</span>
                </div>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">3</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">4</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">5</span>
                <div class="mt-1">
                    <span class="block px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-medium rounded">14-18</span>
                </div>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">6</span>
                <div class="mt-1">
                    <span class="block px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-medium rounded">8-12</span>
                </div>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">7</span>
                <div class="mt-1">
                    <span class="block px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-medium rounded">14-18</span>
                </div>
            </div>
            
            <!-- Week 2 -->
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">8</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">9</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px] bg-pink-50">
                <span class="text-sm font-bold text-pink-600">10</span>
                <div class="mt-1">
                    <span class="block px-1.5 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-medium rounded">14-18</span>
                </div>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">11</span>
                <div class="mt-1">
                    <span class="block px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-medium rounded">8-12</span>
                </div>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">12</span>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">13</span>
                <div class="mt-1">
                    <span class="block px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-medium rounded">14-18</span>
                </div>
            </div>
            <div class="border-b border-r border-gray-100 p-2 min-h-[100px]">
                <span class="text-sm font-semibold text-gray-900">14</span>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="flex flex-wrap items-center justify-between gap-4 bg-white rounded-lg border border-gray-100 p-4">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-500">Chú thích:</span>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-yellow-100 border border-yellow-300"></span>
                <span class="text-sm text-gray-600">Chờ xác nhận</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-blue-100 border border-blue-300"></span>
                <span class="text-sm text-gray-600">Đã xác nhận</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-green-100 border border-green-300"></span>
                <span class="text-sm text-gray-600">Hoàn thành</span>
            </div>
        </div>
        <a href="{{ route('staff.shifts') }}" class="px-4 py-2 bg-pink-500 text-white rounded-lg text-sm font-medium hover:bg-pink-600 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">list</span>
            Xem danh sách
        </a>
    </div>
</div>
@endsection




