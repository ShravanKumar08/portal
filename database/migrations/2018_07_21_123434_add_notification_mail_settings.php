<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationMailSettings extends Migration
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
            'name' => 'PERMISSION_NOTIFICATION_MAIL'
        ]);
        $model->fieldtype = 'multiselect';
        $model->value = $values;
        $model->save();

        $model = \App\Models\Setting::firstOrNew([
            'name' => 'LEAVE_NOTIFICATION_MAIL'
        ]);
        $model->fieldtype = 'multiselect';
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
        \App\Models\Setting::whereIn('name', ['PERMISSION_NOTIFICATION_MAIL', 'LEAVE_NOTIFICATION_MAIL'])->forceDelete();
    }
}
