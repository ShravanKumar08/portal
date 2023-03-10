<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('experience');
            $table->text('other_info')->nullable();
        });

        Schema::create('exam_question', function (Blueprint $table) {
            $table->uuid('exam_id');
            $table->uuid('question_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_question');
    }
}
