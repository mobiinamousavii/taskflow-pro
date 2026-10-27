<?php

namespace App\Listeners;

use App\Events\TaskActivityEvent;
use App\Models\activity_log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class LogTaskActivityListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskActivityEvent $event): void
    {
        // dd(Auth::id(),$event->task->id , $event->oldvalue ,$event->action);
        activity_log::create([
            'user_id' => Auth::id() ?? $event->task->user_id,
            'task_id' => $event->task->id,
            'action' => $event->action,
            'description' => 'تسک توسط کاربراپدیت شد',
            'old_value' => $event->oldvalue,
            'new_value' => $event->newvalue,
        ]);
    }
}
