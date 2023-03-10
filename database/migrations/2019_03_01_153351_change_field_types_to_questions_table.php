<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldTypesToQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->mediumText('name')->change();
            $table->mediumText('options')->nullable()->change();
        });

        if (Schema::hasColumn('platform_question', 'id')){
            Schema::table('platform_question', function (Blueprint $table) {
                $table->dropColumn('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('name')->change();
            $table->mediumText('options')->change();
        });

        if (Schema::hasColumn('platform_question', 'id') == false){
            Schema::table('platform_question', function (Blueprint $table) {
                $table->uuid('id')->primary()->first();
            });
        }
    }
}
