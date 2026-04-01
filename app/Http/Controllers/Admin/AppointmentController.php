<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Service;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Hiển thị danh sách lịch hẹn
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $staffId = $request->input('staff_id');
        $date = $request->input('date');

        $appointments = Appointment::with(['customer', 'staff', 'pet', 'services'])
            ->when($status, function($query) use ($status) {
                return $query->where('Status', $status);
            })
            ->when($staffId, function($query) use ($staffId) {
                return $query->where('StaffID', $staffId);
            })
            ->when($date, function($query) use ($date) {
                return $query->whereDate('AppointmentTime', $date);
            })
            ->orderBy('AppointmentTime', 'desc')
            ->paginate(15);

        // Lấy danh sách nhân viên
        $staffMembers = User::where('RoleID', 2)
            ->where('IsActive', 1)
            ->orderBy('FullName')
            ->get();

        $appointmentStatuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'in_progress' => 'Đang thực hiện',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'no_show' => 'Vắng mặt',
        ];

        return view('admin.appointments.index', compact('appointments', 'appointmentStatuses', 'staffMembers'));
    }

    /**
     * Hiển thị chi tiết lịch hẹn
     */
    public function show($id)
    {
        $appointment = Appointment::with(['customer', 'staff', 'pet', 'services'])
            ->where('AppointmentID', $id)
            ->firstOrFail();

        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Cập nhật trạng thái lịch hẹn
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'Status' => 'required|string',
        ]);

        $appointment = Appointment::with(['order.details'])->where('AppointmentID', $id)->first();
        $oldStatus = $appointment->Status;
        $newStatus = $request->Status;

        // Hoàn lại tồn kho khi hủy lịch hẹn (từ pending/confirmed -> cancelled)
        // Chỉ hoàn lại nếu lịch hẹn có đơn hàng gốc
        if (in_array($oldStatus, ['pending', 'confirmed']) && $newStatus === 'cancelled' && $appointment->OrderID) {
            $order = $appointment->order;
            if ($order && $order->details) {
                foreach ($order->details as $detail) {
                    if ($detail->ProductID && $detail->Quantity > 0) {
                        DB::table('products')
                            ->where('ProductID', $detail->ProductID)
                            ->increment('Stock', $detail->Quantity);

                        // Giảm số lượng đã bán (PurchaseCount)
                        DB::table('products')
                            ->where('ProductID', $detail->ProductID)
                            ->decrement('PurchaseCount', $detail->Quantity);
                    }
                }
            }
        }

        Appointment::where('AppointmentID', $id)->update([
            'Status' => $request->Status,
        ]);

        $statusText = $newStatus === 'cancelled' ? 'hủy và hoàn lại tồn kho' : 'cập nhật';
        return redirect()
            ->route('admin.appointments.show', $id)
            ->with('success', 'Cập nhật trạng thái thành công! Lịch hẹn đã được ' . $statusText . '.');
    }

    /**
     * Phân công nhân viên cho lịch hẹn
     */
    public function assignStaff(Request $request, $id)
    {
        $request->validate([
            'StaffID' => 'required|exists:users,UserID',
        ]);

        Appointment::where('AppointmentID', $id)->update([
            'StaffID' => $request->StaffID,
        ]);

        return redirect()
            ->route('admin.appointments.show', $id)
            ->with('success', 'Phân công nhân viên thành công!');
    }

    /**
     * Thêm dịch vụ cho lịch hẹn
     */
    public function addService(Request $request, $id)
    {
        $request->validate([
            'ServiceID' => 'required|exists:services,ServiceID',
        ]);

        // Kiểm tra xem dịch vụ đã tồn tại chưa
        $exists = DB::table('appointment_services')
            ->where('AppointmentID', $id)
            ->where('ServiceID', $request->ServiceID)
            ->exists();

        if (!$exists) {
            DB::table('appointment_services')->insert([
                'AppointmentID' => $id,
                'ServiceID' => $request->ServiceID,
            ]);
        }

        return redirect()
            ->route('admin.appointments.show', $id)
            ->with('success', 'Thêm dịch vụ thành công!');
    }

    /**
     * Xóa dịch vụ khỏi lịch hẹn
     */
    public function removeService($appointmentId, $serviceId)
    {
        DB::table('appointment_services')
            ->where('AppointmentID', $appointmentId)
            ->where('ServiceID', $serviceId)
            ->delete();

        return redirect()
            ->route('admin.appointments.show', $appointmentId)
            ->with('success', 'Xóa dịch vụ thành công!');
    }

    /**
     * Lấy danh sách nhân viên gợi ý (AJAX)
     */
    public function getStaffSuggestions($appointmentId)
    {
        $appointment = Appointment::where('AppointmentID', $appointmentId)->first();

        if (!$appointment) {
            return response()->json(['error' => 'Lịch hẹn không tồn tại'], 404);
        }

        // Lấy tất cả nhân viên đang hoạt động
        $staff = User::where('RoleID', 2)
            ->where('IsActive', 1)
            ->with('staffProfile')
            ->get()
            ->map(function($s) {
            return [
                'UserID' => $s->UserID,
                'FullName' => $s->FullName,
                'Phone' => $s->Phone,
                'Position' => $s->staffProfile?->Position,
                'Rating' => $s->staffProfile?->Rating,
                'Avatar' => $s->Avatar,
            ];
        });

        return response()->json([
            'appointment' => [
                'AppointmentID' => $appointment->AppointmentID,
                'AppointmentTime' => $appointment->AppointmentTime,
                'current_staff_id' => $appointment->StaffID,
            ],
            'staff' => $staff,
        ]);
    }
}
