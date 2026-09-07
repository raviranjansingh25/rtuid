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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->after('email');
            $table->string('profile')->after('mobile')->nullable();
            $table->string('wallet')->after('profile')->nullable();
            $table->string('refar_code')->after('wallet')->nullable();
            $table->string('user_refral')->after('refar_code')->nullable();
            $table->tinyInteger('status')->after('user_refral')->default(1)->comment('1:Active, 2:Deactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
          //
      });
    }
};
