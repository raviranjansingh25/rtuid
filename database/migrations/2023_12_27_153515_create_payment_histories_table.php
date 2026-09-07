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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('vender_id');
            $table->string('user_id');
            $table->string('session_id')->nullable();
            $table->string('session_name')->nullable();
            $table->string('package_id')->nullable();
            $table->string('package_name')->nullable();
            $table->string('course_id')->nullable();
            $table->string('course_name')->nullable();
            $table->string('price');
            $table->string('tran_id')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('payment_histories');
    }
};
