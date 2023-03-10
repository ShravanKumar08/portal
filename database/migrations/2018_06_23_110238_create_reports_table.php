<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('user_id');
            $table->date('date');
            $table->time('start');
            $table->time('end')->nullable();
            $table->time('workedhours')->nullable();
            $table->time('breakhours')->nullable();
            $table->time('totalhours')->nullable();
            $table->enum('status', ['P', 'A', 'C'])->comments('P -> Pending, A -> Approved, C -> Completed')->default('P');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
