<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserIdColumnsEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->renameColumn('user_id', 'employee_id');
        });
        \DB::statement("ALTER TABLE reports CHANGE user_id employee_id char(36) NOT null");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->renameColumn('employee_id', 'user_id');
        });
        \DB::statement("ALTER TABLE reports CHANGE employee_id user_id char(36) NOT null");
    }
}
