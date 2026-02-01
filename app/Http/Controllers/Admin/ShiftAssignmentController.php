<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ShiftStatus;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShiftAssignmentController extends Controller
{
    /**
     * Hiển thị danh sách phân công ca
     */
    public function index(Request $request)
    {
        $staffId = $request->input('staff_id');
        $date = $request->input('date');
        $weekStart = $request->input('week_start');

        // Lấy danh sách nhân viên (RoleID = 2)
        $staffMembers = User::where('RoleID', 2)
            ->where('IsActive', 1)
            ->with('staffProfile')
            ->orderBy('FullName')
            ->get();

        // Query shifts
        $shiftsQuery = DB::table('staff_shifts')
            ->select([
                'staff_shifts.*',
                'users.FullName as staff_name',
                'users.Email as staff_email',
                'users.Phone as staff_phone',
                'users.Avatar as staff_avatar',
                'shift_status.ShiftStatusName',
            ])
            ->leftJoin('users', 'staff_shifts.StaffID', '=', 'users.UserID')
            ->leftJoin('shift_status', 'staff_shifts.ShiftStatusID', '=', 'shift_status.ShiftStatusID')
            ->where('users.RoleID', 2);

        // Lọc theo nhân viên
        if ($staffId) {
            $shiftsQuery->where('staff_shifts.StaffID', $staffId);
        }

        // Lọc theo tuần
        if ($weekStart) {
            $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
            $shiftsQuery->whereDate('staff_shifts.StartTime', '>=', $weekStart)
                        ->whereDate('staff_shifts.StartTime', '<=', $weekEnd);
        } elseif ($date) {
            $shiftsQuery->whereDate('staff_shifts.StartTime', $date);
        } else {
            // Mặc định lấy tuần hiện tại
            $weekStart = date('Y-m-d', strtotime('monday this week'));
        }

        $shifts = $shiftsQuery->orderBy('staff_shifts.StartTime')
            ->orderBy('staff_shifts.StaffID')
            ->get();

        // Nhóm shifts theo ngày
        $shiftsByDate = $shifts->groupBy(function($item) {
            return date('Y-m-d', strtotime($item->StartTime));
        });

        $shiftStatuses = ShiftStatus::orderBy('ShiftStatusID')->get();

        return view('admin.shifts.index', [
            'staffMembers' => $staffMembers,
            'shifts' => $shifts,
            'shiftsByDate' => $shiftsByDate,
            'shiftStatuses' => $shiftStatuses,
            'filters' => [
                'staff_id' => $staffId,
                'date' => $date,
                'week_start' => $weekStart,
            ],
        ]);
    }

    /**
     * Lưu phân công ca mới
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'StaffID' => ['required', 'exists:users,UserID'],
            'ShiftDate' => ['required', 'date'],
            'StartTime' => ['required'],
            'EndTime' => ['required', 'after:StartTime'],
            'ShiftStatusID' => ['required', 'exists:shift_status,ShiftStatusID'],
        ])->validate();

        // Kết hợp ngày và giờ
        $startDateTime = $validated['ShiftDate'] . ' ' . $validated['StartTime'];
        $endDateTime = $validated['ShiftDate'] . ' ' . $validated['EndTime'];

        DB::table('staff_shifts')->insert([
            'StaffID' => $validated['StaffID'],
            'StartTime' => $startDateTime,
            'EndTime' => $endDateTime,
            'ShiftStatusID' => $validated['ShiftStatusID'],
        ]);

        return redirect()
            ->route('admin.shifts.index')
            ->with('success', 'Phân công ca thành công!');
    }

    /**
     * Cập nhật phân công ca
     */
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'StaffID' => ['required', 'exists:users,UserID'],
            'ShiftDate' => ['required', 'date'],
            'StartTime' => ['required'],
            'EndTime' => ['required', 'after:StartTime'],
            'ShiftStatusID' => ['required', 'exists:shift_status,ShiftStatusID'],
        ])->validate();

        $startDateTime = $validated['ShiftDate'] . ' ' . $validated['StartTime'];
        $endDateTime = $validated['ShiftDate'] . ' ' . $validated['EndTime'];

        DB::table('staff_shifts')
            ->where('ShiftID', $id)
            ->update([
                'StaffID' => $validated['StaffID'],
                'StartTime' => $startDateTime,
                'EndTime' => $endDateTime,
                'ShiftStatusID' => $validated['ShiftStatusID'],
            ]);

        return redirect()
            ->route('admin.shifts.index')
            ->with('success', 'Cập nhật ca thành công!');
    }

    /**
     * Xóa phân công ca
     */
    public function destroy($id)
    {
        DB::table('staff_shifts')->where('ShiftID', $id)->delete();

        return redirect()
            ->route('admin.shifts.index')
            ->with('success', 'Xóa ca thành công!');
    }

    /**
     * Lấy thông tin gợi ý khi chọn nhân viên (AJAX)
     */
    public function getStaffSuggestions($staffId)
    {
        $staff = User::where('RoleID', 2)
            ->where('UserID', $staffId)
            ->with('staffProfile')
            ->first();

        if (!$staff) {
            return response()->json(['error' => 'Nhân viên không tồn tại'], 404);
        }

        // Lấy thống kê
        $totalShifts = DB::table('staff_shifts')
            ->where('StaffID', $staffId)
            ->count();

        $thisWeekShifts = DB::table('staff_shifts')
            ->where('StaffID', $staffId)
            ->whereBetween('StartTime', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();

        // Lấy các ca trong tuần hiện tại để gợi ý
        $weekShifts = DB::table('staff_shifts')
            ->where('StaffID', $staffId)
            ->where('StartTime', '>=', now()->startOfWeek())
            ->where('StartTime', '<=', now()->endOfWeek())
            ->orderBy('StartTime')
            ->get()
            ->groupBy(function($item) {
                return date('l', strtotime($item->StartTime));
            });

        return response()->json([
            'staff' => [
                'UserID' => $staff->UserID,
                'FullName' => $staff->FullName,
                'Email' => $staff->Email,
                'Phone' => $staff->Phone,
                'Avatar' => $staff->Avatar,
                'Position' => $staff->staffProfile?->Position,
                'Rating' => $staff->staffProfile?->Rating,
            ],
            'stats' => [
                'total_shifts' => $totalShifts,
                'this_week_shifts' => $thisWeekShifts,
            ],
            'week_shifts' => $weekShifts,
        ]);
    }

    /**
     * Xuất Excel danh sách phân công ca
     */
    public function export(Request $request)
    {
        $weekStart = $request->input('week_start', date('Y-m-d', strtotime('monday this week')));
        $staffId = $request->input('staff_id');

        $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));

        // Lấy dữ liệu shifts
        $shiftsQuery = DB::table('staff_shifts')
            ->select([
                'staff_shifts.ShiftID',
                'users.FullName as staff_name',
                'users.Phone as staff_phone',
                'staff_shifts.StartTime',
                'staff_shifts.EndTime',
                'shift_status.ShiftStatusName',
            ])
            ->leftJoin('users', 'staff_shifts.StaffID', '=', 'users.UserID')
            ->leftJoin('shift_status', 'staff_shifts.ShiftStatusID', '=', 'shift_status.ShiftStatusID')
            ->where('users.RoleID', 2)
            ->whereDate('staff_shifts.StartTime', '>=', $weekStart)
            ->whereDate('staff_shifts.StartTime', '<=', $weekEnd);

        if ($staffId) {
            $shiftsQuery->where('staff_shifts.StaffID', $staffId);
        }

        $shifts = $shiftsQuery->orderBy('staff_shifts.StartTime')
            ->orderBy('users.FullName')
            ->get();

        // Tạo tên file
        $filename = 'phan-cong-ca-' . $weekStart . '-' . $weekEnd . '.csv';

        // Tạo header cho CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // Tạo nội dung CSV
        $callback = function() use ($shifts, $weekStart, $weekEnd) {
            $file = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, ['PHÂN CÔNG CA LÀM VIỆC']);
            fputcsv($file, ['Tuần: ' . date('d/m/Y', strtotime($weekStart)) . ' - ' . date('d/m/Y', strtotime($weekEnd))]);
            fputcsv($file, []);
            fputcsv($file, ['STT', 'Nhân viên', 'SĐT', 'Ngày', 'Giờ bắt đầu', 'Giờ kết thúc', 'Trạng thái']);

            // Data
            $index = 1;
            foreach ($shifts as $shift) {
                fputcsv($file, [
                    $index++,
                    $shift->staff_name,
                    $shift->staff_phone ?? '',
                    date('d/m/Y', strtotime($shift->StartTime)),
                    date('H:i', strtotime($shift->StartTime)),
                    date('H:i', strtotime($shift->EndTime)),
                    $shift->ShiftStatusName,
                ]);
            }

            // Footer
            fputcsv($file, []);
            fputcsv($file, ['Tổng số ca: ' . count($shifts)]);
            fputcsv($file, ['Ngày xuất: ' . date('d/m/Y H:i:s')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

