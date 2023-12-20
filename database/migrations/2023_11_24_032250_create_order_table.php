<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('cartItemIds'); // IDs of Cart Items
            $table->foreignId('userId')->constrained('users');// Foreign key to users table
            $table->text('deliveryAddress'); // Delivery Address
            $table->string('orderStatus'); // Status of the Order
            $table->dateTime('orderDate'); // Date of the Order
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('order');
    }
}