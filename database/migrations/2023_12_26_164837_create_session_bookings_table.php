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
        Schema::create('session_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('vender_id');
            $table->string('session_id');
            $table->string('booking_date');
            $table->string('session_name');
            $table->string('session_price');
            $table->string('doctor_name');
            $table->string('status')->default(1)->comment('1:Pending,2:Active,3:cancle');
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
        Schema::dropIfExists('session_bookings');
    }
};
