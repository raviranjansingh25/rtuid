<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBestTestCertificateTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('best_test_batches')) {
            Schema::create('best_test_batches', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->string('district')->nullable();
                $table->date('exam_date')->nullable();
                $table->string('place')->nullable();
                $table->string('status')->default('applied'); // applied | graded
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('best_test_athletes')) {
            Schema::create('best_test_athletes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_id');
                $table->unsignedBigInteger('user_id');
                $table->string('belt_type');
                $table->string('grade')->nullable();
                $table->tinyInteger('certificate_ready')->default(0);
                $table->string('status')->default('applied'); // applied | graded
                $table->timestamps();

                $table->index(['batch_id', 'user_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('best_test_athletes');
        Schema::dropIfExists('best_test_batches');
    }
}
