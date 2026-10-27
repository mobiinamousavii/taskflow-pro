<?php

namespace App\Providers;

use App\Events\TaskActivityEvent;
use App\Listeners\LogTaskActivityListener;
use App\Models\task;
use App\Observers\TaskObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // task::observe(TaskObserver::class);
        Event::listen(
            TaskActivityEvent::class,
            LogTaskActivityListener::class
        );
    }
}
