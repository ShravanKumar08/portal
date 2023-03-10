<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\CustomField;
use App\Models\Role;

class AddReferenceColumnCustomfield extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customfield = CustomField::firstOrCreate([
            'name' => 'interviewcall_reference',
            'label' => 'Reference',
            'field_type' => 'textarea',
            'model_type' => 'App\Models\InterviewCall',
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
        CustomField::where('name','interviewcall_reference')->delete();
    }
}
