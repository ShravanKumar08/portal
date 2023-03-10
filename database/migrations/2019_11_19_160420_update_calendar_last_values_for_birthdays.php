<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCalendarLastValuesForBirthdays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $calendar_settings = \App\Models\UserSettings::where(['name' => 'CALENDAR_LAST_VALUE'])->get();

        foreach ($calendar_settings as $calendar_setting) {
            $new_setting = json_decode($calendar_setting->value, true);
            $new_setting['filters'][] = 'birthdays';
            $calendar_setting->value = json_encode($new_setting);
            $calendar_setting->save();
        }
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
