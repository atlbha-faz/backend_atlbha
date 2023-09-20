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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('searchBorder')->default("#e5e5e5");
            $table->string('searchBg')->default("#ffffff");
            $table->string('categoriesBg')->default("#02466a");
            $table->string('menuBg')->default("#1dbbbe");
            $table->string('layoutBg')->default("#ffffff");
            $table->string('iconsBg')->default("#1dbbbe");
            $table->string('productBorder')->default("#ededed");
            $table->string('productBg')->default("#ffffff");
            $table->string('filtersBorder')->default("#f0f0f0");
            $table->string('filtersBg')->default("#ffffff");
            $table->string('mainButtonBg')->default("#1dbbbe");
            $table->string('mainButtonBorder')->default("#1dbbbe");
            $table->string('subButtonBg')->default("#02466a");
            $table->string('subButtonBorder')->default("#02466a");
            $table->string('footerBorder')->default("#ebebeb");
            $table->string('footerBg')->default("#ffffff");
            $table->unsignedBigInteger('store_id')->nullable();
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
        Schema::dropIfExists('themes');
    }
};
