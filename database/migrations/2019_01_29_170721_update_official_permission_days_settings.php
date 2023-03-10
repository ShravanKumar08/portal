<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOfficialPermissionDaysSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::where([
            'name' => 'OFFICIAL_PERMISSION_SATURDAYS',
        ])->first();

        if($setting){
            $setting->name = 'OFFICIAL_PERMISSION_LEAVE_DAYS';
            $setting->hint = 'You can change official leave/permission days for each weeks';

            $value = [];

            $default_permissions = \App\Models\Setting::$default_official_permission;

            foreach ($default_permissions as $k => $v) {
                $value['permission'][$k] = [
                    'dayOfWeek' => 6,
                    'value' => $v,
                ];
            }

            $default_leaves = \App\Models\Setting::$default_official_leave;

            foreach ($default_leaves as $k => $v) {
                $value['leave'][$k] = [
                    'dayOfWeek' => 6,
                    'value' => $v,
                ];
            }

            $setting->value = $value;
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
        \App\Models\Setting::where([
            'name' => 'OFFICIAL_PERMISSION_LEAVE_DAYS',
        ])->update([
            'name' => 'OFFICIAL_PERMISSION_SATURDAYS',
        ]);
    }
}
