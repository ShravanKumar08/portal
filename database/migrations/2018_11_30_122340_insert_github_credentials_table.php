<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\UserSettings;

class InsertGithubCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = \App\Models\User::where('active' , 1)->get();
        foreach($users as $user){
            $setting = new UserSettings;
            $setting->user_id = $user->id;
            $setting->name = UserSettings::GITHUB_CREDENTIALS ;    
            $setting->value = '{"username":"","personalaccesstoken":""}' ;    
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
            'name' => UserSettings::GITHUB_CREDENTIALS,
        ])->forceDelete();
    }
}
