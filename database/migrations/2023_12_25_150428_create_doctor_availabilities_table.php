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
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->string('vender_id');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('frequency');
            $table->string('once_date');
            $table->string('weeks');
            $table->string('month_date');
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
        Schema::dropIfExists('doctor_availabilities');
    }
};
