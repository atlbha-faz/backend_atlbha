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
        Schema::table('packages_stores', function (Blueprint $table) {
            $table->string('paymentType')->nullable();
            $table->string('paymentTransectionID')->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->integer('coupon_id')->nullable();
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
        Schema::table('packages_stores', function (Blueprint $table) {
            //
        });
    }
};
