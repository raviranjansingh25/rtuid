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
        Schema::create('practitioners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('dob');
            $table->string('age');
            $table->string('gender');
            $table->string('qualifications');
            $table->string('experience');
            $table->text('bio');
            $table->string('areas_of_intresres');
            $table->string('languages');
            $table->string('timezone');
            $table->text('address');
            $table->text('image');
            $table->text('video');
            $table->tinyInteger('status')->default(1)->comment('1:Active, 2:Deactive, 3:Delete');
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
        Schema::dropIfExists('practitioners');
    }
};
