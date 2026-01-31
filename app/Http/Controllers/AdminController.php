<?php

namespace App\Http\Controllers;

use App\Models\StaffRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Hiển thị danh sách đơn xin việc
     */
    public function staffRequests()
    {
        $requests = StaffRequest::where('Status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.staff-requests.index', compact('requests'));
    }

    /**
     * Hiển thị chi tiết đơn xin việc
     */
    public function showRequest($id)
    {
        $request = StaffRequest::findOrFail($id);
        return view('admin.staff-requests.show', compact('request'));
    }

    /**
     * Duyệt đơn xin việc
     */
    public function approveRequest(Request $request, $id)
    {
        $staffRequest = StaffRequest::findOrFail($id);
        
        if ($staffRequest->Status !== 'pending') {
            return back()->with('error', 'Đơn này đã được xử lý.');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Tạo tài khoản staff mới
        $user = User::create([
            'FullName' => $staffRequest->FullName,
            'Email' => $staffRequest->Email,
            'Password' => Hash::make($request->input('password')),
            'Phone' => $staffRequest->Phone,
            'Address' => $staffRequest->Address,
            'RoleID' => 2, // Staff role
            'IsActive' => true,
        ]);

        // Cập nhật trạng thái đơn
        $staffRequest->update([
            'Status' => 'approved',
            'ApprovedByUserID' => auth()->id(),
            'ApprovedAt' => now(),
        ]);

        return back()->with('success', 'Duyệt đơn xin việc thành công. Tài khoản staff đã được tạo.');
    }

    /**
     * Từ chối đơn xin việc
     */
    public function rejectRequest(Request $request, $id)
    {
        $staffRequest = StaffRequest::findOrFail($id);
        
        if ($staffRequest->Status !== 'pending') {
            return back()->with('error', 'Đơn này đã được xử lý.');
        }

        $staffRequest->update([
            'Status' => 'rejected',
            'ApprovedByUserID' => auth()->id(),
            'ApprovedAt' => now(),
        ]);

        return back()->with('success', 'Đã từ chối đơn xin việc.');
    }
}
