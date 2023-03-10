<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertHintToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Setting::where('name', 'THEME_COLOR')->update(['hint' => 'You can change application theme color']);
        \App\Models\Setting::where('name', 'LOGO_LIGHT_ICON')->update(['hint' => 'You can change application logo in light shade']);
        \App\Models\Setting::where('name', 'LOGO_LIGHT_TEXT')->update(['hint' => 'You can change application logo text in light color']);
        \App\Models\Setting::where('name', 'LOGO_DARK_ICON')->update(['hint' => 'You can change application logo in dark shade']);
        \App\Models\Setting::where('name', 'LOGO_DARK_TEXT')->update(['hint' => 'You can change application logo text in dark color']);
        \App\Models\Setting::where('name', 'PERMISSION_NOTIFICATION_MAIL')->update(['hint' => 'You can assign emails to get permission request notification']);
        \App\Models\Setting::where('name', 'LEAVE_NOTIFICATION_MAIL')->update(['hint' => 'You can assign emails to get leave request notification']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Setting::where(['name' => 'THEME_COLOR',])->forceDelete();
//        Setting::where(['name' => 'LOGO_LIGHT_ICON',])->forceDelete();
//        Setting::where(['name' => 'LOGO_LIGHT_TEXT',])->forceDelete();
//        Setting::where(['name' => 'LOGO_DARK_ICON',])->forceDelete();
//        Setting::where(['name' => 'LOGO_DARK_TEXT',])->forceDelete();
//        Setting::where(['name' => 'PERMISSION_NOTIFICATION_MAIL',])->forceDelete();
//        Setting::where(['name' => 'LEAVE_NOTIFICATION_MAIL',])->forceDelete();
    }
}
