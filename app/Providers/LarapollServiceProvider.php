<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inani\Larapoll\Helpers\PollWriter;

class LarapollServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPollWriter();
    }

    /**
     * Boot What is needed
     *
     */
    public function boot()
    {
        $dir = base_path('vendor/inani/larapoll/src');
        
        // migrations
        $this->publishes([
            $dir. '/database/migrations/2017_01_23_115718_create_polls_table.php'
            => base_path('database/migrations/2017_01_23_115718_create_polls_table.php'),
            $dir. '/database/migrations/2017_01_23_124357_create_options_table.php'
            => base_path('database/migrations/2017_01_23_124357_create_options_table.php'),
            $dir. '/database/migrations/2017_01_25_111721_create_votes_table.php'
            => base_path('database/migrations/2017_01_25_111721_create_votes_table.php'),
        ]);
        // routes
//        include $dir . '/Http/routes.php';
        // views
        $this->loadViewsFrom(resource_path('vendor/larapoll'), 'larapoll');

        $this->publishes([
            $dir.'/config/config.php' => config_path('larapoll_config.php'),
        ]);
    }

    /**
     * Register the poll writer instance.
     *
     * @return void
     */
    protected function registerPollWriter()
    {
        $this->app->singleton('pollwritter', function ($app) {
            return new PollWriter();
        });
    }
}