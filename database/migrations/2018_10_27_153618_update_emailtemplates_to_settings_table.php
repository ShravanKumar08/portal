<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEmailtemplatesToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Setting::where('name', 'BIRTHDAY_EMAIL_NOTIFICATION_CONTENT')->update(['emailtemplate' => '1']);
        \App\Models\Setting::where('name', 'BIRTHDAY_EMAIL_CONTENT')->update(['emailtemplate' => '1']);
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
