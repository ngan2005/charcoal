<?php

// ============================================================
// NAMESPACE - Khai bao vung ten de tranh trung ten class
// ============================================================
namespace App\Http\Controllers\Auth;

// ============================================================
// CAC DONG USE - Import cac class can thiet
// ============================================================
use App\Http\Controllers\Controller;       // Class co so, AuthController ke thua tu Controller
use App\Models\User;                        // Model User de tuong tac voi bang users
use Illuminate\Http\Request;                 // Class xu ly request HTTP (POST, GET, ...)
use Illuminate\Support\Facades\Hash;         // Facade ma hoa mat khau (bcrypt algorithm)
use Illuminate\Support\Facades\Auth;         // Facade xac thuc nguoi dung (login, logout, ...)
use Illuminate\Support\Facades\Mail;          // Facade gui email
use Illuminate\Support\Facades\Schema;      // Facade thao tac schema database (hien tai chua dung)
use Illuminate\Support\Str;                 // Class xu ly chuoi ky tu (hien tai chua dung)

use App\Mail\ThankYouEmail;                 // Mailable class - dinh nghia template email cam on
use DB;                                     // Facade truy van SQL truc tiep (hien tai chua dung)

// ============================================================
// CLASS AUTHCONTROLLER - Controller xu ly auth (dang ky, dang nhap, dang xuat)
// ============================================================
class AuthController extends Controller
{
    // ========================================================
    // METHOD: Hien thi form dang ky khach hang
    // Route: GET /auth/register-customer
    // View: resources/views/auth/register-customer.blade.php
    // ========================================================
    public function showRegisterCustomer()
    {
        // Tra ve view chua form dang ky
        return view('auth.register-customer');
    }

    // ========================================================
    // METHOD: Xu ly dang ky khach hang moi
    // Route: POST /auth/register-customer
    // Tao tai khoan, gui email cam on, chuyen huong ve login
    // ========================================================
    public function registerCustomer(Request $request)
    {
        // -------------------- BUOC 1: VALIDATE DU LIEU --------------------
        // $request->validate() kiem tra va loc du lieu tu form
        // Neu fail -> tu dong quay lai form + hien thi loi
        $validated = $request->validate([
            'FullName' => 'required|string|max:100',                    // Bat buoc, la chuoi, toi da 100 ky tu
            'Email' => 'required|email|unique:users,Email',            // Bat buoc, dung dinh dang email, chi duy nhat trong bang users
            'Password' => 'required|min:6|confirmed',                  // Bat buoc, it nhat 6 ky tu, phai co xac nhan (Password_confirmation)
            'Phone' => 'nullable|string|max:15',                        // Khong bat buoc (nullable), la chuoi, toi da 15 ky tu
            'Address' => 'nullable|string|max:255',                     // Khong bat buoc, la chuoi, toi da 255 ky tu
        ]);

        // -------------------- BUOC 2: TAO USER MOI --------------------
        // User::create() tao ban ghi trong bang users (dung Mass Assignment)
        // $validated chua mang du lieu da duoc loc (chi cac truong hop le)
        $user = User::create([
            'FullName' => $validated['FullName'],                       // Gan ho ten tu du lieu form
            'Email' => $validated['Email'],                            // Gan email tu du lieu form
            'Password' => Hash::make($validated['Password']),           // MA HOA mat khau bang bcrypt -> hash, KHONG luu mat khau thuan
            'Phone' => $validated['Phone'] ?? null,                     // Neu co thi gan, khong thi null
            'Address' => $validated['Address'] ?? null,                // Neu co thi gan, khong thi null
            'RoleID' => 3,                                             // RoleID = 3 la KHACH HANG (1=Admin, 2=Staff, 3=Customer)
            'IsActive' => 1,                                           // 1 = Tai khoan dang hoat dong, co the dang nhap ngay
        ]);

        // -------------------- BUOC 3: GUI EMAIL CAM ON --------------------
        // Dung try-catch de neu email loi (vi du Gmail sai App Password)
        // -> khong lam crash trang dang ky, chi canh bao trong log
        try {
            // Mail::to() -> gui den email cua user vua dang ky
            // ->send() -> gui mail su dung Mailable class ThankYouEmail
            Mail::to($user->Email)->send(new ThankYouEmail($user));
        } catch (\Throwable $e) {
            // \Log::warning() ghi log loi nhung KHONG hien thi cho nguoi dung
            // $e->getMessage() lay noi dung loi tu exception
            \Log::warning('Khong gui duoc email cam on: ' . $e->getMessage());
        }

        // -------------------- BUOC 4: CHUYEN HUONG --------------------
        // redirect()->route('login') -> chuyen huong ve trang login
        // ->with('success', ...) -> gui message session de hien thi thong bao thanh cong
        return redirect()->route('login')->with('success', 'Dang ky thanh cong! Ban co the dang nhap ngay.');
    }

    // ========================================================
    // METHOD: Hien thi form dang nhap
    // Route: GET /auth/login
    // View: resources/views/auth/login.blade.php
    // ========================================================
    public function showLogin()
    {
        // Tra ve view chua form dang nhap
        return view('auth.login');
    }

