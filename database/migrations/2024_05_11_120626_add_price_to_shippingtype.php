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
        Schema::table('shippingtypes', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(30); 
            $table->integer('time')->default(4);
            $table->decimal('overprice', 10, 2)->default(3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shippingtypes', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('time');
            $table->dropColumn('overprice');
        });
    }
};
