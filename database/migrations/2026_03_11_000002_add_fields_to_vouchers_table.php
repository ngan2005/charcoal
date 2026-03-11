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
        // Check if DiscountAmount column exists
        if (!Schema::hasColumn('vouchers', 'DiscountAmount')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->decimal('DiscountAmount', 12, 2)->nullable()->after('DiscountPercent');
            });
        }

        // Check if MinOrderAmount column exists
        if (!Schema::hasColumn('vouchers', 'MinOrderAmount')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->decimal('MinOrderAmount', 12, 2)->default(0)->after('DiscountAmount');
            });
        }

        // Check if Description column exists
        if (!Schema::hasColumn('vouchers', 'Description')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->string('Description', 255)->nullable()->after('Quantity');
            });
        }

        // Check if IsActive column exists
        if (!Schema::hasColumn('vouchers', 'IsActive')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->boolean('IsActive')->default(true)->after('Description');
            });
        }

        // Check if CreatedAt column exists
        if (!Schema::hasColumn('vouchers', 'CreatedAt')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->dateTime('CreatedAt')->useCurrent()->after('IsActive');
            });
        }

        // Check if UpdatedAt column exists
        if (!Schema::hasColumn('vouchers', 'UpdatedAt')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->dateTime('UpdatedAt')->nullable()->after('CreatedAt');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'DiscountAmount',
                'MinOrderAmount',
                'Description',
                'IsActive',
                'CreatedAt',
                'UpdatedAt',
            ]);
        });
    }
};
