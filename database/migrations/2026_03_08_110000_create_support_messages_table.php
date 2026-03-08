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
        Schema::create('support_messages', function (Blueprint $table) {
            $table->id('MessageID');
            $table->unsignedBigInteger('UserID')->nullable();
            $table->string('SessionID')->nullable();
            $table->text('Message');
            $table->boolean('IsFromAdmin')->default(false);
            $table->timestamp('ReadAt')->nullable();
            $table->timestamps();

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_messages');
    }
};
