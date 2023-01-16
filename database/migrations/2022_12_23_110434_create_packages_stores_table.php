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
        Schema::create('packages_stores', function (Blueprint $table) {
         $table->id();
        $table->bigInteger('package_id')->unsigned();
         $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        $table->bigInteger('store_id')->unsigned();
        $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        $table->unsignedBigInteger('packagecoupon_id')->nullable();
        $table->foreign('packagecoupon_id')->references('id')->on('packagecoupons')->onDelete('cascade');
        $table->timestamp('start_at')->nullable();
        $table->timestamp('end_at')->nullable();
        $table->enum('periodtype',['6months','year'])->default('year');
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
        Schema::dropIfExists('packages_stores');
    }
};