    // ========================================================
    // METHOD: Xu ly dang nhap
    // Route: POST /auth/login
    // Kiem tra thong tin, dang nhap, phan quyen chuyen trang
    // ========================================================
    public function login(Request $request)
    {
        // Lay gia tri tu input 'Email' hoac 'Username' (form co the gui ca 2)
        // ?: la toan tu ternary - neu 'Email' co gia tri thi dung, khong thi lay 'Username'
        $emailOrUsername = $request->input('Email') ?: $request->input('Username');
        
        // -------------------- BUOC 1: VALIDATE --------------------
        // Chi kiem tra Password, vi Email/Username da check thu cong o duoi
        // Mang thu 2 la custom error messages tieng Viet
        $validated = $request->validate([
            'Password' => 'required|min:6',                           // Bat buoc, it nhat 6 ky tu
        ], [
            'Password.required' => 'Vui long nhap mat khau.',          // Loi neu khong nhap mat khau
            'Password.min' => 'Mat khau toi thieu 6 ky tu.',           // Loi neu mat khau qua ngan
        ]);

        // -------------------- BUOC 2: KIEM TRA EMAIL/USERNAME --------------------
        // Neu khong nhap gi -> bao loi
        if (empty($emailOrUsername)) {
            // back() -> quay lai trang truoc
            // ->withErrors() -> gui loi vao session errors de hien thi
            // ->onlyInput() -> giu lai gia tri da nhap (khong mat khi reload)
            return back()->withErrors([
                'Email' => 'Vui long nhap email hoac ten dang nhap.',
            ])->onlyInput('Email');
        }

        // Neu khong dung dinh dang email -> bao loi
        // filter_var() voi FILTER_VALIDATE_EMAIL kiem tra dinh dang email
        if (!filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors([
                'Email' => 'Vui long nhap dung dinh dang email.',
            ])->withInput(['Email' => $emailOrUsername, 'Username' => $emailOrUsername]);
        }

        // -------------------- BUOC 3: TIM USER TRONG DATABASE --------------------
        // Tim ban ghi co Email = gia tri nguoi dung nhap
        // ->first() tra ve 1 ban ghi hoac null
        $user = User::where('Email', $emailOrUsername)->first();

        // -------------------- BUOC 4: KIEM TRA MAT KHAU --------------------
        // Neu khong tim thay user HOAC mat khau khong khop -> bao loi
        // Hash::check() so sanh mat khau nhap voi hash trong database
        if (!$user || !Hash::check($validated['Password'], $user->Password)) {
            return back()->withErrors([
                'Email' => 'Email hoac mat khau khong dung.',
            ])->withInput(['Email' => $emailOrUsername, 'Username' => $emailOrUsername]);
        }

        // -------------------- BUOC 5: KIEM TRA TAI KHOAN BI KHOA --------------------
        // Neu IsActive = 0 (bi vo hieu hoa boi admin) -> khong cho dang nhap
        if (!$user->IsActive) {
            return back()->withErrors([
                'Email' => 'Tai khoan cua ban da bi vo hieu hoa.',
            ])->onlyInput('Email');
        }

        // -------------------- BUOC 6: CAP NHAT THONG TIN --------------------
        // Cap nhat LastLogin = thoi diem hien tai (now() tra ve Carbon datetime)
        $user->update(['LastLogin' => now()]);

        // -------------------- BUOC 7: DANG NHAP --------------------
        // Auth::login() luu thong tin user vao session -> tao authenticated session
        // $request->boolean('remember') -> neu nguoi dung tick "Ghi nho dang nhap"
        Auth::login($user, $request->boolean('remember'));

        // -------------------- BUOC 8: XU LY CHO NHAN VIEN --------------------
        // Neu la nhan vien (RoleID = 2) -> dat trang thai lam viec
        if ($user->RoleID == 2) {
            // Dat session is_working = true de hien thi trang thai ban
            session(['is_working' => true]);
            
            // Neu co staffProfile (bang staff_profiles) -> cap nhat trang thai trong DB
            // WorkStatusID = 1 nghia la "San sang" lam viec
            if ($user->staffProfile) {
                $user->staffProfile->update(['WorkStatusID' => 1]);
            }
        }

        // -------------------- BUOC 9: CHUYEN TRANG THEO VAI TRO --------------------
        // Khach hang (RoleID = 3) -> chuyen ve trang shop (trang chu Pink Charcoal)
        if ($user->RoleID == 3) {
            return redirect()->route('shop')->with('success', 'Dang nhap thanh cong!');
        }
        
        // Admin (RoleID = 1) hoac Staff (RoleID = 2) -> chuyen ve dashboard
        // redirect()->intended() -> uu tien chuyen ve URL truoc do (neu co), 
        // neu khong thi mac dinh la dashboard
        return redirect()->intended(route('dashboard'))->with('success', 'Dang nhap thanh cong!');
    }

    // ========================================================
    // METHOD: Dang xuat (logout)
    // Route: POST /auth/logout (thong thuong duoc goi tu form voi CSRF token)
    // Xoa session, huy token, chuyen ve trang login
    // ========================================================
    public function logout(Request $request)
    {
        // Auth::logout() -> xoa authenticated user khoi session
        Auth::logout();
        
        // session()->invalidate() -> huy toan bo session hien tai (bao gom cart, flash messages, ...)
        $request->session()->invalidate();
        
        // session()->regenerateToken() -> tao CSRF token moi
        // Phong chong tan cong CSRF khi logout
        $request->session()->regenerateToken();
        
        // Chuyen ve trang login voi thong bao thanh cong
        return redirect()->route('login')->with('success', 'Da dang xuat thanh cong!');
    }
}
