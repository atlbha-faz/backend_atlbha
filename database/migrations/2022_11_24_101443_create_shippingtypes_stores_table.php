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
        Schema::create('shippingtypes_stores', function (Blueprint $table) {
            $table->id();
             $table->bigInteger('shippingtype_id')->unsigned();
         $table->foreign('shippingtype_id')->references('id')->on('shippingtypes')->onDelete('cascade');
        $table->bigInteger('store_id')->unsigned();
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
        Schema::dropIfExists('shippingtypes_stores');
    }
};
