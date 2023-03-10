<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeEmployeeIdNoyNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE leaves MODIFY employee_id char (36) NOT NULL;');
        \DB::statement('ALTER TABLE userpermissions MODIFY employee_id char (36) NOT NULL;');

        \DB::statement('ALTER TABLE leaveitems DROP end;');
        \DB::statement('ALTER TABLE leaveitems DROP reason;');
        \DB::statement('ALTER TABLE leaveitems DROP remarks;');
        \DB::statement('ALTER TABLE leaveitems DROP status;');

        Schema::table('leaveitems', function (Blueprint $table) {
            $table->char('type', 1)->default('C')->comments('C -> Casual, P-> Paid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE leaves MODIFY employee_id char (36) NULL;');
        \DB::statement('ALTER TABLE userpermissions MODIFY employee_id char (36) NULL;');

        Schema::table('leaveitems', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
