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
        Schema::create('packages_templates', function (Blueprint $table) {
          $table->id();
        $table->bigInteger('package_id')->unsigned();
         $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        $table->bigInteger('template_id')->unsigned();
        $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages_templates');
    }
};
