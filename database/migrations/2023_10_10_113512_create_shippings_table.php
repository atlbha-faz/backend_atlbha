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

            $table->string('destination_city')->nullable();
            $table->string('destination_streetaddress')->nullable();
            $table->string('destination_district')->nullable();
            
            $table->unsignedBigInteger('shippingtype_id')->nullable();
            $table->foreign('shippingtype_id')->references('id')->on('shippingtypes')->onDelete('cascade');

            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('quantity', 10, 2)->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->enum('shipping_status', ['new', 'completed', 'delivery_in_progress', 'ready', 'canceled', 'not_completed'])->default('new');
            $table->enum('shipping_type', [ 'send', 'return'])->default('send');

            $table->bigInteger('is_deleted')->default(0);
            $table->boolean('cashondelivery')->default(0);
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
