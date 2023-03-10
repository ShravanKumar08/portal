<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReportitemsAndTechnolgies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technologies', function (Blueprint $table) {
            $table->boolean('exclude')->comment('Exclude from work hours for reports');
        });

        Schema::table('reportitems', function (Blueprint $table) {
            $table->dropColumn('breaktime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technologies', function (Blueprint $table) {
            $table->dropColumn('exclude');
        });

        Schema::table('reportitems', function (Blueprint $table) {
            $table->boolean('breaktime');
        });
    }
}
