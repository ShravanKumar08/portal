<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomFieldsAndCreateCustomFieldRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::rename('custom_fields', 'customfields');
//        Schema::rename('custom_field_values', 'customfieldvalues');
//        Schema::rename('late_entries', 'lateentries');

        Schema::create('custom_field_role', function (Blueprint $table) {
            $table->uuid('custom_field_id');
            $table->uuid('role_id');
        });

        $custom_fields = \App\Models\CustomField::all();
        $roles = \App\Models\Role::all();

        foreach ($custom_fields as $custom_field) {
            $custom_field->roles()->sync($roles);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::rename('customfields', 'custom_fields');
//        Schema::rename('customfieldvalues', 'custom_field_values');
//        Schema::rename('lateentries', 'late_entries');

        Schema::dropIfExists('customfield_role');
    }
}
