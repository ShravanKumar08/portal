<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'COMPANY_RULES',
        ]);
        $setting->value = 'Sunday will consider as a leave when you are taking leave for Saturday and Monday.';
        $setting->fieldtype = 'textarea';
        $setting->hint = 'You can change the company rules';
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
            'name' => 'COMPANY_RULES',
        ])->forceDelete();
    }
}
