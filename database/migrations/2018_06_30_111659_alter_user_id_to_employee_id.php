<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserIdToEmployeeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE userpermissions CHANGE user_id employee_id char(36)");
        \DB::statement("ALTER TABLE leaves CHANGE user_id employee_id char(36)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE userpermissions CHANGE employee_id user_id char(36)");
        \DB::statement("ALTER TABLE leaves CHANGE employee_id user_id char(36)");
    }
}
