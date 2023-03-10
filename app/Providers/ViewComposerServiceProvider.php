<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer("*", "App\Http\ViewComposers\SettingsViewComposer");
        view()->composer("employee.*", "App\Http\ViewComposers\EmployeesViewComposer");
        view()->composer("trainee.*", "App\Http\ViewComposers\EmployeesViewComposer");
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
