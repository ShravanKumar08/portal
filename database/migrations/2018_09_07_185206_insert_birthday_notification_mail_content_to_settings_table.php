<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertBirthdayNotificationMailContentToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'BIRTHDAY_EMAIL_NOTIFICATION_CONTENT',
        ]);
        $setting->value = 'Let we ready to celebrate the birthday !!!';
        $setting->fieldtype = 'textarea';
        $setting->hint = 'You can change the birthday notification email content for employees';
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
            'name' => 'BIRTHDAY_EMAIL_NOTIFICATION_CONTENT',
        ])->forceDelete();
    }
}
