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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
           $table->bigInteger('order_id')->unsigned();
           $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
           $table->bigInteger('product_id')->unsigned();
           $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
           $table->bigInteger('user_id')->unsigned();
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
           $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
           $table->decimal('price', 10, 2);
           $table->decimal('discount', 10, 2);
           $table->integer('quantity');
           $table->decimal('total_price', 10, 2);
           $table->longText('options')->nullable();
           $table->enum('order_status',['new','completed','delivery_in_progress','ready','canceled'])->default('new');
           $table->enum('payment_status', ['pending', 'paid', 'failed'])
            ->default('pending');

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
        Schema::dropIfExists('order_items');
    }
};
