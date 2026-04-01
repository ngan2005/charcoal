<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'PaymentTransactionRef')) {
                $table->string('PaymentTransactionRef', 100)->nullable()->after('PaymentStatus');
            }
            if (!Schema::hasColumn('orders', 'PaymentBankCode')) {
                $table->string('PaymentBankCode', 50)->nullable()->after('PaymentTransactionRef');
            }
            if (!Schema::hasColumn('orders', 'PaymentCompletedAt')) {
                $table->dateTime('PaymentCompletedAt')->nullable()->after('PaymentBankCode');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['PaymentTransactionRef', 'PaymentBankCode', 'PaymentCompletedAt'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
