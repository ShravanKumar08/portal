<?php

use App\Models\CustomField;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldTypeIncrementToEmployeeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CustomField::where('name','employee_deviceuserid')->update(['field_type' => 'increment']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CustomField::where('name','employee_deviceuserid')->update(['field_type' => 'text']);
    }
}
