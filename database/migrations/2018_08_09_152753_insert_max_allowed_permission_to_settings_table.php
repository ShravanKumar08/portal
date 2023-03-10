<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertMaxAllowedPermissionToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'MAX_ALLOWED_PERMISSION',
        ]);
        $setting->value = '3';
        $setting->hint = 'You can set Maximum allowed permission for employee per month';
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
            'name' => 'MAX_ALLOWED_PERMISSION',
        ])->forceDelete();
    }
}
