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
        Schema::create('roles', function (Blueprint $table) {
            $table->integer('RoleID')->primary(); // Using integer ID as per requirement, usually bigint unsigned is preferred in Laravel but sticking to user schema
            $table->string('RoleName', 50);
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['RoleID' => 1, 'RoleName' => 'Admin'],
            ['RoleID' => 2, 'RoleName' => 'Nhân viên'],
            ['RoleID' => 3, 'RoleName' => 'Khách hàng'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
