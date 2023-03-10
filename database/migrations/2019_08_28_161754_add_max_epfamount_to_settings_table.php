<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxEpfamountToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $model = \App\Models\Setting::where('name','PAYSLIP_CALCULATIONS')->first();
        $values = $model->value;
        $values['EPF_MAX'] = ['value' => '15000', 'title' => 'EPF Max', 'type' => 'Rs'];
        $model->value = $values;
        $model->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
