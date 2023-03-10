<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTempcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tempcards', function (Blueprint $table) {
            $table->dropColumn(['date']);
            $table->date('from');
            $table->date('to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tempcards', function (Blueprint $table) {
            $table->date('date');
            $table->dropColumn(['from']);
            $table->dropColumn(['to']);
        });
    }
}
