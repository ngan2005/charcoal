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
}
