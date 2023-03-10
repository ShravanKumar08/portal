<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStatusColumnToReportitemsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    function up() {
        \DB::statement('ALTER TABLE reportitems MODIFY status Char (1) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \DB::statement('ALTER TABLE reportitems MODIFY status Char (1) NOT NULL;');
    }

}
