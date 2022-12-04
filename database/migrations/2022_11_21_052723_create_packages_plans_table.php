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
        Schema::create('packages_plans', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('package_id')->unsigned();
         $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        $table->bigInteger('plan_id')->unsigned();
        $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages_plans');
    }
};
