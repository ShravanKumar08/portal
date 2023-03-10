<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewCallTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_calls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->softDeletes();
            $table->timestamps();
            $table->date('schedule_date')->nullable();
            $table->string('references')->nullable();
            $table->string('present_company');
            $table->string('change_reason');
            $table->string('present_location');
            $table->uuid('interview_candidate_id');
            $table->string('experience');
            //$table->foreign('interview_candidate_id')->references('id')->on('interview_candidate');
            $table->uuid('interview_status_id')->nullable();
            //$table->foreign('interview_status_id')->references('id')->on('interview_status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_calls');
    }
}
