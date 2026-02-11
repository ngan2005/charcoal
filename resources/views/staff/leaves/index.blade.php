@extends('layouts.staff')

@section('title', 'Đơn từ / Nghỉ phép - Nhân viên')

@section('header_title', 'Đơn từ / Nghỉ phép')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Đơn từ & Nghỉ phép</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý đơn xin nghỉ và các yêu cầu</p>
        </div>
        <button class="px-4 py-2 bg-primary text-white rounded-xl font-medium hover:bg-primary-hover transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span>
            Tạo đơn mới
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">event_available</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">4</p>
                    <p class="text-xs text-gray-500">Ngày phép còn lại</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">hourglass_empty</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">1</p>
                    <p class="text-xs text-gray-500">Đang chờ duyệt</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">3</p>
                    <p class="text-xs text-gray-500">Đã được duyệt</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#111827] rounded-xl p-4 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">cancel</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">1</p>
                    <p class="text-xs text-gray-500">Đã từ chối</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Requests -->
    <div class="bg-white dark:bg-[#111827] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">edit_document</span>
                Danh sách đơn từ
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Loại đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Thời gian</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Lý do</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ngày gửi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <!-- Pending Request -->
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-yellow-600 text-sm">beach_access</span>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-white">Nghỉ phép</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-800 dark:text-white">15/02 - 16/02</p>
                            <p class="text-xs text-gray-500">2 ngày</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-600 dark:text-gray-400 max-w-xs truncate">Có việc gia đình cần giải quyết</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">07/02/2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                Chờ duyệt
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </button>
                                <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-red-600">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Approved Request -->
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">medical_services</span>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-white">Nghỉ ốm</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-800 dark:text-white">03/02</p>
                            <p class="text-xs text-gray-500">1 ngày</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-600 dark:text-gray-400 max-w-xs truncate">Bị cảm lạnh, sốt nhẹ</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">03/02/2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                <span class="material-symbols-outlined text-xs filled">check</span>
                                Đã duyệt
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Approved Request -->
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-purple-600 text-sm">event</span>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-white">Nghỉ phép</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-800 dark:text-white">25/01 - 26/01</p>
                            <p class="text-xs text-gray-500">2 ngày</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-600 dark:text-gray-400 max-w-xs truncate">Du lịch cùng gia đình</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">20/01/2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                <span class="material-symbols-outlined text-xs filled">check</span>
                                Đã duyệt
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Rejected Request -->
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-red-600 text-sm">beach_access</span>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-white">Nghỉ phép</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-800 dark:text-white">10/01</p>
                            <p class="text-xs text-gray-500">1 ngày</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-600 dark:text-gray-400 max-w-xs truncate">Có việc riêng</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">05/01/2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                <span class="material-symbols-outlined text-xs filled">close</span>
                                Từ chối
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



