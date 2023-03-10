<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfficialPermissionHolidaysSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'OFFICIAL_PERMISSION_SATURDAYS',
        ]);
        $setting->value = json_encode(\App\Models\Setting::$default_official_permission);
        $setting->fieldtype = 'multiselect';
        $setting->hint = 'You can change official permission saturday weeks';
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
            'name' => 'OFFICIAL_PERMISSION_SATURDAYS',
        ])->forceDelete();
    }
}
