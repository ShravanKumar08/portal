<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaveitems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('leave_id');
            $table->date('date');
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
        Schema::dropIfExists('leaveitems');
    }
}
