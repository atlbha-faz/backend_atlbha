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
        Schema::create('options_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('option_id')->unsigned();
        $table->foreign('option_id')
              ->references('id')
              ->on('options')->onDelete('cascade');
              $table->bigInteger('product_id')->unsigned();
        $table->foreign('product_id')
              ->references('id')
              ->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('options_products');
    }
};