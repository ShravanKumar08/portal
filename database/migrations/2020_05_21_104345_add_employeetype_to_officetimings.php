<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeetypeToOfficetimings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('officetimings', function (Blueprint $table) {
            $table->char('employeetype', 1)->nullable()->comment('P => Permanent; T => Trainee')->default('P');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('officetimings', function (Blueprint $table) {
            $table->dropColumn(['employeetype']);
        });
    }
}
