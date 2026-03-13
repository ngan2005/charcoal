<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('order_vouchers');
    }

    public function down(): void
    {
        Schema::create('order_vouchers', function (Blueprint $table) {
            $table->unsignedBigInteger('OrderID');
            $table->unsignedBigInteger('VoucherID');

            $table->primary(['OrderID', 'VoucherID']);
            $table->foreign('OrderID')->references('OrderID')->on('orders');
            $table->foreign('VoucherID')->references('VoucherID')->on('vouchers');
        });
    }
};
