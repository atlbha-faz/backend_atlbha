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
        Schema::create('days_stores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('day_id')->unsigned();
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
           $table->bigInteger('store_id')->unsigned();
           $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
           $table->time('from')->nullable();
           $table->time('to')->nullable();
           $table->enum('status',['active','not_active'])->default('active');
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
        Schema::dropIfExists('days_stores');
    }
};
