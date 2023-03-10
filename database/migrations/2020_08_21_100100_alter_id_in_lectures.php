<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIdInLectures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `lectures` CHANGE `id` `id`  char(36)');
//        DB::unprepared('ALTER TABLE `employee_lecture` DROP PRIMARY KEY');
        Schema::table('employee_lecture', function (Blueprint $table) {
            $table->dropColumn(['id','created_at', 'updated_at', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
