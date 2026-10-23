<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeritCertificateTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('merit_certificate_batches')) {
            Schema::create('merit_certificate_batches', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->string('recipient_type', 20); // coach | referee
                $table->tinyInteger('template')->default(1); // 1 | 2
                $table->string('course_title');
                $table->string('place')->nullable();
                $table->string('date_from')->nullable(); // template 1 "from ..."
                $table->string('date_on')->nullable();   // template 2 "on ..."
                $table->date('issue_date')->nullable();
                $table->string('status')->default('issued');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('merit_certificates')) {
            Schema::create('merit_certificates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_id');
                $table->unsignedBigInteger('recipient_id');
                $table->string('serial_no')->nullable();
                $table->timestamps();

                $table->index(['batch_id', 'recipient_id']);
                $table->index(['recipient_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('merit_certificates');
        Schema::dropIfExists('merit_certificate_batches');
    }
}
