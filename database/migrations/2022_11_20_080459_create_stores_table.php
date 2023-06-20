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
            $table->string('store_name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('store_email')->unique()->nullable();
            $table->string('domain')->unique()->nullable();
            $table->string('icon')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('description')->nullable();
            $table->string('file')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('twiter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instegram')->nullable();
            $table->string('logo')->nullable();
            $table->string('entity_type')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->enum('verification_status',['pending','admin_waiting','accept','reject'])->default('pending');
            $table->timestamp('verification_date')->nullable();
            $table->enum('periodtype',['6months','year'])->default('year');
            $table->enum('status',['active','not_active'])->default('active');
            $table->enum('commercialregistertype',['commercialregister','maeruf'])->default('commercialregister');
            $table->timestamp('confirmation_date')->nullable();
            $table->string('link')->nullable();
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
