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
use App\Mail\VerifyEmailMail;
use App\Mail\ResetPasswordMail;
use App\Mail\StaffRequestConfirmationMail;
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

        if (Schema::hasTable('email_verifications')) {
            // Generate verification token
            $verificationToken = Str::random(64);
            DB::table('email_verifications')->insert([
                'user_id' => $user->UserID,
                'token' => $verificationToken,
                'created_at' => now(),
            ]);

            // Send verification email
            Mail::to($user->Email)->send(new VerifyEmailMail($user, $verificationToken));

            return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.');
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

        // Send confirmation email
        Mail::to($staffRequest->Email)->send(new StaffRequestConfirmationMail($staffRequest));

        return redirect()->route('login')->with('success', 'Yêu cầu đã được gửi! Admin sẽ xem xét và liên hệ với bạn trong thời gian sớm nhất.');
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login user
    public function login(Request $request)
    {
        $validated = $request->validate([
            'Email' => 'required|email',
            'Password' => 'required|min:6',
        ]);

        $user = User::where('Email', $validated['Email'])->first();

        if (!$user || !Hash::check($validated['Password'], $user->Password)) {
            return back()->withErrors([
                'Email' => 'Email hoặc mật khẩu không đúng.',
            ])->onlyInput('Email');
        }

        if (!$user->IsActive) {
            return back()->withErrors([
                'Email' => 'Tài khoản của bạn đã bị vô hiệu hóa.',
            ])->onlyInput('Email');
        }

        // Check if email is verified (only when table exists)
        if (Schema::hasTable('email_verifications')) {
            $emailVerified = DB::table('email_verifications')
                ->where('user_id', $user->UserID)
                ->where('verified_at', '!=', null)
                ->exists();

            if ($user->RoleID == 3 && !$emailVerified) {
                return back()->with('warning', 'Vui lòng xác nhận email trước khi đăng nhập.');
            }
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

        // Send reset email
        Mail::to($user->Email)->send(new ResetPasswordMail($user, $resetToken));

        return back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
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

    // Verify email
    public function verifyEmail($token)
    {
        if (!Schema::hasTable('email_verifications')) {
            return redirect()->route('login')->with('error', 'Chức năng xác nhận email hiện chưa được cấu hình.');
        }

        $verification = DB::table('email_verifications')
            ->where('token', $token)
            ->first();

        if (!$verification) {
            return redirect()->route('login')->with('error', 'Liên kết xác nhận email không hợp lệ.');
        }

        // Mark email as verified
        DB::table('email_verifications')
            ->where('token', $token)
            ->update(['verified_at' => now()]);

        return redirect()->route('login')->with('success', 'Email đã được xác nhận thành công! Bạn có thể đăng nhập ngay.');
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
