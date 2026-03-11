<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('VoucherID')->nullable()->after('PaymentStatus');
            $table->decimal('DiscountAmount', 12, 2)->default(0)->after('VoucherID');
            $table->foreign('VoucherID')->references('VoucherID')->on('vouchers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['VoucherID']);
            $table->dropColumn(['VoucherID', 'DiscountAmount']);
        });
    }
};
