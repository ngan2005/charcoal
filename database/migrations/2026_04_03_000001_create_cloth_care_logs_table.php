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
        Schema::create('cloth_care_logs', function (Blueprint $table) {
            $table->id('LogID');
            $table->unsignedBigInteger('OrderID')->nullable();
            $table->unsignedBigInteger('StaffID')->nullable();
            $table->string('ItemName', 200);
            $table->string('ItemType', 100)->nullable();
            $table->string('Condition', 100)->nullable();
            $table->string('ServiceName', 200)->nullable();
            $table->string('Status', 50)->default('pending');
            $table->text('BeforeNotes')->nullable();
            $table->text('AfterNotes')->nullable();
            $table->text('StaffNotes')->nullable();
            $table->timestamps();

            $table->foreign('OrderID')->references('OrderID')->on('orders');
            $table->foreign('StaffID')->references('UserID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cloth_care_logs');
    }
};
