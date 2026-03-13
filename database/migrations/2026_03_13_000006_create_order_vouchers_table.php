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
        Schema::create('order_vouchers', function (Blueprint $table) {
            $table->unsignedBigInteger('OrderID');
            $table->unsignedBigInteger('VoucherID');

            $table->primary(['OrderID', 'VoucherID']);
            $table->foreign('OrderID')->references('OrderID')->on('orders')->onDelete('cascade');
            $table->foreign('VoucherID')->references('VoucherID')->on('vouchers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_vouchers');
    }
};
