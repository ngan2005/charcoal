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
        // Services
        Schema::create('services', function (Blueprint $table) {
            $table->id('ServiceID');
            $table->string('ServiceName', 150)->nullable();
            $table->decimal('BasePrice', 12, 2)->nullable();
            $table->decimal('MemberPrice', 12, 2)->nullable();
            $table->integer('Duration')->nullable();
            $table->string('Description', 255)->nullable();
        });

        // ServiceImages
        Schema::create('service_images', function (Blueprint $table) {
            $table->id('ImageID');
            $table->unsignedBigInteger('ServiceID')->nullable();
            $table->string('ImageUrl', 255)->nullable();

            $table->foreign('ServiceID')->references('ServiceID')->on('services');
        });

        // StaffServices
        Schema::create('staff_services', function (Blueprint $table) {
            $table->unsignedBigInteger('StaffID');
            $table->unsignedBigInteger('ServiceID');

            $table->primary(['StaffID', 'ServiceID']);
            $table->foreign('StaffID')->references('UserID')->on('users');
            $table->foreign('ServiceID')->references('ServiceID')->on('services');
        });

        // Pets
        Schema::create('pets', function (Blueprint $table) {
            $table->id('PetID');
            $table->unsignedBigInteger('OwnerID');
            $table->string('PetName', 100)->nullable();
            $table->string('Species', 50)->nullable();
            $table->string('Breed', 50)->nullable();
            $table->string('Size', 50)->nullable();
            $table->integer('Age')->nullable();
            $table->string('Notes', 255)->nullable();

            $table->foreign('OwnerID')->references('UserID')->on('users');
        });

        // Appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('AppointmentID');
            $table->unsignedBigInteger('CustomerID');
            $table->unsignedBigInteger('StaffID');
            $table->unsignedBigInteger('PetID')->nullable();
            $table->dateTime('AppointmentTime')->nullable();
            $table->string('Status', 50)->nullable();

            $table->foreign('CustomerID')->references('UserID')->on('users');
            $table->foreign('StaffID')->references('UserID')->on('users');
            $table->foreign('PetID')->references('PetID')->on('pets');
        });

        // AppointmentServices
        Schema::create('appointment_services', function (Blueprint $table) {
            $table->unsignedBigInteger('AppointmentID');
            $table->unsignedBigInteger('ServiceID');

            $table->primary(['AppointmentID', 'ServiceID']);
            $table->foreign('AppointmentID')->references('AppointmentID')->on('appointments');
            $table->foreign('ServiceID')->references('ServiceID')->on('services');
        });

        // PetCareRecords
        Schema::create('pet_care_records', function (Blueprint $table) {
            $table->id('RecordID');
            $table->unsignedBigInteger('PetID')->nullable();
            $table->unsignedBigInteger('ServiceID')->nullable();
            $table->unsignedBigInteger('StaffID')->nullable();
            $table->string('Status', 50)->nullable();
            $table->dateTime('StartDate')->nullable();
            $table->dateTime('EndDate')->nullable();

            $table->foreign('PetID')->references('PetID')->on('pets');
            $table->foreign('ServiceID')->references('ServiceID')->on('services');
            $table->foreign('StaffID')->references('UserID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_care_records');
        Schema::dropIfExists('appointment_services');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('pets');
        Schema::dropIfExists('staff_services');
        Schema::dropIfExists('service_images');
        Schema::dropIfExists('services');
    }
};
