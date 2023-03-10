<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEmployeeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'fathername', 'mothername', 'spousename', 'address', 'country', 'state', 'city', 'phone', 'email',
                'skype', 'passport', 'panno', 'aadhaarno', 'emergencynumber', 'joindate'
            ]);
        });

        Schema::table('custom_fields', function (Blueprint $table) {
            $table->string('tags')->nullable()->change();
            $table->renameColumn('tags', 'formgroup');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('fathername', 100)->nullable();
            $table->string('mothername', 100)->nullable();
            $table->string('spousename', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('country', 60)->nullable();
            $table->string('state', 60)->nullable();
            $table->string('city', 60)->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('email', 60)->nullable();
            $table->string('skype', 60)->nullable();
            $table->string('passport', 60)->nullable();
            $table->string('panno', 60)->nullable();
            $table->string('aadhaarno', 60)->nullable();
            $table->string('emergencynumber', 60)->nullable();
            $table->date('joindate')->nullable();
        });

        Schema::table('custom_fields', function (Blueprint $table) {
            $table->text('formgroup')->nullable()->change();
            $table->renameColumn('formgroup', 'tags');
        });
    }
}
