<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamsunitAuthTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'CAMSUNIT_AUTH_TOKEN',
        ]);
        $setting->value = ' ';
        $setting->fieldtype = 'text';
        $setting->hint = 'Setup Camsunit auth token';
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
            'name' => 'CAMSUNIT_AUTH_TOKEN',
        ])->forceDelete();
    }
}
