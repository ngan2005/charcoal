<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'completed_shifts' => [
                'count' => 12,
                'trend' => '+4',
                'trend_color' => 'green',
                'trend_label' => 'So với hôm qua',
            ],
            'pending_orders' => [
                'count' => 8,
                'trend' => '-2',
                'trend_color' => 'amber',
                'trend_label' => 'So với hôm qua',
            ],
            'new_messages' => [
                'count' => 5,
                'trend' => '+1',
                'trend_color' => 'blue',
                'trend_label' => 'So với hôm qua',
            ],
        ];

        $schedule = [
            [
                'time' => '08:00',
                'color' => 'green-500',
                'icon_bg' => 'green-100',
                'icon_color' => 'green-600',
                'icon' => 'pets',
                'title' => 'Tắm & cắt tỉa',
                'customer' => 'Nguyễn Văn A',
                'status' => 'Đang xử lý',
                'status_color' => 'green',
            ],
            [
                'time' => '10:30',
                'color' => 'blue-500',
                'icon_bg' => 'blue-100',
                'icon_color' => 'blue-600',
                'icon' => 'content_cut',
                'title' => 'Cắt móng',
                'customer' => 'Trần Thị B',
                'status' => 'Sắp đến',
                'status_color' => 'blue',
            ],
            [
                'time' => '14:00',
                'color' => 'amber-500',
                'icon_bg' => 'amber-100',
                'icon_color' => 'amber-600',
                'icon' => 'medication',
                'title' => 'Khám sức khỏe',
                'customer' => 'Lê Văn C',
                'status' => 'Chờ xử lý',
                'status_color' => 'amber',
            ],
        ];

        $recent_orders = [
            [
                'id' => '#DH-2301',
                'product' => 'Pate cho mèo',
                'time' => 'Hôm nay',
                'status' => 'Đang giao',
                'status_bg' => 'blue-100',
                'status_text' => 'blue-700',
                'action' => 'Chi tiết',
            ],
            [
                'id' => '#DH-2298',
                'product' => 'Vòng cổ thú cưng',
                'time' => 'Hôm qua',
                'status' => 'Hoàn tất',
                'status_bg' => 'green-100',
                'status_text' => 'green-700',
                'action' => 'Xem',
            ],
            [
                'id' => '#DH-2295',
                'product' => 'Sữa tắm thú cưng',
                'time' => '2 ngày trước',
                'status' => 'Chờ xác nhận',
                'status_bg' => 'amber-100',
                'status_text' => 'amber-700',
                'action' => 'Duyệt',
            ],
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'schedule' => $schedule,
            'recent_orders' => $recent_orders,
        ]);
    }
}
