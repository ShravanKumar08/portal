<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLeaveitemsTableMakeDetaultType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaveitems', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('leaveitems', function (Blueprint $table) {
            $table->string('type', 1)->comments('C-> Casual, P -> Paid')->nullable();
        });

        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'CASUAL_LEAVE_COUNT',
        ]);
        $setting->value = 12;
        $setting->hint = 'You can change casual leave count for employees';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaveitems', function (Blueprint $table) {
            $table->string('type', 1)->comments('C-> Casual, P -> Paid')->default('C')->change();
        });
    }
}
