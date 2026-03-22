<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = User::where('RoleID', 3)->count();
        $totalStaff = User::where('RoleID', 2)->count();
        $totalProducts = Product::count();
        $totalServices = Service::count();

        // Doanh thu đơn hàng: chỉ khi admin ấn "Đã giao hàng" / "Hoàn thành"
        $orderRevenue = Order::whereIn('Status', ['delivered', 'completed'])->sum('TotalAmount');
        // Doanh thu lịch hẹn: chỉ khi nhân viên ấn "Hoàn thành" (completed)
        $appointmentRevenue = DB::table('appointments')
            ->join('appointment_services', 'appointments.AppointmentID', '=', 'appointment_services.AppointmentID')
            ->join('services', 'appointment_services.ServiceID', '=', 'services.ServiceID')
            ->where('appointments.Status', 'completed')
            ->sum('services.BasePrice');
        $totalRevenue = $orderRevenue + $appointmentRevenue;

        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Doanh thu đơn hàng theo ngày
        $dailyOrderRevenue = Order::select([
                DB::raw("DATE(CreatedAt) as date"),
                DB::raw("SUM(TotalAmount) as total"),
            ])
            ->whereIn('Status', ['delivered', 'completed'])
            ->where('CreatedAt', '>=', $startDate)
            ->groupBy(DB::raw("DATE(CreatedAt)"))
            ->get()
            ->keyBy('date');

        // Doanh thu lịch hẹn (nhân viên ấn hoàn thành) theo ngày
        $dailyAppointmentRevenue = DB::table('appointments')
            ->join('appointment_services', 'appointments.AppointmentID', '=', 'appointment_services.AppointmentID')
            ->join('services', 'appointment_services.ServiceID', '=', 'services.ServiceID')
            ->where('appointments.Status', 'completed')
            ->whereBetween('appointments.AppointmentTime', [$startDate, $endDate])
            ->select(DB::raw('DATE(appointments.AppointmentTime) as date'), DB::raw('SUM(services.BasePrice) as total'))
            ->groupBy(DB::raw('DATE(appointments.AppointmentTime)'))
            ->get()
            ->keyBy('date');

        $labels = [];
        $values = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d/m');
            $orderTotal = (float) (optional($dailyOrderRevenue->get($date))->total ?? 0);
            $appointmentTotal = (float) (optional($dailyAppointmentRevenue->get($date))->total ?? 0);
            $values[] = $orderTotal + $appointmentTotal;
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
     * Gồm: doanh thu đơn hàng + doanh thu dịch vụ (lịch hẹn đã hoàn thành).
     *
     * @param string $period  7days | 1month | all
     */
    public function export(Request $request)
    {
        $period = $request->query('period', 'all');
        $endDate = Carbon::now()->endOfDay();
        $startDate = null;
        if ($period === '7days') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
        } elseif ($period === '1month') {
            $startDate = Carbon::now()->subDays(29)->startOfDay();
        }

        // Doanh thu đơn hàng
        $orderQuery = Order::with('user')
            ->whereIn('Status', ['delivered', 'completed']);
        if ($startDate !== null) {
            $orderQuery->where('CreatedAt', '>=', $startDate);
        }
        $orders = $orderQuery->orderByDesc('CreatedAt')->get();

        // Doanh thu dịch vụ (lịch hẹn đã hoàn thành)
        $serviceRows = DB::table('appointments')
            ->join('appointment_services', 'appointments.AppointmentID', '=', 'appointment_services.AppointmentID')
            ->join('services', 'appointment_services.ServiceID', '=', 'services.ServiceID')
            ->leftJoin('users as customer', 'appointments.CustomerID', '=', 'customer.UserID')
            ->leftJoin('pets', 'appointments.PetID', '=', 'pets.PetID')
            ->where('appointments.Status', 'completed')
            ->when($startDate !== null, fn ($q) => $q->where('appointments.AppointmentTime', '>=', $startDate))
            ->select(
                'appointments.AppointmentID',
                'appointments.AppointmentTime',
                'customer.FullName as CustomerName',
                'pets.PetName',
                'services.ServiceName',
                'services.BasePrice'
            )
            ->orderByDesc('appointments.AppointmentTime')
            ->get();

        $periodLabels = [
            '7days'  => '7 ngày gần nhất',
            '1month' => '1 tháng gần nhất',
            'all'    => 'Toàn bộ',
        ];
        $periodLabel = $periodLabels[$period] ?? 'Toàn bộ';

        $filename = 'bao-cao-doanh-thu-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders, $serviceRows, $periodLabel) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['BÁO CÁO DOANH THU']);
            fputcsv($file, ['Ngày xuất:', date('d/m/Y H:i:s')]);
            fputcsv($file, ['Kỳ báo cáo:', $periodLabel]);
            fputcsv($file, []);

            // ---- Phần 1: Doanh thu đơn hàng ----
            fputcsv($file, ['DOANH THU ĐƠN HÀNG']);
            fputcsv($file, ['STT', 'Mã đơn hàng', 'Khách hàng', 'Tổng tiền', 'Trạng thái', 'Ngày tạo']);

            $orderTotal = 0;
            $index = 1;
            foreach ($orders as $order) {
                fputcsv($file, [
                    $index++,
                    '#' . $order->OrderID,
                    $order->user?->FullName ?? 'Khách vãng lai',
                    number_format($order->TotalAmount, 0, ',', '.') . ' đ',
                    $order->Status,
                    $order->CreatedAt->format('d/m/Y H:i'),
                ]);
                $orderTotal += $order->TotalAmount;
            }
            fputcsv($file, []);
            fputcsv($file, ['', '', 'Tổng đơn hàng:', number_format($orderTotal, 0, ',', '.') . ' đ']);
            fputcsv($file, []);

            // ---- Phần 2: Doanh thu dịch vụ (lịch hẹn) ----
            fputcsv($file, ['DOANH THU DỊCH VỤ (LỊCH HẸN ĐÃ HOÀN THÀNH)']);
            fputcsv($file, ['STT', 'Mã lịch hẹn', 'Khách hàng', 'Thú cưng', 'Dịch vụ', 'Thành tiền', 'Ngày hoàn thành']);

            $serviceTotal = 0;
            $index = 1;
            foreach ($serviceRows as $row) {
                $price = (float) $row->BasePrice;
                $serviceTotal += $price;
                fputcsv($file, [
                    $index++,
                    '#' . $row->AppointmentID,
                    $row->CustomerName ?? '—',
                    $row->PetName ?? '—',
                    $row->ServiceName ?? '—',
                    number_format($price, 0, ',', '.') . ' đ',
                    $row->AppointmentTime ? Carbon::parse($row->AppointmentTime)->format('d/m/Y H:i') : '—',
                ]);
            }
            fputcsv($file, []);
            fputcsv($file, ['', '', '', '', 'Tổng dịch vụ:', number_format($serviceTotal, 0, ',', '.') . ' đ']);
            fputcsv($file, []);

            // ---- Tổng cộng ----
            $grandTotal = $orderTotal + $serviceTotal;
            fputcsv($file, ['TỔNG DOANH THU:', number_format($grandTotal, 0, ',', '.') . ' đ']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
