<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\CustomField;
use App\Models\Role;

class AddCustomFieldInterviewRoundStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customfield = CustomField::firstOrCreate([
           'name' => 'interviewround_status',
           'label' => 'Status',
           'field_type' => 'select',
           'select_options' => 'Selected, Short-listed,Rejected,On-Hold',
           'model_type' => 'App\Models\InterviewRound',
        ]);

        $roles = Role::where('name','admin')->pluck('id');
        $customfield ->roles()->attach($roles);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CustomField::where('name','interviewround_status')->delete();
    }
}
