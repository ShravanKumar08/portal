<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToOfficetimingslotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('officetimingslots', function (Blueprint $table) {
            $table->string('bg_color', 50)->nullable();
            $table->string('text_color', 50)->nullable();
        });
        
        App\Models\Officetimingslot::query()->update([
            'bg_color' => '#008000',
            'text_color' => '#FFFFFF'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('officetimingslots', function (Blueprint $table) {
            $table->dropColumn('bg_color');
            $table->dropColumn('text_color');
        });
    }
}
