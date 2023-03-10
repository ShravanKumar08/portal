<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportNotificationMailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        $values = [
            'to' => 'admin@arkinfotec.com',
            'cc' => '',
            'bcc' => '',
        ];
        
        $model = \App\Models\Setting::firstOrNew([
               'name' => 'REPORT_NOTIFICATION_EMAIL',
        ]);
         
        $model->value = json_encode($values);
        $model->fieldtype = 'multiselect';
        $model->hint = 'You can assign emails to get daily reports sent by the employees..';
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
             'name' => 'REPORT_NOTIFICATION_EMAIL'
             ])->forceDelete();
    }
}
