<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

use App\Mail\ThankYouEmail;
use DB;

class AuthController extends Controller
{
    // Show registration form
    public function showRegisterCustomer()
    {
        return view('auth.register-customer');
    }

    // Register customer
    public function registerCustomer(Request $request)
    {
        $validated = $request->validate([
            'FullName' => 'required|string|max:100',
            'Email' => 'required|email|unique:users,Email',
            'Password' => 'required|min:6|confirmed',
            'Phone' => 'nullable|string|max:15',
            'Address' => 'nullable|string|max:255',
        ]);

        // Create user with customer role (RoleID = 3 for customer)
        $user = User::create([
            'FullName' => $validated['FullName'],
            'Email' => $validated['Email'],
            'Password' => Hash::make($validated['Password']),
            'Phone' => $validated['Phone'] ?? null,
            'Address' => $validated['Address'] ?? null,
            'RoleID' => 3, // Customer role
            'IsActive' => 1,
        ]);

        // Gửi email cảm ơn (bắt mọi lỗi để không làm crash trang đăng ký)
        try {
            Mail::to($user->Email)->send(new ThankYouEmail($user));
        } catch (\Throwable $e) {
            \Log::warning('Không gửi được email cảm ơn: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Bạn có thể đăng nhập ngay.');
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login user (chấp nhận Email hoặc Username - đều tra theo cột Email)
    public function login(Request $request)
    {
        $emailOrUsername = $request->input('Email') ?: $request->input('Username');
        
        $validated = $request->validate([
            'Password' => 'required|min:6',
        ], [
            'Password.required' => 'Vui lòng nhập mật khẩu.',
            'Password.min' => 'Mật khẩu tối thiểu 6 ký tự.',
        ]);

        if (empty($emailOrUsername)) {
            return back()->withErrors([
                'Email' => 'Vui lòng nhập email hoặc tên đăng nhập.',
            ])->onlyInput('Email');
        }

        if (!filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors([
                'Email' => 'Vui lòng nhập đúng định dạng email.',
            ])->withInput(['Email' => $emailOrUsername, 'Username' => $emailOrUsername]);
        }

        $user = User::where('Email', $emailOrUsername)->first();

        if (!$user || !Hash::check($validated['Password'], $user->Password)) {
            return back()->withErrors([
                'Email' => 'Email hoặc mật khẩu không đúng.',
            ])->withInput(['Email' => $emailOrUsername, 'Username' => $emailOrUsername]);
        }

        if (!$user->IsActive) {
            return back()->withErrors([
                'Email' => 'Tài khoản của bạn đã bị vô hiệu hóa.',
            ])->withInput(['Email' => $emailOrUsername, 'Username' => $emailOrUsername]);
        }

        // Update last login
        $user->update(['LastLogin' => now()]);

        // Login user
        Auth::login($user, $request->boolean('remember'));

        // Auto set 'is_working' status for staff
        if ($user->RoleID == 2) {
            session(['is_working' => true]);
            
            // Update WorkStatus in DB if staff profile exists (1 = Sẵn sàng)
            if ($user->staffProfile) {
                $user->staffProfile->update(['WorkStatusID' => 1]);
            }
        }

        // Khách hàng -> trang shop (trang chủ Pink Charcoal), Admin/Nhân viên -> dashboard
        if ($user->RoleID == 3) {
            return redirect()->route('shop')->with('success', 'Đăng nhập thành công!');
        }
        return redirect()->intended(route('dashboard'))->with('success', 'Đăng nhập thành công!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đã đăng xuất thành công!');
    }
}
