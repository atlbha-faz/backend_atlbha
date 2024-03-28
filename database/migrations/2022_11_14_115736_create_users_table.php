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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('user_name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('image')->nullable();
            $table->enum('user_type', ['admin', 'admin_employee', 'store', 'store_employee', 'customer', 'marketer'])->default('customer');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->integer('supplierCode')->nullable();
            $table->enum('status', ['active', 'not_active'])->default('active');
            // $table->boolean('status')->default(true);
            $table->bigInteger('is_deleted')->default(0);
            $table->string('device_token')->nullable();
            $table->boolean('verified')->default(0);
            $table->integer('code')->nullable();
            $table->timestamp('code_expires_at')->nullable();
            $table->integer('verify_code')->nullable();
            $table->timestamp('verify_code_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->unique(['user_name','user_type','is_deleted']);
            $table->unique(['email','user_type','is_deleted']);
            $table->unique(['phonenumber', 'user_type','is_deleted']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
