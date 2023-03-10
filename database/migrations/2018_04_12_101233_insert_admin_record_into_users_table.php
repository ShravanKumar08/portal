<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAdminRecordIntoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

//        $user = \App\Models\User::firstOrNew([
//            'email' => 'admin@arkinfotec.com'
//        ]);
//        $user->name = 'Admin';
//        $user->password = bcrypt('123456');
//        $user->save();

        \App\Models\Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        \App\Models\Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'web',
        ]);

//        $user->syncRoles($role);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}
