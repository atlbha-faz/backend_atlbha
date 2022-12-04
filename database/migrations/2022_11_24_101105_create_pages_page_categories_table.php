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
        Schema::create('pages_page_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id')->unsigned();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->bigInteger('page_category_id')->unsigned();
            $table->foreign('page_category_id')->references('id')->on('page_categories')->onDelete('cascade');
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
        Schema::dropIfExists('pages_page_categories');
    }
};