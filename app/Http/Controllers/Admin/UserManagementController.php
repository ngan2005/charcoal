<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Pet;
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
                'pets_count' => DB::table('pets')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('pets.OwnerID', 'users.UserID'),
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

    /**
     * Get customer details with pets (AJAX)
     */
    public function getCustomerDetails($userId)
    {
        $user = User::where('RoleID', 3)
            ->where('UserID', $userId)
            ->firstOrFail();

        $pets = Pet::where('OwnerID', $userId)->get();

        $orders = DB::table('orders')
            ->where('UserID', $userId)
            ->orderByDesc('CreatedAt')
            ->limit(5)
            ->get();

        $appointments = DB::table('appointments')
            ->where('CustomerID', $userId)
            ->orderByDesc('AppointmentTime')
            ->limit(5)
            ->get();

        return response()->json([
            'user' => [
                'UserID' => $user->UserID,
                'FullName' => $user->FullName,
                'Email' => $user->Email,
                'Phone' => $user->Phone,
                'Address' => $user->Address,
                'Avatar' => $user->Avatar,
                'IsActive' => $user->IsActive,
                'CreatedAt' => $user->CreatedAt,
                'LastLogin' => $user->LastLogin,
            ],
            'orders_count' => DB::table('orders')->where('UserID', $userId)->count(),
            'orders_total' => DB::table('orders')->where('UserID', $userId)->sum('TotalAmount'),
            'appointments_count' => DB::table('appointments')->where('CustomerID', $userId)->count(),
            'pets' => $pets,
            'recent_orders' => $orders,
            'recent_appointments' => $appointments,
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

    /**
     * Get staff details with profile info (AJAX)
     */
    public function getStaffDetails($userId)
    {
        $user = User::where('RoleID', 2)
            ->where('UserID', $userId)
            ->firstOrFail();

        $staffProfile = DB::table('staff_profiles')
            ->where('UserID', $userId)
            ->first();

        $recentShifts = DB::table('staff_shifts')
            ->where('StaffID', $userId)
            ->orderByDesc('StartTime')
            ->limit(5)
            ->get();

        $recentServices = DB::table('appointment_services')
            ->join('appointments', 'appointments.AppointmentID', '=', 'appointment_services.AppointmentID')
            ->join('services', 'services.ServiceID', '=', 'appointment_services.ServiceID')
            ->select('services.ServiceName', 'appointments.AppointmentTime')
            ->where('appointments.StaffID', $userId)
            ->orderByDesc('appointments.AppointmentTime')
            ->limit(5)
            ->get();

        return response()->json([
            'user' => [
                'UserID' => $user->UserID,
                'FullName' => $user->FullName,
                'Email' => $user->Email,
                'Phone' => $user->Phone,
                'Address' => $user->Address,
                'Avatar' => $user->Avatar,
                'IsActive' => $user->IsActive,
                'CreatedAt' => $user->CreatedAt,
                'LastLogin' => $user->LastLogin,
            ],
            'staff_profile' => $staffProfile,
            'shifts_count' => DB::table('staff_shifts')->where('StaffID', $userId)->count(),
            'services_count' => DB::table('appointment_services')
                ->join('appointments', 'appointments.AppointmentID', '=', 'appointment_services.AppointmentID')
                ->where('appointments.StaffID', $userId)
                ->count(),
            'recent_shifts' => $recentShifts,
            'recent_services' => $recentServices,
        ]);
    }
}
