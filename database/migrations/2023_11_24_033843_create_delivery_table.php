<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orderId')->constrained('order')->onDelete('cascade');
            $table->string('deliveryManName')->nullable();
            $table->string('deliveryManPhone')->nullable();
            $table->string('deliveryCompany')->nullable();
            $table->timestamp('estimatedDeliveryDate')->nullable();
            $table->timestamp('actualDeliveryDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery');
    }
};
