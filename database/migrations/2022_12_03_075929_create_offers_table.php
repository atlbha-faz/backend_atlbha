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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->enum('offer_type',['If_bought_gets','fixed_amount','percent'])->default('If_bought_gets');
            $table->string('offer_title');
            $table->enum('offer_view',['store_website','store_application','both'])->default('store_website');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->integer('purchase_quantity')->nullable();
            $table->enum('purchase_type',['product','category','payment'])->nullable();
            $table->integer('get_quantity')->nullable();
             $table->enum('get_type',['product','category'])->nullable();
            $table->enum('offer1_type',['percent','free_product'])->nullable();
            $table->decimal('discount_percent')->nullable();
            $table->double('discount_value_offer2')->nullable();
            $table->enum('offer_apply',['all','selected_product','selected_category','selected_payment'])->nullable();
            $table->enum('offer_type_minimum',['purchase_amount','products_quantity'])->nullable();
            $table->decimal('offer_amount_minimum')->nullable();
            $table->boolean('coupon_status')->nullable();
            $table->double('discount_value_offer3')->nullable();
            $table->double('maximum_discount')->nullable();
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
        Schema::dropIfExists('offers');
    }
};
