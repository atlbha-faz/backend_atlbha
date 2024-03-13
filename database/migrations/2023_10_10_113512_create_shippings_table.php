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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('shipping_id');
            $table->string('track_id')->nullable();
            $table->longText('sticker')->nullable();
            $table->longText('description')->nullable();
            $table->string('city')->nullable();
            $table->string('streetaddress')->nullable();
            $table->string('district')->nullable();
            $table->double('price')->nullable();
            $table->integer('quantity')->nullable();
            $table->double('weight')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('shippingtype_id')->unsigned();
            $table->foreign('shippingtype_id')->references('id')->on('shippingtypes')->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->enum('shipping_status',['new','completed','delivery_in_progress','ready','canceled','not_completed'])->default('new');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->boolean('cashondelivery')->default(0);
            $table->bigInteger('is_deleted')->default(0);
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
        Schema::dropIfExists('shippings');
    }
};
