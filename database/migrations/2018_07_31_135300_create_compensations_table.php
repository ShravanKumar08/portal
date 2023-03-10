<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompensationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compensations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('employee_id');
            $table->date('date');
            $table->double('days', 8, 1);
            $table->string('reason');
            $table->char('type', 1)->default('L')->comments('L -> Leave, P -> Permission');
        });

        Schema::create('compensates', function (Blueprint $table) {
            $table->uuid('compensation_id');
            $table->uuid('compensates_id');
            $table->string('compensates_type');
            $table->double('days', 8, 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compensations');
        Schema::dropIfExists('compensates');
    }
}
