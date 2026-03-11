<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('CommentID');
            $table->unsignedBigInteger('ProductID');
            $table->unsignedBigInteger('UserID');
            $table->text('Content');
            $table->integer('Rating')->default(5)->comment('1-5 sao');
            $table->boolean('Status')->default(true)->comment('true: hiển thị, false: ẩn');
            $table->timestamps();

            $table->foreign('ProductID')->references('ProductID')->on('products')->onDelete('cascade');
            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
