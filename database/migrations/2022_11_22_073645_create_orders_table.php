<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->double('total_price')->nullable();
            $table->double('tax')->nullable();
            $table->string('discount_type')->nullable();
            $table->double('discount')->nullable();
            $table->enum('order_status',['not_completed','completed','delivery_in_progress','ready','canceled','refund'])->default('not_completed');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('shipping_status', ['pending', 'delivery', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
