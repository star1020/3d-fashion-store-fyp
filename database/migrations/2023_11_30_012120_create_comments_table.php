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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('product_id');
            $table->integer('rating');
            $table->string('review', 255);
            $table->string('image', 255)->nullable();
            $table->string('likes', 191)->nullable();
            $table->string('admin_reply', 255)->nullable(); // Varchar(255) NULL
            $table->timestamps();
            $table->boolean('deleted_at')->default(0); // Tinyint(4)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
