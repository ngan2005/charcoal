<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Pet;
use App\Models\User;
use App\Notifications\AppointmentBookedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AppointmentController extends Controller
{
    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'PetID' => 'required|exists:pets,PetID',
            'ServiceID' => 'required|exists:services,ServiceID',
            'AppointmentDate' => 'required|date|after:today',
            'AppointmentTime' => 'required',
            'Notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $appointmentTime = $validated['AppointmentDate'] . ' ' . $validated['AppointmentTime'];

            $appointment = Appointment::create([
                'CustomerID' => Auth::id(),
                'PetID' => $validated['PetID'],
                'AppointmentTime' => $appointmentTime,
                'Status' => 'pending',
                'StaffID' => null, // Will be assigned by admin later
            ]);

            // Add service
            DB::table('appointment_services')->insert([
                'AppointmentID' => $appointment->AppointmentID,
                'ServiceID' => $validated['ServiceID'],
            ]);

            DB::commit();

            // Notify Admins
            $admins = User::where('RoleID', 1)->get();
            Notification::send($admins, new AppointmentBookedNotification($appointment));

            return redirect()->back()->with('success', 'Đặt lịch hẹn thành công! Vui lòng chờ xác nhận.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
