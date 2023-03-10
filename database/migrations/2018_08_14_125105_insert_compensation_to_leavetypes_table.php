<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCompensationToLeavetypesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $model = \App\Models\Leavetype::firstOrNew([
                    'name' => 'compensation',
        ]);
        $model->display_name = 'Compensation leave';
        $model->allowed_days = 0;
        $model->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \App\Models\Leavetype::where([
            'name' => 'compensation',
        ])->forceDelete();
    }

}
