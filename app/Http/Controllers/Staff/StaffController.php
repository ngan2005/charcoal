<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the staff dashboard.
     */
    /**
     * Show the staff dashboard.
     */
    public function dashboard()
    {
        $userId = Auth::id();
        $today = now()->startOfDay();
        $endOfToday = now()->endOfDay();

        // 1. Thống kê (Stats)
        // Số ca đã hoàn thành (Dựa trên ca trực kết thúc trước hiện tại)
        $completedShiftsCount = \App\Models\StaffShift::where('StaffID', $userId)
            ->where('EndTime', '<', now())
            ->count();

        // Lịch chăm sóc sắp tới
        $upcomingAppointmentsCount = \App\Models\Appointment::where('StaffID', $userId)
            ->where('AppointmentTime', '>', now())
            ->whereIn('Status', ['pending', 'confirmed'])
            ->count();

        // Thú cưng mới nhận (trong ngày hôm nay)
        $newPetsCount = \App\Models\Appointment::where('StaffID', $userId)
            ->whereBetween('AppointmentTime', [$today, $endOfToday])
            ->distinct('PetID')
            ->count('PetID');

        // Tin nhắn hỗ trợ / Thông báo chưa đọc
        $unreadNotificationsCount = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->count();

        // 2. Lịch làm việc hôm nay
        $todayAppointments = \App\Models\Appointment::with(['pet', 'services'])
            ->where('StaffID', $userId)
            ->whereBetween('AppointmentTime', [$today, $endOfToday])
            ->orderBy('AppointmentTime', 'asc')
            ->get();

        // 3. Thông tin ca trực hôm nay
        $todayShift = \App\Models\StaffShift::where('StaffID', $userId)
            ->whereBetween('StartTime', [$today, $endOfToday])
            ->first();

        // 4. Ghi chú chăm sóc (Lấy từ Notes của các bé thú cưng trong lịch hôm nay)
        $careNotes = \App\Models\Pet::whereIn('PetID', $todayAppointments->pluck('PetID'))
            ->whereNotNull('Notes')
            ->select('PetName', 'Notes')
            ->get();

        return view('staff.dashboard', compact(
            'completedShiftsCount',
            'upcomingAppointmentsCount',
            'newPetsCount',
            'unreadNotificationsCount',
            'todayAppointments',
            'todayShift',
            'careNotes'
        ));

    }


    /**
     * Show the staff shifts page.
     */
    /**
     * Show the staff shifts page.
     */
    public function shifts()
    {
        $userId = Auth::id();
        
        // Lấy danh sách ca trực của nhân viên hiện tại
        // Sắp xếp theo thời gian giảm dần (mới nhất lên đầu) hoặc tăng dần tùy nhu cầu
        // Ở đây mình lấy các ca từ đầu tuần hiện tại trở đi để hiển thị lịch
        $startOfWeek = now()->startOfWeek();
        
        $shifts = \App\Models\StaffShift::with('shiftStatus')
            ->where('StaffID', $userId)
            // ->where('StartTime', '>=', $startOfWeek) // Nếu muốn chỉ lấy từ tuần này
            ->orderBy('StartTime', 'asc')
            ->get();

        return view('staff.shifts', compact('shifts'));
    }

    /**
     * Show the staff pets management page.
     */
    /**
     * Show the staff pets management page.
     */
    public function pets(Request $request)
    {
        $userId = Auth::id();
        $today = now()->startOfDay();
        $endOfToday = now()->endOfDay();
        $search = $request->input('search');

        // Thống kê (Dữ liệu thống kê vẫn tính trên tổng thể trong ngày)
        $currentlyCaring = \App\Models\Appointment::where('StaffID', $userId)
            ->where('Status', 'in_progress')
            ->count();

        $completedToday = \App\Models\Appointment::where('StaffID', $userId)
            ->where('Status', 'completed')
            ->whereBetween('AppointmentTime', [$today, $endOfToday])
            ->count();

        $pendingToday = \App\Models\Appointment::where('StaffID', $userId)
            ->whereIn('Status', ['pending', 'confirmed'])
            ->whereBetween('AppointmentTime', [$today, $endOfToday])
            ->count();

        $totalToday = \App\Models\Appointment::where('StaffID', $userId)
            ->whereBetween('AppointmentTime', [$today, $endOfToday])
            ->count();

        // Danh sách thú cưng thông qua lịch hẹn hôm nay (Có lọc theo tìm kiếm)
        $query = \App\Models\Appointment::with(['pet', 'services', 'customer'])
            ->where('StaffID', $userId)
            ->whereBetween('AppointmentTime', [$today, $endOfToday]);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('pet', function($pq) use ($search) {
                    $pq->where('PetName', 'like', "%{$search}%")
                       ->orWhere('Species', 'like', "%{$search}%")
                       ->orWhere('Breed', 'like', "%{$search}%");
                })->orWhereHas('customer', function($cq) use ($search) {
                    $cq->where('FullName', 'like', "%{$search}%");
                });
            });
        }

        $appointments = $query->orderBy('AppointmentTime', 'asc')->get();

        return view('staff.pets.index', compact(
            'currentlyCaring',
            'completedToday',
            'pendingToday',
            'totalToday',
            'appointments'
        ));
    }



    /**
     * Show the staff journal page.
     */
    public function journal(Request $request)
    {
        $userId = Auth::id();
        $petFilter = $request->input('pet_id');
        $dateFilter = $request->input('date_range', '7days');

        // Xây dựng query
        $query = \App\Models\PetCareRecord::with(['pet', 'staff', 'service'])
            ->orderBy('created_at', 'desc');

        // Lọc theo nhân viên hiện tại hoặc tất cả (tùy quyền)
        // Hiện tại chỉ hiển thị nhật ký của nhân viên đang đăng nhập
        $query->where('StaffID', $userId);

        // Lọc theo thú cưng
        if ($petFilter) {
            $query->where('PetID', $petFilter);
        }

        // Lọc theo khoảng thời gian
        if ($dateFilter == 'today') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($dateFilter == '7days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($dateFilter == '30days') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        $careRecords = $query->get();

        // Lấy danh sách thú cưng để hiển thị trong filter
        $pets = \App\Models\Pet::whereIn('PetID', 
            \App\Models\Appointment::where('StaffID', $userId)->pluck('PetID')
        )->get();

        return view('staff.journal.index', compact('careRecords', 'pets'));
    }

    /**
     * Store a new journal entry.
     */
    public function storeJournal(Request $request)
    {
        $validated = $request->validate([
            'PetID' => 'required|exists:pets,PetID',
            'Title' => 'required|string|max:200',
            'Notes' => 'nullable|string',
            'Status' => 'nullable|string|max:50',
        ]);

        $validated['StaffID'] = Auth::id();

        \App\Models\PetCareRecord::create($validated);

        return redirect()->route('staff.journal')->with('success', 'Đã thêm nhật ký chăm sóc thành công!');
    }

    /**
     * Show create appointment form.
     */
    public function createAppointment()
    {
        // Lấy danh sách khách hàng (RoleID = 3)
        $customers = \App\Models\User::where('RoleID', 3)
            ->orderBy('FullName')
            ->get();


        // Lấy danh sách thú cưng
        $pets = \App\Models\Pet::with('owner')->get();

        // Lấy danh sách dịch vụ
        $services = \App\Models\Service::all();


        return view('staff.appointments.create', compact('customers', 'pets', 'services'));
    }

    /**
     * Store a new appointment.
     */
    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'CustomerID' => 'required|exists:users,UserID',
            'PetID' => 'required|exists:pets,PetID',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,ServiceID',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Kết hợp ngày và giờ
        $appointmentDateTime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];

        // Tạo appointment
        $appointment = \App\Models\Appointment::create([
            'CustomerID' => $validated['CustomerID'],
            'StaffID' => Auth::id(), // Tự động gán staff hiện tại
            'PetID' => $validated['PetID'],
            'AppointmentTime' => $appointmentDateTime,
            'Status' => 'pending',
        ]);

        // Lưu services vào bảng appointment_services
        foreach ($validated['services'] as $serviceId) {
            DB::table('appointment_services')->insert([
                'AppointmentID' => $appointment->AppointmentID,
                'ServiceID' => $serviceId,
            ]);
        }

        return redirect()->route('staff.pets')->with('success', 'Đã tạo lịch hẹn thành công!');
    }

    /**


     * Show the staff timekeeping page.
     */
    public function timekeeping()
    {
        return view('staff.timekeeping.index');
    }

    /**
     * Show the staff leaves page.
     */
    public function leaves()
    {
        return view('staff.leaves.index');
    }

    /**
     * Show the staff profile.
     */
    public function profile()
    {
        $user = Auth::user();
        if($user) {
            $user->load('staffProfile.workStatus');
        }
        return view('staff.profile', compact('user'));
    }

    /**
     * Show the edit profile form.
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('staff.profile-edit', compact('user'));
    }

    /**
     * Update the staff profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'FullName' => 'required|string|max:100',
            'Phone' => 'nullable|string|max:15',
            'Address' => 'nullable|string|max:255',
            'AvatarFile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'CurrentPassword' => 'nullable|required_with:NewPassword|current_password',
            'NewPassword' => 'nullable|min:6|confirmed',
        ]);

        $user->FullName = $validated['FullName'];
        $user->Phone = $validated['Phone'];
        $user->Address = $validated['Address'];

        // Handle Avatar Upload
        if ($request->hasFile('AvatarFile')) {
            $file = $request->file('AvatarFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Store in storage/app/public/avatars
            $path = $file->storeAs('public/avatars', $filename);
            // Save relative path to DB (e.g., avatars/filename.jpg) - accessible via storage/avatars/filename.jpg
            $user->Avatar = 'avatars/' . $filename;
        }

        // Handle Password Change
        if (!empty($validated['NewPassword'])) {
            $user->Password = \Illuminate\Support\Facades\Hash::make($validated['NewPassword']);
        }

        $user->save();

        return redirect()->route('staff.profile')->with('success', 'Cập nhật hồ sơ thành công!');
    }

    /**
     * Toggle work status.
     */
    public function toggleStatus(Request $request)
    {
        $request->validate([
            'is_working' => 'required|boolean',
        ]);

        Session::put('is_working', $request->is_working);

        return response()->json([
            'success' => true,
            'is_working' => $request->is_working,
        ]);
    }
}
