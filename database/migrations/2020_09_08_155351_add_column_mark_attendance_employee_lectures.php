<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMarkAttendanceEmployeeLectures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_lecture', function (Blueprint $table) {
            $table->boolean('mark_attendance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_lecture', function (Blueprint $table) {
            $table->dropColumn(['mark_attendance']);
        });
    }
}
