<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskDeadlineNotification;
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

        // ۱. بررسی تسک‌های نزدیک به ددلاین (تا ۲۴ ساعت آینده)
        $upcomingTasks = Task::where('status_id', '!=', 5)
            ->whereBetween('deadline', [$now, $now->copy()->addHours(24)])
            ->get();

        foreach ($upcomingTasks as $task) {
            if ($task->assignee) {
                $alreadySent = $task->assignee->notifications()
                    ->where('type', TaskDeadlineNotification::class)
                    ->where('data->task_id', $task->id)
                    ->where('created_at', '>=', Carbon::today())
                    ->exists();

                if (!$alreadySent) {
                    $task->assignee->notify(new TaskDeadlineNotification($task));
                }
            }
        }

        // ۲. شناسایی تسک‌های Overdue (گذشته از ددلاین و تکمیل‌نشده)
        $overdueTasks = Task::where('status_id', '!=', 5)
            ->where('deadline', '<', $now)
            ->get();

        foreach ($overdueTasks as $task) {
            // اینجا می‌توانید وضعیت تسک را به حالت منقضی‌شده تغییر دهید 
            // یا اگر خواستید برایشان نوتیفیکیشن یا لاگ جداگانه ثبت کنید:
            // $task->update(['status_id' => ...]);
        }

        $this->info('بررسی ددلاین‌ها و تسک‌های Overdue با موفقیت انجام شد.');
    }
        }
