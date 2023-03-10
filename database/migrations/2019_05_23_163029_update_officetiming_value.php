<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOfficetimingValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $timings = \App\Models\Officetimingslot::all();

        $defaultslot = \App\Models\Officetimingslot::$defaultSlots;

        foreach($timings as $timing)
        {
            $value = $timing->value;
            $value['minimum_permission_hours'] = $defaultslot['minimum_permission_hours'];

            $timing->value = $value;
            $timing->save();
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
