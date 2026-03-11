<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->string('service_name', 255)->nullable()->after('Message');
            $table->decimal('service_price', 12, 2)->nullable()->after('service_name');
            $table->string('service_url', 500)->nullable()->after('service_price');
            $table->unsignedBigInteger('service_id')->nullable()->after('service_url');
        });
    }

    public function down(): void
    {
        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropColumn(['service_name', 'service_price', 'service_url', 'service_id']);
        });
    }
};
