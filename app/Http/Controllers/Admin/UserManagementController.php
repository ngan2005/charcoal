<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function customers(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $users = User::query()
            ->select('users.*')
            ->where('RoleID', 3)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('FullName', 'like', '%' . $search . '%')
                        ->orWhere('Email', 'like', '%' . $search . '%');
                });
            })
            ->addSelect([
                'orders_count' => DB::table('orders')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('orders.UserID', 'users.UserID'),
                'orders_total' => DB::table('orders')
                    ->selectRaw('COALESCE(SUM(TotalAmount), 0)')
                    ->whereColumn('orders.UserID', 'users.UserID'),
                'appointments_count' => DB::table('appointments')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('appointments.CustomerID', 'users.UserID'),
            ])
            ->orderByDesc('CreatedAt')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.customers', [
            'users' => $users,
            'roles' => Role::orderBy('RoleName')->get(),
            'defaultRoleId' => 3,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function staff(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $users = User::query()
            ->select('users.*')
            ->where('RoleID', 2)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('FullName', 'like', '%' . $search . '%')
                        ->orWhere('Email', 'like', '%' . $search . '%');
                });
            })
            ->addSelect([
                'shifts_count' => DB::table('staff_shifts')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('staff_shifts.StaffID', 'users.UserID'),
                'services_count' => DB::table('appointment_services')
                    ->join('appointments', 'appointments.AppointmentID', '=', 'appointment_services.AppointmentID')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('appointments.StaffID', 'users.UserID'),
            ])
            ->orderByDesc('CreatedAt')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.staff', [
            'users' => $users,
            'roles' => Role::orderBy('RoleName')->get(),
            'defaultRoleId' => 2,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }
}
