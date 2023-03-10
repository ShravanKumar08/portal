<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarksInInterviewcall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interview_calls', function (Blueprint $table) {
            $table->longText('remarks')->nullable();
            $table->longText('present_company')->change();
            $table->longText('change_reason')->change();
            $table->longText('present_location')->change();
            $table->dropColumn('references');
        });

        Schema::table('interview_candidates', function (Blueprint $table) {
            $table->longText('permanent_location')->change();
            $table->longText('current_designation')->change();
            $table->longText('technology')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interview_calls', function (Blueprint $table) {
            $table->dropColumn(['remarks']);
            $table->text('present_company')->change();
            $table->text('change_reason')->change();
            $table->text('present_location')->change();
            $table->text('references')->nullable();
        });

        Schema::table('interview_candidates', function (Blueprint $table) {
            $table->text('permanent_location')->change();
            $table->text('current_designation')->change();
            $table->text('technology')->change();
        });
    }
}
