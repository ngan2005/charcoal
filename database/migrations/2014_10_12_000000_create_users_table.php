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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('UserID'); // Explicitly Unsigned Big Integer
            $table->string('FullName', 100);
            $table->string('Email', 100)->unique();
            $table->string('Password', 255);
            $table->string('Phone', 15)->nullable();
            $table->string('Address', 255)->nullable();
            $table->string('Avatar', 255)->nullable();
            $table->integer('RoleID');
            $table->boolean('IsActive')->default(1);
            $table->dateTime('LastLogin')->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            // Laravel defaults
            $table->rememberToken();
            $table->timestamps(); // Adds created_at and updated_at, kept for Laravel compatibility, though UpdatedAt wasn't explicitly asked, it's good practice. User asked for CreatedAt specifically.

            $table->foreign('RoleID')->references('RoleID')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
