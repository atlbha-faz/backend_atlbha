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
            $table->string('logo_footer');
            $table->string('banar1');
            $table->enum('banarstatus1',['active','not_active'])->default('active');
            $table->string('banar2');
            $table->enum('banarstatus2',['active','not_active'])->default('active');
            $table->string('banar3');
            $table->enum('banarstatus3',['active','not_active'])->default('active');
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
