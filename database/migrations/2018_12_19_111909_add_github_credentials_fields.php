<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGithubCredentialsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user_settings = \App\Models\UserSettings::where(['name' => 'GITHUB_CREDENTIALS'])->get();

        foreach ($user_settings as $user_setting) {
            $new_setting = json_decode($user_setting->value, true);
            unset($new_setting['filters']);
            $new_setting['showinreport'] = $new_setting['personalaccesstoken'] ? 1 : 0;
            $user_setting->value = json_encode($new_setting);
            $user_setting->save();
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
