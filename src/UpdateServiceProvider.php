<?php

namespace Cesargb\Update;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class UpdateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/update.php' => config_path('update.php'),
        ], 'config');
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\CheckUpdate::class,
                Commands\Update::class,
            ]);
        }
        
        $this->app->booted(function () {
            if (config('update.scheduler.enable', false)) {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('update:check', ['--notify'])->cron(config('update.scheduler.cron', '0 9 * * * *'));
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
