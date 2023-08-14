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
        Schema::create('atlobha_contacts', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('email');
            $table->string('title');
            $table->longText('content');
            $table->enum('status', ['finished', 'not_finished', 'pending'])->default('not_finished');
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
        Schema::dropIfExists('atlobha_contacts');
    }
};
