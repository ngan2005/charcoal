<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_images', function (Blueprint $table) {
            $table->id('ImageID');
            $table->unsignedBigInteger('PetID')->nullable();
            $table->string('ImageUrl', 255)->nullable();
            $table->boolean('IsMain')->default(0);

            $table->foreign('PetID')->references('PetID')->on('pets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_images');
    }
};
