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
        Schema::table('websiteorders', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->string('paymentType')->nullable();
            $table->string('paymentTransectionID')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('websiteorders', function (Blueprint $table) {
            //
        });
    }
};
