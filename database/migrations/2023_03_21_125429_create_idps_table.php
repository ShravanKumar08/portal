<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->uuid('manager_id')->nullable();
            $table->uuid('mentor_id')->nullable();
            $table->longText('cv')->nullable();
            $table->longText('personal_motivation')->nullable();
            $table->longText('current_job_requirements')->nullable();
            $table->longText('goals')->nullable();
            $table->longText('assignments')->nullable();
            $table->longText('strengths')->nullable();
            $table->longText('development_needs')->nullable();
            $table->longText('development_action_plan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('idps');
    }
}
