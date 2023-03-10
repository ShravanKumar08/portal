<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertEmployeeDeviceIdCustomField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $field = \App\Models\CustomField::firstOrNew([
            'name' => \App\Helpers\CustomfieldHelper::EMPLOYEE_DEVICE_FIELD
        ]);
        $field->label = 'Device ID';
        $field->model_type = \App\Models\Employee::class;
        $field->formgroup = 'Other Details';
        $field->save();

        $roles = \App\Models\Role::whereIn('name', ['admin', 'super-user'])->get();

        $field->roles()->sync($roles);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\CustomField::where('name', \App\Helpers\CustomfieldHelper::EMPLOYEE_DEVICE_FIELD)->forceDelete();
    }
}
