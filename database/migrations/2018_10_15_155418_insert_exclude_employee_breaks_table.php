<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertExcludeEmployeeBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $setting = \App\Models\Setting::firstOrNew([
            'name' => 'EXCLUDE_EMPLOYEE_FROM_BREAKS',
        ]);
        $setting->value = '';
        $setting->fieldtype = 'multiselect';
        $setting->hint = 'You can exclude the break hours for the employees';
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
            'name' => 'EXCLUDE_EMPLOYEE_FROM_BREAKS',
        ])->forceDelete();
    }
}
