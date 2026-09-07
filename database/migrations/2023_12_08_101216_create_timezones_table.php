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
        Schema::create('timezones', function (Blueprint $table) {
            $table->id();
            $table->string('country_code')->nullable();
            $table->string('timezone');
            $table->string('gmt_offset')->nullable();
            $table->string('dst_offset')->nullable();
            $table->string('raw_offset')->nullable();
            $table->string('status')->default(1)->comment('1:Active, 2:Deactive, 3:Delete');
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
        Schema::dropIfExists('timezones');
    }
};
