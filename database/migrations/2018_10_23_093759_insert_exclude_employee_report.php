<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertExcludeEmployeeReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
            'name' => 'EXCLUDE_EMPLOYEE_FROM_REPORTS',
        ]);
        $setting->value = '';
        $setting->fieldtype = 'multiselect';
        $setting->hint = 'You can exclude the report generations for the employees';
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
            'name' => 'EXCLUDE_EMPLOYEE_FROM_REPORTS',
        ])->forceDelete();
    }
}
