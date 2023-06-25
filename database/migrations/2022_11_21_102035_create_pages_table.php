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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('page_content');
            $table->string('seo_title')->nullable();
            $table->string('seo_link')->nullable();
            $table->longText('seo_desc')->nullable();
            $table->longText('page_desc')->nullable();
            $table->text('tags');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('postcategory_id')->nullable();
            $table->foreign('postcategory_id')->references('id')->on('postcategories')->onDelete('cascade');
           $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
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
        Schema::dropIfExists('pages');
    }
};
