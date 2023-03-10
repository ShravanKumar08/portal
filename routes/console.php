<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('custom:create:admin', function () {
    $name = $this->ask('What is your name?');
    $email = $this->ask('What is your email?');

    if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $this->error("$email is not a valid email address");
        return;
    }

    $pass = $this->secret('What is the password?');

    $user = \App\Models\User::firstOrNew([
        'email' => $email
    ]);
    $user->name = $name;
    $user->password = bcrypt($pass);
    $user->save();

    $role = \App\Models\Role::firstOrCreate([
        'name' => 'admin',
        'guard_name' => 'web',
    ]);

    $user->syncRoles($role);

    $this->comment('Successfully created');
})->describe('You can create an admin');
