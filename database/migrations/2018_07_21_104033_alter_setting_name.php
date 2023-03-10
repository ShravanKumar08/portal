<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSettingName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Setting::where('name', 'LOGO_TEXT')->update(['name' => 'LOGO_LIGHT_TEXT']);
        \App\Models\Setting::where('name', 'LOGO_ICON')->update(['name' => 'LOGO_LIGHT_ICON']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\Setting::where('name', 'LOGO_LIGHT_TEXT')->update(['name' => 'LOGO_TEXT']);
        \App\Models\Setting::where('name', 'LOGO_LIGHT_ICON')->update(['name' => 'LOGO_ICON']);
    }
}
