<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\UpdatePostStatusJob;
use Illuminate\Support\Facades\Log;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            Log::info('ScheduleServiceProvider is executing.');
            // Schedule the UpdatePostStatusJob to run every minute
            $schedule->job(new UpdatePostStatusJob())->everyMinute();
        });
    }
}
