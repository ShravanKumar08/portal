<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThemeSettingForUsersSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = \App\Models\User::query()->has('employee')->where('active' , 1)->get();

        foreach($users as $user){
            $setting = new \App\Models\UserSettings;
            $setting->user_id = $user->id;
            $setting->name =  'THEME_COLOR' ;
            $setting->value = 'default-dark';
            $setting->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\UserSettings::where([
            'name' => 'THEME_COLOR',
        ])->forceDelete();
    }
}
