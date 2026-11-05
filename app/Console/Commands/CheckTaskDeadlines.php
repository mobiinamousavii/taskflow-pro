<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskDeadlineNotification;
use App\Notifications\TaskOverdueNotification;
use Carbon\Carbon;

//command یه دستور اختصاصیه که خودم میسازم تا کدارو مستقیم از ترمینال یا خودکار تو بک گراند اجراکنه   
// برعکس کنترلر که وابسته به درخواست کاربره تا اجرا بشه
// کامند وابسته به کاربر و مرورگر نیست و مستقله. کدیه که مستقیم تو ترمینال اجرا میشه یا سر ساعت مشخصی خودکار صدا زده میشه
// اینجاهم برای تسک هایی که یکم مونده به تاریخ تحویل استفاده کردم
// داخل console.php هم یه زمانبندی گزاشتم
class CheckTaskDeadlines extends Command
{
    // نام دستوری که در ترمینال فراخونی میشه
    protected $signature = 'tasks:check-deadlines';

    // توضیحات کوتاه درباره کار این دستور
    protected $description = 'بررسی تسک‌های در حال انقضا و ارسال اعلان ددلاین';

    public function handle()
    {
        $now = Carbon::now();

        // تسک‌های نزدیک به ددلاین (تا ۲۴ ساعت آینده) و تموم نشده
        $upcomingTasks = Task::whereNull('completed_at')
            ->whereBetween('deadline', [$now, $now->copy()->addHours(24)])
            ->get();

        foreach ($upcomingTasks as $task) {
            if ($task->assignee) {
                $alreadySent = $task->assignee->notifications()
                    ->where('type', TaskDeadlineNotification::class)
                    ->where('data->task_id', $task->id)
                    ->where('created_at', '>=', Carbon::parse($task->deadline)->subHours(24))
                    ->exists();

                if (!$alreadySent) {
                    $task->assignee->notify(new TaskDeadlineNotification($task));
                }
            }
        }

        //تسک‌های Overdue: به مسئول اعلان و ایمیل بده (یک بار برای هر مهلت)
        $overdueTasks = Task::overdue()->get();

        foreach ($overdueTasks as $task) {
            if ($task->assignee) {
                $alreadySent = $task->assignee->notifications()
                    ->where('type', TaskOverdueNotification::class)
                    ->where('data->task_id', $task->id)
                    ->where('created_at', '>=', $task->deadline)
                    ->exists();

                if (!$alreadySent) {
                    $task->assignee->notify(new TaskOverdueNotification($task));
                }
            }
        }

        $this->info('ددلاین‌ها بررسی شد. تعداد تسک‌های Overdue: ' . $overdueTasks->count());
    }
}
