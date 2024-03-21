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
        Schema::create('paymenttypes_stores', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2);
            $table->integer('time')->nullable();
            $table->bigInteger('paymentype_id')->unsigned();
            $table->foreign('paymentype_id')->references('id')->on('paymenttypes')->onDelete('cascade');
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
        Schema::dropIfExists('paymenttypes_stores');
    }
};
