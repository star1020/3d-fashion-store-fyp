<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
{
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orderId')->constrained('order');
            $table->foreignId('userId')->constrained('users'); 
            $table->string('transactionId')->unique();
            $table->string('paymentMethod');
            $table->timestamp('paymentDate')->useCurrent();
            $table->decimal('totalPaymentFee', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
