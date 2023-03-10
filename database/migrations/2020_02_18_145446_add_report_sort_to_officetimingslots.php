<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportSortToOfficetimingslots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('officetimingslots', function (Blueprint $table) {
            $table->boolean('report_sort')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('officetimingslots', function (Blueprint $table) {
            $table->dropColumn(['report_sort']);
        });
    }
}
