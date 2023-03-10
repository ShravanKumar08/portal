<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use \Carbon\Carbon;

class ResetPermissionLeave extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:reset:permissionleave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Reset Permission Leave Days";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $setting = Setting::where('name' , 'OFFICIAL_PERMISSION_LEAVE_DAYS')->first();
        
        $value = [];

            $default_permissions = Setting::$default_official_permission;

            foreach ($default_permissions as $k => $v) {
                $value['permission'][$k] = [
                    'dayOfWeek' => 6,
                    'value' => $v,
                ];
            }

            $default_leaves = Setting::$default_official_leave;

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
