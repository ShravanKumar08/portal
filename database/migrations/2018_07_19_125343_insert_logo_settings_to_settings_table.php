<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertLogoSettingsToSettingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'LOGO_ICON',
        ]);
        $setting->value = '/assets/images/logo-icon.png';
        $setting->fieldtype = 'file';
        $setting->save();

        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'LOGO_TEXT',
        ]);
        $setting->value = '/assets/images/logo-text.png';
        $setting->fieldtype = 'file';
        $setting->save();

        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'LOGO_DARK_ICON',
        ]);
        $setting->value = '/assets/images/logo-icon.png';
        $setting->fieldtype = 'file';
        $setting->save();

        $setting = \App\Models\Setting::firstOrNew([
                    'name' => 'LOGO_DARK_TEXT',
        ]);
        $setting->value = '/assets/images/logo-text.png';
        $setting->fieldtype = 'file';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \App\Models\Setting::where([
            'name' => 'LOGO_ICON',
        ])->forceDelete();
        \App\Models\Setting::where([
            'name' => 'LOGO_TEXT',
        ])->forceDelete();
        \App\Models\Setting::where([
            'name' => 'LOGO_DARK_ICON',
        ])->forceDelete();
        \App\Models\Setting::where([
            'name' => 'LOGO_DARK_TEXT',
        ])->forceDelete();
    }

}
