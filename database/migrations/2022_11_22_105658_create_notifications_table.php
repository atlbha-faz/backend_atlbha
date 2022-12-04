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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->longText('description');
            $table->timestamp('notification_time')->nullable();
            $table->enum('status',['active','not_active'])->default('active');
            $table->boolean('is_deleted')->default(0);
            $table->unsignedBigInteger('notificationtype_id')->nullable();
            $table->foreign('notificationtype_id')->references('id')->on('notification_types')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('notifications');
    }
};