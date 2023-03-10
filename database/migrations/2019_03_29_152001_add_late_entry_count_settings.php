<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLateEntryCountSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'LATE_ENTRY_COUNT',
        ]);
        $setting->fieldtype = 'multiselect';
        $setting->value = ['count' => 9, 'perm_interval' => '3,6,9'];
        $setting->hint = 'You can set late entry count and permission interval';
        $setting->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\Setting::where([
            'name' => 'LATE_ENTRY_COUNT',
        ])->forceDelete();
    }
}
