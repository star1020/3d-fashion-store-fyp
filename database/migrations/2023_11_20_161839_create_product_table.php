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
        Schema::create('product', function (Blueprint $table) {
            $table->id(); 
            $table->string('productName');
            $table->string('productType');
            $table->string('productDesc');
            $table->string('productImgObj');
            $table->string('productTryOnQR')->nullable();;
            $table->string('category');
            $table->string('color');
            $table->string('size');
            $table->string('stock');
            $table->double('price');
            $table->tinyInteger('deleted');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
