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
        Schema::create('offers_paymenttypes', function (Blueprint $table) {
            $table->id();
             $table->bigInteger('offer_id')->unsigned();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->bigInteger('paymenttype_id')->unsigned();
            $table->foreign('paymenttype_id')->references('id')->on('paymenttypes')->onDelete('cascade');
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
        Schema::dropIfExists('offers_paymenttypes');
    }
};
