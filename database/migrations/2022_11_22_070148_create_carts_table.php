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

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->decimal('total', 10, 2)->nullable();
            $table->integer('count')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('shipping_price', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->integer('totalCount')->nullable();
            $table->enum('discount_type',['fixed','percent'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('discount_total', 10, 2)->nullable();
            $table->timestamp('discount_expire_date')->nullable();
            $table->boolean('free_shipping')->default(0);
            $table->bigInteger('is_deleted')->default(0);
            $table->string('message')->nullable();
            $table->timestamps();
            // $table->unique('user_id','store_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
