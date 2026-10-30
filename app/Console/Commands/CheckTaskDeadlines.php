<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskDeadlineNotification;
use Carbon\Carbon;

class CheckTaskDeadlines extends Command
{
    // نام دستوری که در ترمینال فراخوانی می‌شود
    protected $signature = 'tasks:check-deadlines';

    // توضیحات کوتاه درباره کار این دستور
    protected $description = 'بررسی تسک‌های در حال انقضا و ارسال اعلان ددلاین';

    public function handle()
    {
        // پیدا کردن تسک‌هایی که کامل نشده‌اند و ددلاین آن‌ها تا ۲۴ ساعت آینده است
        $upcomingTasks = Task::where('status_id', '!=', 5)
            ->whereBetween('deadline', [Carbon::now(), Carbon::now()->addHours(24)])
            ->get();

        foreach ($upcomingTasks as $task) {
            if ($task->assignedUser) {
                // تریگر خودکار هشدار ددلاین برای کاربر مسئول تسک
                $task->assignedUser->notify(new TaskDeadlineNotification($task));
            }
        }

        $this->info('بررسی ددلاین‌ها انجام شد و نوتیفیکیشن‌ها ارسال شدند.');
    }
}