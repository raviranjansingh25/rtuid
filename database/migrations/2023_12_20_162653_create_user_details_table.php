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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id');
            $table->string('category');
            $table->string('qualification');
            $table->string('year_graduated');
            $table->string('graduate_doc');
            $table->string('organization_name');
            $table->string('organization_membership_no');
            $table->string('membership_number_doc');
            $table->string('upload_type');
            $table->string('upload_id');
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
        Schema::dropIfExists('user_details');
    }
};
