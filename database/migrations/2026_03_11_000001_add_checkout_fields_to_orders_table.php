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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('OrderCode', 50)->nullable()->after('OrderID');
            $table->string('ShippingName', 255)->nullable()->after('Status');
            $table->string('ShippingPhone', 20)->nullable()->after('ShippingName');
            $table->string('ShippingAddress', 500)->nullable()->after('ShippingPhone');
            $table->string('PaymentMethod', 50)->nullable()->after('ShippingAddress');
            $table->string('PaymentStatus', 50)->default('unpaid')->after('PaymentMethod');
            $table->text('Note')->nullable()->after('PaymentStatus');
            $table->dateTime('UpdatedAt')->nullable()->after('CreatedAt');
        });

        // Update existing orders with default values
        DB::table('orders')->whereNull('OrderCode')->update([
            'OrderCode' => DB::raw("CONCAT('ORD-', DATE_FORMAT(CreatedAt, '%Y%m%d'), '-', LPAD(OrderID, 6, '0'))"),
            'PaymentStatus' => 'unpaid',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'OrderCode',
                'ShippingName',
                'ShippingPhone',
                'ShippingAddress',
                'PaymentMethod',
                'PaymentStatus',
                'Note',
                'UpdatedAt',
            ]);
        });
    }
};
