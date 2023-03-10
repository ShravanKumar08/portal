<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRoleSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'EMPLOYEE_ROLES',
            'fieldtype' => 'multiselect',
        ]);
        $setting->value = ['employee'];
        $setting->save();

        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'ADMIN_ROLES',
            'fieldtype' => 'multiselect',
        ]);
        $setting->value = ['admin'];
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\Setting::whereIn('name', ['ADMIN_ROLES', 'EMPLOYEE_ROLES'])->forceDelete();
    }
}
