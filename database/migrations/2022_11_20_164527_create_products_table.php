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
            $table->enum('for', ['store', 'etlobha', 'stock'])->default('etlobha');
            $table->enum('special', ['special', 'not_special'])->default('not_special');
            $table->enum('admin_special', ['special', 'not_special'])->default('not_special');
            $table->longText('description');
            $table->boolean('amount')->default(0);
            $table->boolean('product_has_options')->default(0);
            $table->decimal('purchasing_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2);
            $table->integer('quantity')->nullable();
            $table->integer('less_qty')->nullable();
            $table->integer('stock')->nullable();
            $table->string('cover');
            $table->text("tags")->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('SEOdescription')->nullable();
            $table->longText('robot_link')->nullable();
            $table->longText('google_analytics')->nullable();
            $table->decimal('weight', 10, 2)->default(0.5)->nullable();
            $table->longText('short_description');
            $table->text('snappixel')->nullable();
            $table->text('tiktokpixel')->nullable();
            $table->text('twitterpixel')->nullable();
            $table->text('instapixel')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->string('subcategory_id')->nullable();
            // $table->foreign('subcategory_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('original_id')->nullable();
            $table->boolean('is_import')->default(0);
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
        Schema::dropIfExists('products');
    }
};
