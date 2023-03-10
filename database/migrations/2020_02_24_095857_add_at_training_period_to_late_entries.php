<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAtTrainingPeriodToLateEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('late_entries', function (Blueprint $table) {
            $table->boolean('at_training_period')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('late_entries', function (Blueprint $table) {
            $table->dropColumn(['at_training_period']);
        });
    }
}
