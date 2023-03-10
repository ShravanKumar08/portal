<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCamsunitSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $camsunit_setting = \App\Models\Setting::query()->where('name', 'CAMSUNIT_AUTH_TOKEN')->first();

        if($camsunit_setting){
            $token = $camsunit_setting->value;

            $camsunit_setting->fieldtype = 'multiselect';
            $camsunit_setting->value = ['token' => $token, 'status' => 1];
            $camsunit_setting->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $camsunit_setting = \App\Models\Setting::query()->where('name', 'CAMSUNIT_AUTH_TOKEN')->first();

        if($camsunit_setting){
            $token = @$camsunit_setting->value['token'];

            $camsunit_setting->fieldtype = 'text';
            $camsunit_setting->value = $token;
            $camsunit_setting->save();
        }
    }
}
