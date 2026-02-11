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
            if (!Schema::hasColumn('vouchers', 'IsActive')) {
                $table->boolean('IsActive')->default(true)->after('Quantity');
            }
            if (!Schema::hasColumn('vouchers', 'Description')) {
                $table->string('Description', 255)->nullable()->after('IsActive');
            }
            if (!Schema::hasColumn('vouchers', 'MinOrderAmount')) {
                $table->decimal('MinOrderAmount', 12, 2)->default(0)->after('Description');
            }
            if (!Schema::hasColumn('vouchers', 'MaxDiscountAmount')) {
                $table->decimal('MaxDiscountAmount', 12, 2)->default(0)->after('MinOrderAmount');
            }
            if (!Schema::hasColumn('vouchers', 'CreatedAt')) {
                $table->dateTime('CreatedAt')->useCurrent()->after('MaxDiscountAmount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['IsActive', 'Description', 'MinOrderAmount', 'MaxDiscountAmount', 'CreatedAt']);
        });
    }
};








