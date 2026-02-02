<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = User::where('RoleID', 3)->count();
        $totalStaff = User::where('RoleID', 2)->count();
        $totalProducts = Product::count();
        $totalServices = Service::count();
        $totalRevenue = Order::sum('TotalAmount');

        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $dailyRevenue = Order::select([
                DB::raw("DATE(CreatedAt) as date"),
                DB::raw("SUM(TotalAmount) as total"),
            ])
            ->where('CreatedAt', '>=', $startDate)
            ->groupBy(DB::raw("DATE(CreatedAt)"))
            ->orderBy(DB::raw("DATE(CreatedAt)"))
            ->get()
            ->keyBy('date');

        $labels = [];
        $values = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d/m');
            $values[] = (float) ($dailyRevenue[$date]->total ?? 0);
        }

        $recentProducts = Product::orderByDesc('CreatedAt')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'totalCustomers' => $totalCustomers,
            'totalStaff' => $totalStaff,
            'totalProducts' => $totalProducts,
            'totalServices' => $totalServices,
            'totalRevenue' => $totalRevenue,
            'chartLabels' => $labels,
            'chartValues' => $values,
            'recentProducts' => $recentProducts,
        ]);
    }

    /**
     * Xuất báo cáo doanh thu ra file CSV (Excel)
     */
    public function export()
    {
        // Lấy danh sách 100 đơn hàng gần nhất (hoặc tất cả nếu cần)
        $orders = Order::with('user')
            ->orderByDesc('CreatedAt')
            ->get();

        $filename = 'bao-cao-doanh-thu-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // BOM cho Excel có thể đọc UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Tiêu đề báo cáo
            fputcsv($file, ['BÁO CÁO DOANH THU']);
            fputcsv($file, ['Ngày xuất:', date('d/m/Y H:i:s')]);
            fputcsv($file, []);

            // Header bảng
            fputcsv($file, ['STT', 'Mã đơn hàng', 'Khách hàng', 'Tổng tiền', 'Trạng thái', 'Ngày tạo']);

            $index = 1;
            $totalAll = 0;
            foreach ($orders as $order) {
                fputcsv($file, [
                    $index++,
                    '#' . $order->OrderID,
                    $order->user?->FullName ?? 'Khách vãng lai',
                    number_format($order->TotalAmount, 0, ',', '.') . ' đ',
                    $order->Status,
                    $order->CreatedAt->format('d/m/Y H:i'),
                ]);
                $totalAll += $order->TotalAmount;
            }

            // Chân trang báo cáo
            fputcsv($file, []);
            fputcsv($file, ['', '', 'TỔNG CỘNG:', number_format($totalAll, 0, ',', '.') . ' đ']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
