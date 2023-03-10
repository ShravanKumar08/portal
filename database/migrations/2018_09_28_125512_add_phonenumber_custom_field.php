<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhonenumberCustomField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $field = \App\Models\CustomField::firstOrNew([
            'name' => 'employee_phonenumber'
        ]);
        $field->label = 'Phone';
        $field->model_type = \App\Models\Employee::class;;
        $field->formgroup = 'Other Details';
        $field->required = 1;
        $field->save();

        $field->roles()->sync(\App\Models\Role::all());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\CustomField::where('name', 'employee_phonenumber')->forceDelete();
    }
}
