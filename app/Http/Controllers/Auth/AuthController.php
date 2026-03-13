<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportException;
use App\Mail\ResetPasswordMail;
use App\Mail\StaffRequestConfirmationMail;
use App\Mail\ThankYouEmail;
use DB;

class AuthController extends Controller
{
    // Show registration form
    public function showRegisterCustomer()
    {
        return view('auth.register-customer');
    }

    // Show staff registration form
    public function showRegisterStaff()
    {
        return view('auth.register-staff');
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

    // Register staff request
    public function registerStaff(Request $request)
    {
        $validated = $request->validate([
            'FullName' => 'required|string|max:100',
            'Email' => 'required|email|unique:staff_requests,Email|unique:users,Email',
            'Phone' => 'nullable|string|max:15',
            'Address' => 'nullable|string|max:255',
            'Position' => 'required|string|max:100',
            'ReasonForApplication' => 'required|string',
        ]);

        // Create staff request
        $staffRequest = StaffRequest::create([
            'FullName' => $validated['FullName'],
            'Email' => $validated['Email'],
            'Phone' => $validated['Phone'],
            'Address' => $validated['Address'],
            'Position' => $validated['Position'],
            'ReasonForApplication' => $validated['ReasonForApplication'],
            'Status' => 'pending',
        ]);

        // Send confirmation email (bắt lỗi nếu cấu hình mail chưa đúng)
        try {
            Mail::to($staffRequest->Email)->send(new StaffRequestConfirmationMail($staffRequest));
        } catch (TransportException $e) {
            \Log::warning('Không gửi được email xác nhận yêu cầu nhân sự: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('success', 'Yêu cầu đã được gửi! Admin sẽ xem xét và liên hệ với bạn trong thời gian sớm nhất.');
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

    // Show forgot password form
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Send reset password link
    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'Email' => 'required|email|exists:users,Email',
        ]);

        $user = User::where('Email', $validated['Email'])->first();

        // Only allow password reset for customers
        if ($user->RoleID != 3) {
            return back()->with('error', 'Chỉ khách hàng có thể đặt lại mật khẩu.');
        }

        // Generate reset token
        $resetToken = Str::random(64);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->Email],
            [
                'token' => Hash::make($resetToken),
                'created_at' => now(),
            ]
        );

        // Send reset email (bắt lỗi nếu cấu hình mail chưa đúng)
        try {
            Mail::to($user->Email)->send(new ResetPasswordMail($user, $resetToken));
            return back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
        } catch (TransportException $e) {
            \Log::warning('Không gửi được email đặt lại mật khẩu: ' . $e->getMessage());
            return back()->with('error', 'Tạm thời không gửi được email. Vui lòng kiểm tra cấu hình mail (MAIL_MAILER, MAIL_HOST) trong file .env hoặc thử lại sau.');
        }
    }

    // Show reset password form
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'Email' => 'required|email|exists:users,Email',
            'Password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        $resetRecord = DB::table('password_resets')
            ->where('email', $validated['Email'])
            ->first();

        if (!$resetRecord || !Hash::check($validated['token'], $resetRecord->token)) {
            return back()->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
        }

        // Check if token is not older than 1 hour
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_resets')->where('email', $validated['Email'])->delete();
            return back()->with('error', 'Liên kết đặt lại mật khẩu đã hết hạn. Vui lòng thử lại.');
        }

        // Update password
        User::where('Email', $validated['Email'])->update([
            'Password' => Hash::make($validated['Password']),
        ]);

        // Delete reset token
        DB::table('password_resets')->where('email', $validated['Email'])->delete();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại thành công!');
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
