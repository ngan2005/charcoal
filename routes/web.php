<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
});

Route::get('/shop', function () {
    return view('shop');
})->name('shop');

// Auth routes
Route::middleware('guest')->group(function () {
    // Register
    Route::get('auth/register-customer', [AuthController::class, 'showRegisterCustomer'])->name('register-customer');
    Route::post('auth/register-customer', [AuthController::class, 'registerCustomer'])->name('register.customer');
    
    Route::get('auth/register-staff', [AuthController::class, 'showRegisterStaff'])->name('register-staff');
    Route::post('auth/register-staff', [AuthController::class, 'registerStaff'])->name('register.staff');
    
    // Login
    Route::get('auth/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('auth/login', [AuthController::class, 'login'])->name('login.post');
    
    // Forgot Password
    Route::get('auth/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('auth/send-reset-link', [AuthController::class, 'sendResetLink'])->name('send-reset-link');
    
    // Reset Password
    Route::get('auth/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password.show');
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
});

// Email Verification
Route::get('auth/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify-email');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', function() {
        if (auth()->user()->RoleID == 1) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');
    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('staff-requests', [AdminController::class, 'staffRequests'])->name('staff-requests');
        Route::get('staff-request/{id}', [AdminController::class, 'showRequest'])->name('staff-request.show');
        Route::post('staff-request/{id}/approve', [AdminController::class, 'approveRequest'])->name('staff-request.approve');
        Route::post('staff-request/{id}/reject', [AdminController::class, 'rejectRequest'])->name('staff-request.reject');

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::resource('services', ServiceController::class)->except(['show', 'create', 'edit']);

        Route::get('users/customers', [UserManagementController::class, 'customers'])->name('users.customers');
        Route::get('users/staff', [UserManagementController::class, 'staff'])->name('users.staff');
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::delete('users', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});