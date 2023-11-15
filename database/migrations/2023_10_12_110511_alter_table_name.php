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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('shippingtype_id')->nullable();
            $table->foreign('shippingtype_id')->references('id')->on('shippingtypes')->onDelete('cascade');
            $table->unsignedBigInteger('paymentype_id')->nullable();
            $table->foreign('paymentype_id')->references('id')->on('paymenttypes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('shippingtype_id')->nullable();
            $table->foreign('shippingtype_id')->references('id')->on('shippingtypes')->onDelete('cascade');
            $table->unsignedBigInteger('paymentype_id')->nullable();
            $table->foreign('paymentype_id')->references('id')->on('paymenttypes')->onDelete('cascade');
        });
    }
};
