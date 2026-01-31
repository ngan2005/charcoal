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
        // WorkStatus
        Schema::create('work_status', function (Blueprint $table) {
            $table->id('WorkStatusID');
            $table->string('WorkStatusName', 100);
        });

        DB::table('work_status')->insert([
            ['WorkStatusID' => 1, 'WorkStatusName' => 'Sẵn sàng'],
            ['WorkStatusID' => 2, 'WorkStatusName' => 'Đang bận'],
            ['WorkStatusID' => 3, 'WorkStatusName' => 'Offline'],
        ]);

        // StaffProfiles
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('UserID')->primary();
            $table->string('Position', 50)->nullable();
            $table->integer('ExperienceYears')->nullable();
            $table->unsignedBigInteger('WorkStatusID')->default(1); 
            $table->decimal('Rating', 2, 1)->nullable();

            $table->foreign('UserID')->references('UserID')->on('users');
            $table->foreign('WorkStatusID')->references('WorkStatusID')->on('work_status');
        });
        
        // Let's just write the schema properly below.

        // ShiftStatus
        Schema::create('shift_status', function (Blueprint $table) {
            $table->id('ShiftStatusID');
            $table->string('ShiftStatusName', 100);
        });

        DB::table('shift_status')->insert([
             ['ShiftStatusID' => 1, 'ShiftStatusName' => 'Đang làm'],
             ['ShiftStatusID' => 2, 'ShiftStatusName' => 'Nghỉ'], // fixed casing to match values loosely or strict? User: 'nghỉ'. I will use 'Nghỉ' or 'nghỉ'. sticking to user input 'nghỉ'.
        ]);

        // StaffShifts
        Schema::create('staff_shifts', function (Blueprint $table) {
            $table->id('ShiftID');
            $table->unsignedBigInteger('StaffID')->nullable(); // UserID is BigInt
            $table->dateTime('StartTime')->nullable();
            $table->dateTime('EndTime')->nullable();
            $table->unsignedBigInteger('ShiftStatusID')->default(1);

            $table->foreign('StaffID')->references('UserID')->on('users');
            $table->foreign('ShiftStatusID')->references('ShiftStatusID')->on('shift_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_shifts');
        Schema::dropIfExists('shift_status');
        Schema::dropIfExists('staff_profiles');
        Schema::dropIfExists('work_status');
    }
};
