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
        Schema::create('staff_requests', function (Blueprint $table) {
            $table->id('RequestID');
            $table->string('FullName', 100);
            $table->string('Email', 100)->unique();
            $table->string('Phone', 15)->nullable();
            $table->string('Address', 255)->nullable();
            $table->string('Position', 100)->nullable();
            $table->text('ReasonForApplication')->nullable();
            $table->enum('Status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('RejectReason', 255)->nullable();
            $table->unsignedBigInteger('ApprovedByUserID')->nullable();
            $table->dateTime('ApprovedAt')->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ApprovedByUserID')->references('UserID')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_requests');
    }
};
