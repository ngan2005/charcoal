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
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ShiftAssignmentController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\PetController;

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

    // Pet management routes (for all authenticated users)
    Route::prefix('pets')->name('pets.')->group(function () {
        Route::get('/', [PetController::class, 'index'])->name('index');
        Route::get('/create', [PetController::class, 'create'])->name('create');
        Route::post('/store', [PetController::class, 'store'])->name('store');
        Route::get('/{pet}/edit', [PetController::class, 'edit'])->name('edit');
        Route::put('/{pet}', [PetController::class, 'update'])->name('update');
        Route::delete('/{pet}', [PetController::class, 'destroy'])->name('destroy');
    });
    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');
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
        Route::get('users/customer/{id}/details', [UserManagementController::class, 'getCustomerDetails'])->name('users.customer.details');
        Route::get('users/staff', [UserManagementController::class, 'staff'])->name('users.staff');
        Route::get('users/staff/{id}/details', [UserManagementController::class, 'getStaffDetails'])->name('users.staff.details');
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::delete('users', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // Shift Assignment Routes
        Route::get('shifts', [ShiftAssignmentController::class, 'index'])->name('shifts.index');
        Route::post('shifts', [ShiftAssignmentController::class, 'store'])->name('shifts.store');
        Route::put('shifts/{shift}', [ShiftAssignmentController::class, 'update'])->name('shifts.update');
        Route::delete('shifts/{shift}', [ShiftAssignmentController::class, 'destroy'])->name('shifts.destroy');
        Route::get('shifts/staff/{staffId}/suggestions', [ShiftAssignmentController::class, 'getStaffSuggestions'])->name('shifts.staff.suggestions');
        Route::get('shifts/export', [ShiftAssignmentController::class, 'export'])->name('shifts.export');

        // Order Routes
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::put('orders/{id}/payment', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');

        // Appointment Routes
        Route::get('appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/{id}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
        Route::put('appointments/{id}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        Route::post('appointments/{id}/assign-staff', [AdminAppointmentController::class, 'assignStaff'])->name('appointments.assign-staff');
        Route::post('appointments/{id}/add-service', [AdminAppointmentController::class, 'addService'])->name('appointments.add-service');
        Route::delete('appointments/{appointmentId}/services/{serviceId}', [AdminAppointmentController::class, 'removeService'])->name('appointments.remove-service');
        Route::get('appointments/{id}/staff-suggestions', [AdminAppointmentController::class, 'getStaffSuggestions'])->name('appointments.staff-suggestions');

        // Inventory Routes
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::put('inventory/{id}/stock', [InventoryController::class, 'updateStock'])->name('inventory.update-stock');
        Route::get('inventory/{id}', [InventoryController::class, 'show'])->name('inventory.show');
        Route::get('inventory/statistics', [InventoryController::class, 'statistics'])->name('inventory.statistics');

        // Review Routes
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/{id}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::get('reviews/{id}/hide', [ReviewController::class, 'hide'])->name('reviews.hide');
        Route::post('reviews/{id}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
        Route::get('reviews/statistics', [ReviewController::class, 'statistics'])->name('reviews.statistics');

        // Voucher Routes
        Route::get('vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
        Route::get('vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
        Route::post('vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
        Route::get('vouchers/{id}', [VoucherController::class, 'show'])->name('vouchers.show');
        Route::get('vouchers/{id}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
        Route::put('vouchers/{id}', [VoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('vouchers/{id}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
        Route::patch('vouchers/{id}/toggle', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});