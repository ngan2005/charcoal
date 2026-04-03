<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
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
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommentController;

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

Route::get('/', [ShopController::class, 'index'])->name('shop');
Route::get('/about', function() { return view('about'); })->name('about');
Route::get('/services', [\App\Http\Controllers\PublicServiceController::class, 'index'])->name('services.index');
Route::post('/services/reviews', [\App\Http\Controllers\PublicServiceController::class, 'storeReview'])->name('service-reviews.store')->middleware('auth');
Route::delete('/services/reviews/{reviewId}', [\App\Http\Controllers\PublicServiceController::class, 'destroyReview'])->name('service-reviews.destroy')->middleware('auth');
Route::get('/services/{id}', [\App\Http\Controllers\PublicServiceController::class, 'show'])->name('services.show');
Route::get('/appointments/create', function (\Illuminate\Http\Request $request) {
    $serviceId = $request->query('service_id');
    return $serviceId
        ? redirect()->route('services.show', $serviceId)
        : redirect()->route('services.index');
})->name('appointment.create');
Route::get('/product/{id}', [ShopController::class, 'show'])->name('product.show');

// Comments Routes
Route::get('/comments/{productId}', [CommentController::class, 'getComments'])->name('comments.get');
Route::middleware('auth')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{commentId}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
Route::put('/cart/item/{id}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/item/{id}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart/clear', [\App\Http\Controllers\CartController::class, 'destroyAll'])->name('cart.clear');

// Checkout Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/apply-voucher', [CheckoutController::class, 'applyVoucher'])->name('checkout.apply-voucher');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Payment Routes
Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');

// VNPay Payment Routes
Route::get('/checkout/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('checkout.vnpay.return');

Route::get('/home', function () {
    return redirect()->route('dashboard');
});


// // Support Chat Routes (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/support/messages', [App\Http\Controllers\SupportController::class, 'getMessages'])->name('support.messages');
    Route::post('/support/send', [App\Http\Controllers\SupportController::class, 'store'])->name('support.send');
});

// Staff Support Routes - for staff to reply to customer messages
Route::prefix('staff/support')->name('staff.support.')->middleware('role:admin,staff')->group(function () {
    Route::get('/', [App\Http\Controllers\SupportController::class, 'index'])->name('index');
    Route::get('/conversations', [App\Http\Controllers\SupportController::class, 'getAllConversations'])->name('conversations');
    Route::get('/user/{userId}/messages', [App\Http\Controllers\SupportController::class, 'getUserMessages'])->name('user.messages');
    Route::post('/reply', [App\Http\Controllers\SupportController::class, 'reply'])->name('reply');
});

// Auth routes
Route::middleware('guest')->group(function () {
    // Register
    Route::get('auth/register-customer', [AuthController::class, 'showRegisterCustomer'])->name('register-customer');
    Route::post('auth/register-customer', [AuthController::class, 'registerCustomer'])->name('register.customer');
    
    // Login
    Route::get('auth/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('auth/login', [AuthController::class, 'login'])->name('login.post');
});

    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', function() {
            if (auth()->user()->RoleID == 1) {
                return redirect()->route('admin.dashboard');
            } elseif (auth()->user()->RoleID == 2) {
                return redirect()->route('staff.dashboard');
            }
            // Khách hàng: chuyển về trang shop (trang chủ Pink Charcoal)
            return redirect()->route('shop');
        })->name('dashboard');

        // Customer Profile Routes
        Route::get('/profile', [\App\Http\Controllers\CustomerProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [\App\Http\Controllers\CustomerProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\CustomerProfileController::class, 'changePassword'])->name('profile.password');
        Route::post('/profile/orders/{id}/cancel', [\App\Http\Controllers\CustomerProfileController::class, 'cancelOrder'])->name('profile.orders.cancel');
        Route::post('/profile/appointments/{id}/cancel', [\App\Http\Controllers\CustomerProfileController::class, 'cancelAppointment'])->name('profile.appointments.cancel');

    // Staff routes (for authenticated staff members)
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
        Route::get('/shifts', [StaffController::class, 'shifts'])->name('shifts');
        Route::get('/pets', [StaffController::class, 'pets'])->name('pets');
        Route::get('/journal', [StaffController::class, 'journal'])->name('journal');
        Route::post('/journal', [StaffController::class, 'storeJournal'])->name('journal.store');
        Route::get('/cloth-journal', [StaffController::class, 'clothJournal'])->name('cloth-journal');
        Route::post('/cloth-journal', [StaffController::class, 'storeClothJournal'])->name('cloth-journal.store');
        Route::get('/timekeeping', [StaffController::class, 'timekeeping'])->name('timekeeping');

        Route::get('/leaves', [StaffController::class, 'leaves'])->name('leaves');
        Route::get('/profile', [StaffController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [StaffController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile/update', [StaffController::class, 'updateProfile'])->name('profile.update');
        Route::post('/status/toggle', [StaffController::class, 'toggleStatus'])->name('status.toggle');
        
        // Staff Inventory (read-only)
        Route::get('/inventory', [StaffController::class, 'inventory'])->name('inventory');
        
        // Staff appointments
        Route::get('/appointments/create', [StaffController::class, 'createAppointment'])->name('appointments.create');
        Route::post('/appointments', [StaffController::class, 'storeAppointment'])->name('appointments.store');
        Route::put('/appointments/{id}/status', [StaffController::class, 'updateAppointmentStatus'])->name('appointments.update-status');

    });

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
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/images', [ProductController::class, 'getImages'])->name('products.images');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('services/{service}/images', [ServiceController::class, 'getImages'])->name('services.images');
        Route::resource('services', ServiceController::class)->except(['show', 'create', 'edit']);

        Route::get('users/customers', [UserManagementController::class, 'customers'])->name('users.customers');
        Route::get('users/customer/{id}/details', [UserManagementController::class, 'getCustomerDetails'])->name('users.customer.details');
        Route::get('users/admins', [UserManagementController::class, 'admins'])->name('users.admins');
        Route::get('users/admin/{id}/details', [UserManagementController::class, 'getAdminDetails'])->name('users.admin.details');
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
        Route::post('orders/{id}/assign-service-staff', [OrderController::class, 'assignServiceStaff'])->name('orders.assign-service-staff');

        // Appointment Routes
        Route::get('appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/{id}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
        Route::put('appointments/{id}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        Route::post('appointments/{id}/assign-staff', [AdminAppointmentController::class, 'assignStaff'])->name('appointments.assign-staff');
        Route::post('appointments/{id}/add-service', [AdminAppointmentController::class, 'addService'])->name('appointments.add-service');
        Route::delete('appointments/{appointmentId}/services/{serviceId}', [AdminAppointmentController::class, 'removeService'])->name('appointments.remove-service');
        Route::get('appointments/{id}/staff-suggestions', [AdminAppointmentController::class, 'getStaffSuggestions'])->name('appointments.staff-suggestions');

        // Inventory Routes (statistics phải đứng trước {id} để không bị match nhầm)
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('inventory/statistics', [InventoryController::class, 'statistics'])->name('inventory.statistics');
        Route::put('inventory/{id}/stock', [InventoryController::class, 'updateStock'])->name('inventory.update-stock');
        Route::get('inventory/{id}', [InventoryController::class, 'show'])->name('inventory.show');

        // Review Routes (statistics phải đứng trước {id})
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/statistics', [ReviewController::class, 'statistics'])->name('reviews.statistics');
        Route::get('reviews/{id}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::get('reviews/{id}/hide', [ReviewController::class, 'hide'])->name('reviews.hide');
        Route::post('reviews/{id}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');

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

    // Employee routes removed
});