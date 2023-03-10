<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameQuestionPlatformToPlatformQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::rename('question_platform', 'platform_question');
            Schema::table('platform_question', function (Blueprint $table) {
                $table->dropColumn('id');
            });
            Schema::table('questions', function (Blueprint $table) {
                $table->integer('duration');
                $table->integer('opt_count');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            Schema::rename('platform_question','question_platform');
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('duration');
                $table->dropColumn('opt_count');
            });
            Schema::table('platform_question', function (Blueprint $table) {
                $table->uuid('id')->primary();
            });
    }
}
