<?php

namespace App\Observers;

use App\Models\activity_log;
use App\Models\task;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    /**
     * Handle the task "created" event.
     */
    public function created(task $task): void
    {
        // activity_log::create([
        //     'user_id' => Auth::id(),
        //     'task_id' => $task->id,
        //     'action' => 'created',
        //     'description' => 'تسک ایجاد شد',
        //     'new_value' => $task->toArray(),
        // ]);
    }

    /**
     * Handle the task "updated" event.
     */
    public function updated(task $task): void
    {
        // $changes = $task->getChanges();
        // $originals = array_intersect_key($task->getOriginal(), $changes);
        // activity_log::create([
        //     'user_id' => Auth::id(),
        //     'task_id' => $task->id,
        //     'action' => 'updated',
        //     'description' => 'تسک اپدیت شد',
        //     'old_value' => $originals,
        //     'new_value' => $changes,
        // ]);
    }

    /**
     * Handle the task "deleted" event.
     */
    public function deleted(task $task): void
    {
        //
    }

    /**
     * Handle the task "restored" event.
     */
    public function restored(task $task): void
    {
        //
    }

    /**
     * Handle the task "force deleted" event.
     */
    public function forceDeleted(task $task): void
    {
        //
    }
}
