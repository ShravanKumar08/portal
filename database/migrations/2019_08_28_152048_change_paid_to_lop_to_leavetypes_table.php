<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePaidToLopToLeavetypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $leavetype = \App\Models\Leavetype::where('name','paid')->first();
        $leavetype->name = 'LOP';
        $leavetype->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $leavetype = \App\Models\Leavetype::where('name','LOP')->first();
        $leavetype->name = 'paid';
        $leavetype->save();
    }
}
