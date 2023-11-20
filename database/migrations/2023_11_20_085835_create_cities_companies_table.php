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
        Schema::create('shippingcities_shippingtypes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shippingtype_id')->unsigned();
            $table->foreign('shippingtype_id')->references('id')->on('shippingtypes')->onDelete('cascade');
            $table->bigInteger('shipping_city_id')->unsigned();
            $table->foreign('shipping_city_id')->references('id')->on('shipping_cities')->onDelete('cascade');

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
        Schema::dropIfExists('cities_companies');
    }
};
