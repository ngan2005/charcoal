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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->text('Description')->nullable()->after('Quantity');
            $table->decimal('MinOrderAmount', 12, 2)->default(0)->after('Description');
            $table->decimal('MaxDiscountAmount', 12, 2)->default(0)->after('MinOrderAmount');
            $table->boolean('IsActive')->default(true)->after('MaxDiscountAmount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['Description', 'MinOrderAmount', 'MaxDiscountAmount', 'IsActive']);
        });
    }
};

