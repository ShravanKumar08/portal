<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlotsTableAndAddDaySlotColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officetimingslots', function (Blueprint $table) {
            $table->uuid('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->text('value');
        });

        $model = \App\Models\Officetimingslot::firstOrNew([
            'name' => 'Default Slot',
        ]);
        $model->value = \App\Models\Officetimingslot::$defaultSlots;
        $model->save();


        Schema::table('officetimings', function (Blueprint $table) {
            $table->text('slots');
            $table->dropColumn('value');
        });

        $slots = [];
        foreach (range(1, 31) as $day){
            $slots[$day] = $model->id;
        }

        $official_timings = \App\Models\Officetiming::all();

        foreach ($official_timings as $official_timing) {
            $official_timing->slots = $slots;
            $official_timing->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('officetimingslots');

        Schema::table('officetimings', function (Blueprint $table) {
            $table->text('value');
            $table->dropColumn('slots');
        });
    }
}
