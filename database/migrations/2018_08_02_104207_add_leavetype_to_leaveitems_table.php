<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeavetypeToLeaveitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaveitems', function (Blueprint $table) {
            $table->uuid('leavetype_id')->nullable();
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaveitems', function (Blueprint $table) {
            $table->dropColumn('leavetype_id');
            $table->char('type', 1)->nullable();
        });
    }
}
