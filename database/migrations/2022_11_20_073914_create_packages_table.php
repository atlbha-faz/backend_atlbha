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
        Schema::create('packages', function (Blueprint $table) {
           $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->double('monthly_price');
            $table->double('yearly_price');
            $table->double('discount')->nullable()->default(0);
            $table->enum('status',['active','not_active'])->default('active');
            $table->bigInteger('is_deleted')->default(0);
            $table->unsignedBigInteger('trip_id')->nullable();
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
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
        Schema::dropIfExists('packages');
    }
};
