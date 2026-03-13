<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('related_products');
    }

    public function down(): void
    {
        // Tạo lại bảng memberships
        Schema::create('memberships', function (Blueprint $table) {
            $table->id('MembershipID');
            $table->string('TierName', 50);
            $table->text('Description')->nullable();
            $table->decimal('DiscountPercent', 5, 2)->default(0);
            $table->decimal('MinSpending', 12, 2)->default(0);
            $table->tinyInteger('IsActive')->default(1);
            $table->timestamps();
        });

        // Tạo lại bảng related_products
        Schema::create('related_products', function (Blueprint $table) {
            $table->id('RelatedID');
            $table->unsignedBigInteger('ProductID');
            $table->unsignedBigInteger('RelatedProductID');
            $table->timestamps();

            $table->foreign('ProductID')->references('ProductID')->on('products')->onDelete('cascade');
            $table->foreign('RelatedProductID')->references('ProductID')->on('products')->onDelete('cascade');
        });
    }
};
