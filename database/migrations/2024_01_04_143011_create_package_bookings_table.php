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
        Schema::create('package_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('vender_id');
            $table->string('package_id');
            $table->string('package_name');
            $table->string('package_price');
            $table->string('no_of_conslt');
            $table->string('use_no_of_conslt');
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
        Schema::dropIfExists('package_bookings');
    }
};
