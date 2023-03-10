<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportitems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('report_id');
            $table->uuid('project_id')->nullable();
            $table->uuid('technology_id')->nullable();
            $table->string('works')->nullable();
            $table->time('start')->nullable();
            $table->time('end')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('breaktime')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reportitems');
    }
}
