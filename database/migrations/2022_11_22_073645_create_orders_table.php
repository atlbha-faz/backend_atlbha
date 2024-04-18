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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('weight', 10, 2);
            $table->decimal('cod', 10, 2)->default(0);
            $table->string('description')->nullable();
            $table->decimal('shipping_price', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->integer('totalCount')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->enum('order_status', ['new', 'completed' ,'delivery_in_progress', 'ready', 'canceled', 'not_completed'])->default('new');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])
                ->default('pending');
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
        Schema::dropIfExists('orders');
    }
};
