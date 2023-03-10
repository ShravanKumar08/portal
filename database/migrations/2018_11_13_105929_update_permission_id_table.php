<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePermissionIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $old_tech_id = @\App\Models\Technology::where('name', 'Permission')->first()->id;
        $new_tech_id = \App\Models\Technology::PERMISSION_UUID;

        $this->updateIds($new_tech_id, $old_tech_id);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $new_tech_id = \Faker\Provider\Uuid::uuid();
        $old_tech_id = \App\Models\Technology::PERMISSION_UUID;

        $this->updateIds($new_tech_id, $old_tech_id);
    }
    
    protected function updateIds($new_tech_id, $old_tech_id)
    {
        \DB::statement('update technologies set id = "'.$new_tech_id.'" where id = "'.$old_tech_id.'"');
        \DB::statement('update reportitems set technology_id = "'.$new_tech_id.'" where technology_id = "'.$old_tech_id.'"');
    }
}
