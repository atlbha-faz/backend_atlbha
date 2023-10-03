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
        Schema::create('seos', function (Blueprint $table) {
            $table->id();
            $table->string('google_analytics')->nullable();
            $table->text('snappixel')->nullable();
            $table->text('tiktokpixel')->nullable();
            $table->text('twitterpixel')->nullable();
            $table->text('instapixel')->nullable();
            $table->string('metatags')->nullable();
            $table->string('key_words');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->enum('status', ['active', 'not_active'])->default('active');
            $table->bigInteger('is_deleted')->default(0);
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
        Schema::dropIfExists('seos');
    }
};
