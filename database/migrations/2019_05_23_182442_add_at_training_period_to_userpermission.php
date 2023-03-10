<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAtTrainingPeriodToUserpermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userpermissions', function($table) {
            $table->boolean('at_training_period')->default(0);
        });

        Schema::table('leaves', function($table) {
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
        Schema::table('userpermissions', function($table) {
            $table->dropColumn('at_training_period');
        });
        Schema::table('leaves', function($table) {
            $table->dropColumn('at_training_period');
        });
    }
}
