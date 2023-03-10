<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
   
    public function run()
    {
        $user = new User();

        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@gmail.com';
        $user->password = Hash::make('123456');
        $user->save();

        $user->syncRoles('admin');
    }
   
}
