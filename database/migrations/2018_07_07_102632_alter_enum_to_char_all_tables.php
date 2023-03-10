<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnumToCharAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE userpermissions MODIFY status char (1) NOT NULL DEFAULT 'P' COMMENT 'P -> Pending, A-> Approved, D -> Declined';");
        \DB::statement("ALTER TABLE leaves MODIFY status char (1) NOT NULL DEFAULT 'P' COMMENT 'P -> Pending, A-> Approved, D -> Declined';");
        \DB::statement("ALTER TABLE reports MODIFY status char (1) NOT NULL DEFAULT 'P' COMMENT 'P -> Pending, A-> Approved, D -> Declined';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE userpermissions MODIFY status enum ('P', 'A', 'D') NOT NULL DEFAULT 'P' COMMENT 'P -> Pending, A-> Approved, D -> Declined';");
        \DB::statement("ALTER TABLE leaves MODIFY status enum ('P', 'A', 'D') NOT NULL DEFAULT 'P' COMMENT 'P -> Pending, A-> Approved, D -> Declined';");
        \DB::statement("ALTER TABLE reports MODIFY status enum ('P', 'A', 'D') NOT NULL DEFAULT 'P' COMMENT 'P -> Pending, A-> Approved, D -> Declined';");
    }
}
