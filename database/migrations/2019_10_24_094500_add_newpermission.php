<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewpermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $slots = \App\Models\Officetimingslot::all();
        foreach($slots as $slot){
            $arr1 = $slot->value;
            $arr2 = array('morning_permission_hours' => '02:00');
            $slot->value = array_merge($arr1, $arr2);
            $slot->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
