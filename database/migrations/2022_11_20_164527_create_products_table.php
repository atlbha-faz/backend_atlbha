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
        Schema::create('products', function (Blueprint $table) {
          $table->id();
            $table->string('name');
           // $table->string('sku')->unique();
            $table->string('slug');
            $table->enum('for',['store','etlobha','stock'])->default('etlobha');
           $table->enum('special',['special','not_special'])->default('not_special');
            $table->longText('description');
            $table->boolean('mount')->default(0);
            $table->double('purchasing_price')->nullable();
            $table->double('selling_price');
            $table->integer('quantity')->nullable();
            $table->integer('less_qty')->nullable();
            $table->integer('stock')->nullable();
            $table->string('cover');
            $table->text("tags")->nullable();
            $table->double('discount_price')->default(0);
            $table->integer('discount_percent')->default(0);
            $table->string('SEOdescription')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->string('subcategory_id')->nullable();
           // $table->foreign('subcategory_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
};
