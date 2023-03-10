<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertOfficialtimingsToOfficetimingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $model = \App\Models\Officetiming::firstOrNew([
                    'name' => 'Default Timings',
        ]);
        $model->value = json_encode([
            "office_hours" => "08:10",
            "total_office_hours" => "09:40",
            "start" => "09:30",
            "end" => "19:10",
            "lt_start" => "10:05",
            "lt_perm" => "10:30",
            "lt_perm_max" => "11:30",
            "lt_half_day_excuse" => "11:45",
            "lt_half_day_grace" => "00:15",
            "lt_end" => "19:40",
            "permission_hours" => "02:00",
            "half_day_hours" => "04:00",
            "rp_grace" => "00:10",
            "break_hours" => "01:30"
        ]);
        $model->save();

        \App\Models\Employee::query()->update(['officetiming_id' => $model->id]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \App\Models\Officetiming::where([
            'name' => 'Default Timings',
        ])->forceDelete();
    }

}
