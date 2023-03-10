<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBirthdayMailContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\Setting::where('name', 'BIRTHDAY_EMAIL_NOTIFICATION_CONTENT')->update(['value' => '{employee.name} is {employee.age} from {employee.tomorrow}<br> Let we ready to celebrate the birthday !!!']);
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
