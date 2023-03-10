<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('user_id');
            $table->string('name', 100)->nullable();
            $table->char('employeetype', 1)->nullable()->comment('P => Permanent; T => Trainee');
            $table->char('gender', 1)->nullable()->comment('M => Male; F => Female');
            $table->date('dob')->nullable();
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
            $table->string('designation_id', 100)->nullable();
            $table->date('joindate')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('employees');
    }

}
