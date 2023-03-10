<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Interviewstatus;

class CreateInterviewStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_status', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->softDeletes();
            $table->timestamps();
            $table->string('name','100');
            $table->boolean('active')->nullable()->default(1);
        });

        $default_values = [
            'start',
            'on-hold',
            'canceled',
            'completed',
        ];

        foreach($default_values as $name){
            Interviewstatus::firstOrCreate([
                'name' => $name
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_status');
    }
}
