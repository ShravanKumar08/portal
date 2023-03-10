<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLockColumnToReportitems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reportitems', function (Blueprint $table) {
            $table->boolean('lock')->default(0)->nullable();
        });

        \App\Models\Technology::firstOrCreate([
            'name' => 'Break',
            'exclude' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reportitems', function (Blueprint $table) {
            $table->dropColumn('lock');
        });

        \App\Models\Technology::where('name', 'Break')->forceDelete();
    }
}
