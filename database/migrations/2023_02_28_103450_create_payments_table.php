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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamp('paymenDate')->nullable();
            $table->string('paymentType')->nullable();
            $table->string('paymentTransectionID')->nullable();
            $table->string('paymentCardID')->nullable();
            $table->integer('deduction')->nullable();
            $table->integer('price_after_deduction')->nullable();
            $table->integer('default_option')->nullable();
            $table->unsignedBigInteger('orderID')->nullable();
            $table->foreign('orderID')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
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
        Schema::dropIfExists('payments');
    }
};
