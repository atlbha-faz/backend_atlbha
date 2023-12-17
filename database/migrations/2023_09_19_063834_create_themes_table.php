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
            $table->string('primaryBg')->default("#1dbbbe");
            $table->string('secondaryBg')->default("#02466a");
            $table->string('headerBg')->default("#1dbbbe");
            $table->string('layoutBg')->default("#ffffff");
            $table->string('iconsBg')->default("#1dbbbe");
            $table->string('productBorder')->default("#8235DC");
            $table->string('productBg')->default("#8235DC");
            $table->string('filtersBorder')->default("#8235DC");
            $table->string('filtersBg')->default("#8235DC");
            $table->string('mainButtonBg')->default("#8235DC");
            $table->string('mainButtonBorder')->default("#8235DC");
            $table->string('subButtonBg')->default("#8235DC");
            $table->string('subButtonBorder')->default("#8235DC");
            $table->string('footerBorder')->default("#ebebeb");
            $table->string('footerBg')->default("#ffffff");
            $table->string('fontColor')->default("#000");
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
