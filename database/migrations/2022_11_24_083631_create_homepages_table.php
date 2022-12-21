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
        Schema::create('homepages', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('panar1');
            $table->enum('panarstatus1',['active','not_active'])->default('active');
            $table->string('panar2');
            $table->enum('panarstatus2',['active','not_active'])->default('active');
            $table->string('panar3');
            $table->enum('panarstatus3',['active','not_active'])->default('active');
            $table->enum('clientstatus',['active','not_active'])->default('active');
            $table->enum('commentstatus',['active','not_active'])->default('active');
            $table->string('slider1');
            $table->enum('sliderstatus1',['active','not_active'])->default('active');
            $table->string('slider2');
            $table->enum('sliderstatus2',['active','not_active'])->default('active');
            $table->string('slider3');
            $table->enum('sliderstatus3',['active','not_active'])->default('active');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
          
            $table->boolean('is_deleted')->default(0);
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
        Schema::dropIfExists('homepages');
    }
};