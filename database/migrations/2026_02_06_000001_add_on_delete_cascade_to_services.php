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
        // Xóa foreign key cũ và tạo mới với ON DELETE CASCADE cho service_images
        Schema::table('service_images', function (Blueprint $table) {
            $table->dropForeign(['ServiceID']);
            $table->foreign('ServiceID')
                  ->references('ServiceID')
                  ->on('services')
                  ->onDelete('cascade');
        });

        // Cập nhật staff_services với ON DELETE CASCADE
        Schema::table('staff_services', function (Blueprint $table) {
            $table->dropForeign(['ServiceID']);
            $table->foreign('ServiceID')
                  ->references('ServiceID')
                  ->on('services')
                  ->onDelete('cascade');
        });

        // Cập nhật appointment_services với ON DELETE CASCADE
        Schema::table('appointment_services', function (Blueprint $table) {
            $table->dropForeign(['ServiceID']);
            $table->foreign('ServiceID')
                  ->references('ServiceID')
                  ->on('services')
                  ->onDelete('cascade');
        });

        // Cập nhật pet_care_records với ON DELETE CASCADE
        Schema::table('pet_care_records', function (Blueprint $table) {
            $table->dropForeign(['ServiceID']);
            $table->foreign('ServiceID')
                  ->references('ServiceID')
                  ->on('services')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback service_images
        Schema::table('service_images', function (Blueprint $table) {
            $table->dropForeign(['ServiceID']);
            $table->foreign('ServiceID')
                  ->references('ServiceID')
                  ->on('services');
        });

        // Rollback staff_services
        Schema::table('staff_services', function (Blueprint $table) {
            $table->dropForeign(['ServiceID']);
            $table->foreign('ServiceID')
                  ->references('ServiceID')
                  ->on('services');
        });

        // Rollback appointment_services
        Schema::table('appointment_services', function (Blueprint $table) {
            $table->dropForeign(['ServiceID']);
            $table->foreign('ServiceID')
                  ->references('ServiceID')
                  ->on('services');
        });

        // Rollback pet_care_records
        Schema::table('pet_care_records', function (Blueprint $table) {
            $table->dropForeign(['ServiceID']);
            $table->foreign('ServiceID')
                  ->references('ServiceID')
                  ->on('services');
        });
    }
};






