<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuditableTableChangeUserIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE audits MODIFY user_id char (36);');
        \DB::statement('ALTER TABLE audits MODIFY user_type VARCHAR (191);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE audits MODIFY user_id char (36) NOT null;');
        \DB::statement('ALTER TABLE audits MODIFY user_type VARCHAR (191) not null ;');
    }
}
