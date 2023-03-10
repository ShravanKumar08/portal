<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToReportitemsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('reportitems', function (Blueprint $table) {
            $table->char('status', 1)->comment('P ->Progress, C -> Completed, L -> Cancelled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('reportitems', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

}
