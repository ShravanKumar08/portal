<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeCustomFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->string('default')->nullable();
        });

        $model = \App\Models\Employee::class;

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_fathername'
        ]);
        $field->label = 'Father name';
        $field->model_type = $model;
        $field->formgroup = 'Other Details';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_mothername'
        ]);
        $field->label = 'Mother name';
        $field->model_type = $model;
        $field->formgroup = 'Other Details';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_spousename'
        ]);
        $field->label = 'Spouse name';
        $field->model_type = $model;
        $field->formgroup = 'Other Details';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_skype'
        ]);
        $field->label = 'Skype';
        $field->model_type = $model;
        $field->formgroup = 'Other Details';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_passport'
        ]);
        $field->label = 'Passport';
        $field->model_type = $model;
        $field->formgroup = 'Other Details';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_pannumber'
        ]);
        $field->label = 'Pan Number';
        $field->model_type = $model;
        $field->formgroup = 'Other Details';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_aadhaarcardnumber'
        ]);
        $field->label = 'Aadhaar Card Number';
        $field->model_type = $model;
        $field->formgroup = 'Other Details';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_emergencycontactnumber'
        ]);
        $field->label = 'Emergency Contact Number';
        $field->model_type = $model;
        $field->formgroup = 'Other Details';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_joinedon'
        ]);
        $field->label = 'Joined on';
        $field->model_type = $model;
        $field->formgroup = 'Other Details';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_address'
        ]);
        $field->label = 'Address';
        $field->model_type = $model;
        $field->formgroup = 'Address';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_city'
        ]);
        $field->label = 'City';
        $field->model_type = $model;
        $field->formgroup = 'Address';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_state'
        ]);
        $field->label = 'State';
        $field->model_type = $model;
        $field->formgroup = 'Address';
        $field->default = 'Tamilnadu';
        $field->save();

        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_country'
        ]);
        $field->label = 'Country';
        $field->model_type = $model;
        $field->formgroup = 'Address';
        $field->default = 'India';
        $field->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            $table->dropColumn('default');
        });

        $fields = [
            'employee_fathername', 'employee_mothername', 'employee_spousename', 'employee_skype', 'employee_passport',
            'employee_pannumber', 'employee_aadhaarcardnumber', 'employee_emergencycontactnumber', 'employee_joinedon',
            'employee_address', 'employee_city', 'employee_state', 'employee_country'
        ];

        \App\Models\CustomField::whereIn('name', $fields)->forceDelete();
    }
}
