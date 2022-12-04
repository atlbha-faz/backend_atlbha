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
        Schema::create('marketers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->enum('gender',['male','female'])->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('snapchat');
            $table->string('facebook');
            $table->string('twiter');
            $table->string('whatsapp');
            $table->string('youtube');
            $table->string('instegram');
             $table->longText('socialmediatext');
            $table->enum('status',['active','not_active'])->default('active');
          
            $table->boolean("is_deleted")->default(0);
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
             $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');

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
        Schema::dropIfExists('marketers');
    }
};
