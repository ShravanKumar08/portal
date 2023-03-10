<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertBirthdayemailContentToSettingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'BIRTHDAY_EMAIL_CONTENT',
        ]);
        $setting->value = 'Wish u many many Happy returns of day, Have a every enjoyful moment in your life, Have delightful every second of every minutes, enjoy a day with happiness best luck for your brightness future.Best wishes for your enjoyful Birthday.';
        $setting->fieldtype = 'textarea';
        $setting->hint = 'You can change the birthday email content for employees';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \App\Models\Setting::where([
            'name' => 'BIRTHDAY_EMAIL_CONTENT',
        ])->forceDelete();
    }

}
