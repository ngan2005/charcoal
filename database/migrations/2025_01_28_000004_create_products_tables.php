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
        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id('CategoryID');
            $table->string('CategoryName', 100)->nullable();
        });

        // ProductStatus
        Schema::create('product_status', function (Blueprint $table) {
            $table->integer('StatusID')->primary();
            $table->string('StatusName', 100)->nullable();
        });

        DB::table('product_status')->insert([
            ['StatusID' => 1, 'StatusName' => 'Còn hàng'],
            ['StatusID' => 2, 'StatusName' => 'Hết hàng'],
            ['StatusID' => 3, 'StatusName' => 'Ngừng kinh doanh'],
        ]);

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id('ProductID');
            $table->string('ProductCode', 30)->unique()->nullable();
            $table->string('ProductName', 150)->nullable();
            $table->unsignedBigInteger('CategoryID')->nullable();
            $table->decimal('Price', 12, 2)->nullable();
            $table->decimal('Weight', 6, 2)->nullable();
            $table->string('Size', 50)->nullable();
            $table->integer('Stock')->nullable();
            $table->integer('StatusID')->default(2);
            $table->string('Description', 255)->nullable();
            $table->integer('PurchaseCount')->default(0);
            $table->dateTime('CreatedAt')->useCurrent();

            $table->foreign('CategoryID')->references('CategoryID')->on('categories');
            $table->foreign('StatusID')->references('StatusID')->on('product_status');
        });

        // ProductImages
        Schema::create('product_images', function (Blueprint $table) {
            $table->id('ImageID');
            $table->unsignedBigInteger('ProductID')->nullable();
            $table->string('ImageUrl', 255)->nullable();
            $table->boolean('IsMain')->default(0);

            $table->foreign('ProductID')->references('ProductID')->on('products');
        });

        // RelatedProducts
        Schema::create('related_products', function (Blueprint $table) {
            $table->unsignedBigInteger('ProductID');
            $table->unsignedBigInteger('RelatedProductID');

            $table->primary(['ProductID', 'RelatedProductID']);
            $table->foreign('ProductID')->references('ProductID')->on('products');
            $table->foreign('RelatedProductID')->references('ProductID')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('related_products');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_status');
        Schema::dropIfExists('categories');
    }
};
