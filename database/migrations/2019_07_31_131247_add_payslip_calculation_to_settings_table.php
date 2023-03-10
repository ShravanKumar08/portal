<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayslipCalculationToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $values = [
            'BP' => ['value' => '40', 'title' => 'Basic Pay', 'type' => '%'],
            'DA' =>  ['value' => '20', 'title' => 'Dearness Allowance', 'type' => '%'],
            'HRA' =>  ['value' => '25', 'title' => 'House Rent Allowance', 'type' => '%'],
            'EPF' =>  ['value' => '12', 'title' => 'EPF', 'type' => '%'],
            'ESI' =>  ['value' => '0.75', 'title' => 'ESI', 'type' => '%'],
            'ESI_GP' =>  ['value' => '21000', 'title' => 'ESI Gross Pay', 'type' => 'Rs'],

        ];

        $model = \App\Models\Setting::firstOrNew([
            'name' => 'PAYSLIP_CALCULATIONS',
        ]);

        $model->value = json_encode($values);
        $model->hint = 'You can assign payslip calculation amount..';
        $model->fieldtype = 'multiselect';

        $model->save();
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\Setting::where([
            'name' => 'PAYSLIP_CALCULATIONS',
        ])->forceDelete();
    }
}
