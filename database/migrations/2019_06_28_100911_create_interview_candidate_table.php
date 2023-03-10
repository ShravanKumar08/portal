<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewCandidateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('interview_candidates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->softDeletes();
            $table->timestamps();
            $table->string('name','100');
            $table->string('email','100')->unique();
            $table->string('mobile','15');
            $table->string('resume')->nullable();
            $table->string('martial_status')->nullable();
            $table->string('permanent_location');
            $table->uuid('designation_id')->nullable();
            $table->string('gender', 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_candidates');
    }
}
