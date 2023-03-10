<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('user_id');
            $table->date('start');
            $table->date('end');
            $table->double('days', 8, 1);
            $table->text('reason');
            $table->text('remarks')->nullable();
            $table->enum('status', ['P', 'A', 'D'])->comments('P -> Pending, A-> Approved, D -> Declined')->default('P');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaves');
    }
}
