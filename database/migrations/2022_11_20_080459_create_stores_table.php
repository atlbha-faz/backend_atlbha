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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            $table->string('store_name');
            $table->string('store_email')->unique();
            $table->string('domain');
            $table->string('icon')->nullable();
            $table->string('phonenumber');
            $table->string('description')->nullable();
            $table->string('business_license')->nullable();
            $table->string('ID_file')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twiter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instegram')->nullable();
            $table->string('logo')->nullable();
            $table->string('entity_type')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->enum('periodtype',['month','year'])->default('month');
            $table->enum('status',['active','not_active'])->default('active');
            $table->boolean('is_deleted')->default(0);
            $table->enum('special',['special','not_special'])->default('not_special');
            $table->enum('confirmation_status',['request','accept','reject','pending'])->default('request');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
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
        Schema::dropIfExists('stores');
    }
};
