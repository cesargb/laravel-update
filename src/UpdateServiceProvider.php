<?php

namespace Cesargb\Update;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class UpdateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\CheckUpdate::class,
                Commands\Update::class,
            ]);
        }
        $this->app->booted(function () {
            if (config('update.scheduler.enable',false)) {
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
