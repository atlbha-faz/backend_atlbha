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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->enum('discount_type', ['fixed', 'percent'])->default('percent');
            $table->double('total_price');
            $table->double('discount');
            $table->timestamp('expire_date')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->unsignedBigInteger('total_redemptions')->nullable();
            $table->unsignedBigInteger('user_redemptions')->nullable();
            $table->boolean('free_shipping')->default(0);
            $table->boolean('exception_discount_product')->default(0);
            $table->enum('status', ['active', 'not_active', 'expired'])->default('active');
            $table->bigInteger('is_deleted')->default(0);
            $table->enum('coupon_apply', ['all', 'selected_product', 'selected_category', 'selected_payment'])->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['code', 'store_id','is_deleted']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
