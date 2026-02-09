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
        Schema::table('pet_care_records', function (Blueprint $table) {
            $table->string('Title', 200)->after('RecordID')->nullable();
            $table->text('Notes')->after('Title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pet_care_records', function (Blueprint $table) {
            $table->dropColumn(['Title', 'Notes', 'created_at', 'updated_at']);
        });
    }

};
