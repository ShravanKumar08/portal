<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPayslipMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'PAYSLIP_CONTENT_MAIL',
        ]);
        $setting->value = ' ';
        $setting->fieldtype = 'textarea';
        $setting->emailtemplate = 1;
        $setting->hint = 'You can choose the payslip email content for employees';
        $setting->save();
        
        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'PAYSLIP_PDF_MAIL',
        ]);
        $setting->value = ' ';
        $setting->fieldtype = 'textarea';
        $setting->emailtemplate = 1;
        $setting->hint = 'You can choose the payslip email pdf for employees';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\Setting::whereIn([
            'name' => ['PAYSLIP_MAIL','PAYSLIP_PDF_MAIL'],
        ])->forceDelete();
    }
}