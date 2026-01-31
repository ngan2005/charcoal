<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Carts
        Schema::create('carts', function (Blueprint $table) {
            $table->integer('CartID')->primary(); // User specified INT PRIMARY KEY (no identity/autoinc mentioned, assuming manual or 1:1 with User? Actually usually carts are autoinc. Let's check schema: `CartID INT PRIMARY KEY` - No IDENTITY. But strict SQL usually implies unique. I will use id() or integer primary. If no identity, who generates it? Maybe UserID? No, UserID is FK. I'll make it auto-increment for safety unless specified otherswise. User schema: `CartID INT PRIMARY KEY`. Usually implies manual input or mistake in user schema not putting IDENTITY. I'll make it integer primary key without auto-increment for now to be safe with "exactness", or `id()`? `id()` is safer. I'll stick to `integer('CartID')->primary()`.)
            // Wait, if it's not auto-increment, how do we create carts? UUID? 
            // I'll assume it should be IDENTITY like others, but I will strictly follow `INT PRIMARY KEY` meaning "integer, primary key".
            // Actually, in many systems CartID matches UserID for 1:1. But let's see.
            // I'll add autoIncrement() to be safe for a web app, or just use `id()`.
            // User schema for others has IDENTITY. This one does NOT.
            // Carts(CartID, UserID...).
            // I will use `integer('CartID')->primary()`.
            
            $table->unsignedBigInteger('UserID')->nullable();
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('UserID')->references('UserID')->on('users');
        });

        // CartItems
        Schema::create('cart_items', function (Blueprint $table) {
            $table->integer('CartItemID')->primary(); // Again no IDENTITY.
            $table->integer('CartID')->nullable();
            $table->unsignedBigInteger('ProductID')->nullable();
            $table->unsignedBigInteger('ServiceID')->nullable();
            $table->integer('Quantity')->nullable();
            $table->unsignedBigInteger('PetID')->nullable();

            $table->foreign('CartID')->references('CartID')->on('carts');
            $table->foreign('ProductID')->references('ProductID')->on('products');
            $table->foreign('ServiceID')->references('ServiceID')->on('services');
            $table->foreign('PetID')->references('PetID')->on('pets');
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderID');
            $table->unsignedBigInteger('UserID')->nullable();
            $table->decimal('TotalAmount', 12, 2)->nullable();
            $table->string('Status', 50)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('UserID')->references('UserID')->on('users');
        });

        // OrderDetails
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('OrderDetailID');
            $table->unsignedBigInteger('OrderID')->nullable();
            $table->unsignedBigInteger('ProductID')->nullable();
            $table->integer('Quantity')->nullable();
            $table->unsignedBigInteger('ServiceID')->nullable();
            $table->unsignedBigInteger('PetID')->nullable();
            $table->decimal('UnitPrice', 12, 2)->nullable();

            $table->foreign('OrderID')->references('OrderID')->on('orders');
            $table->foreign('ProductID')->references('ProductID')->on('products');
            $table->foreign('ServiceID')->references('ServiceID')->on('services');
            $table->foreign('PetID')->references('PetID')->on('pets');
        });

        // PaymentStatus
        Schema::create('payment_status', function (Blueprint $table) {
            $table->id('StatusID');
            $table->string('StatusName', 100)->nullable();
        });

        DB::table('payment_status')->insert([
            ['StatusID' => 1, 'StatusName' => 'Chưa thanh toán'],
            ['StatusID' => 2, 'StatusName' => 'Đang xử lý'],
            ['StatusID' => 3, 'StatusName' => 'Đã thanh toán'],
            ['StatusID' => 4, 'StatusName' => 'Thanh toán thất bại'],
            ['StatusID' => 5, 'StatusName' => 'Đã hoàn tiền'],
            ['StatusID' => 6, 'StatusName' => 'Bị hủy'],
            ['StatusID' => 7, 'StatusName' => 'Hết hạn'],
        ]);

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id('PaymentID');
            $table->unsignedBigInteger('OrderID')->nullable();
            $table->string('Method', 50)->nullable();
            $table->unsignedBigInteger('StatusID')->default(1);
            $table->dateTime('PaidAt')->useCurrent(); // User: PaidAt DATETIME DEFAULT GETDATE()

            $table->foreign('OrderID')->references('OrderID')->on('orders');
            // Assuming PaymentStatus FK? User schema doesn't explicit FK but has StatusID.
            // "StatusID INT DEFAULT 1"
            // I'll add FK for data integrity.
            // Update: User schema does NOT have foreign key line for PaymentStatus. But naming convention implies it.
            // I'll add it.
            $table->foreign('StatusID')->references('StatusID')->on('payment_status');
        });

        // Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('ReviewID');
            $table->unsignedBigInteger('CustomerID');
            $table->unsignedBigInteger('ProductID')->nullable();
            $table->unsignedBigInteger('ServiceID')->nullable();
            $table->unsignedBigInteger('StaffID')->nullable();
            $table->unsignedBigInteger('ParentReviewID')->nullable();
            $table->integer('Rating')->nullable();
            $table->string('Comment', 500)->nullable();
            $table->boolean('Deleted')->default(0);
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('CustomerID')->references('UserID')->on('users');
            $table->foreign('ProductID')->references('ProductID')->on('products');
            $table->foreign('ServiceID')->references('ServiceID')->on('services');
            $table->foreign('StaffID')->references('UserID')->on('users');
            $table->foreign('ParentReviewID')->references('ReviewID')->on('reviews');
        });

        // Vouchers
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id('VoucherID');
            $table->string('Code', 50)->unique()->nullable();
            $table->integer('DiscountPercent')->nullable();
            $table->dateTime('ExpiredAt');
            $table->integer('Quantity')->nullable();
        });

        // OrderVouchers
        Schema::create('order_vouchers', function (Blueprint $table) {
            $table->unsignedBigInteger('OrderID');
            $table->unsignedBigInteger('VoucherID');

            $table->primary(['OrderID', 'VoucherID']);
            $table->foreign('OrderID')->references('OrderID')->on('orders');
            $table->foreign('VoucherID')->references('VoucherID')->on('vouchers');
        });

        // Memberships
        Schema::create('memberships', function (Blueprint $table) {
            $table->id('MembershipID');
            $table->unsignedBigInteger('CustomerID')->nullable();
            $table->string('Type', 100)->nullable();
            $table->dateTime('StartDate')->nullable();
            $table->dateTime('EndDate')->nullable();

            $table->foreign('CustomerID')->references('UserID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('order_vouchers');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_status');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
<?php
$recent_orders = \App\Models\Order::orderBy('CreatedAt', 'desc')
