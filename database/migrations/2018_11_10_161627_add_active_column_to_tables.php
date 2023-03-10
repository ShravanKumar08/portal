<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveColumnToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->boolean('active')->nullable()->default(1);
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('active')->nullable()->default(1);
        });
        
        Schema::table('technologies', function (Blueprint $table) {
            $table->boolean('active')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        
        Schema::table('technologies', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
