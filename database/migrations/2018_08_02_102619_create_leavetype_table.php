<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavetypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leavetypes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name', 100);
            $table->string('display_name', 100);
            $table->double('allowed_days', 8, 1);
            $table->boolean('paid')->default(0);
        });

        $model = \App\Models\Leavetype::firstOrNew([
            'name' => 'casual',
        ]);
        $model->display_name = 'Casual leave';
        $model->allowed_days = 12;
        $model->save();

        $model = \App\Models\Leavetype::firstOrNew([
            'name' => 'paid',
        ]);
        $model->display_name = 'Paid leave';
        $model->allowed_days = 12;
        $model->paid = 1;
        $model->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leavetypes');
    }
}
