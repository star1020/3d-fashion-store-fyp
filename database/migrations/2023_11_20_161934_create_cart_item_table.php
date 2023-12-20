<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemTable extends Migration
{
    public function up()
    {
        Schema::create('cart_item', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('productId'); // Assuming 'id' in 'products' table is of type bigInteger
            $table->unsignedBigInteger('userId');    // Assuming 'id' in 'users' table is of type bigInteger
            $table->string('color');
            $table->string('size');
            $table->integer('quantity');
            $table->string('status');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('productId')->references('id')->on('product')->onDelete('cascade');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_item');
    }
}

