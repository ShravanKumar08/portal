<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCalendarFilterTable extends Migration
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
            $setting = new \App\Models\UserSettings;
            $setting->user_id = $user->id;
            $setting->name =  'CALENDAR_LAST_VALUE' ;    
            $setting->value = '{"filters":["leaves","holidays","permissions"]}' ;    
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
            'name' => 'CALENDAR_LAST_VALUE',
        ])->forceDelete();
    }
}
