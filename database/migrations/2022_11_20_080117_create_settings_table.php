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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->longText('description');
            $table->string('link');
            $table->string('email')->unique();
            $table->string('phonenumber')->nullable();
            $table->string('logo');
            $table->string('logo_footer');
            $table->string('icon');
            $table->string('address');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->enum('registration_status',['stop_registration','registration_with_admin','registration_without_admin'])->default('registration_without_admin');
            $table->enum('registration_marketer',['active','not_active'])->default('active');
            $table->enum('status_marketer',['active','not_active'])->default('active');
            $table->enum('status',['active','not_active'])->default('active');
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
        Schema::dropIfExists('settings');
    }
};
