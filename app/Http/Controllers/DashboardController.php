<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\workspace;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\time_log;
use App\Models\User;
use Carbon\Carbon;

// برای بهینه شدن دیگه از get استفاده نکردم
// بجاش از count برای شمارش رکوردها استفاده کردم
class DashboardController extends Controller
{
    public function personaldashboard(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        $myTasks = Task::where('assignee_id', $user->id)->count();

        $dueToday = Task::where('assignee_id', $user->id)
            ->whereDate('deadline', $now->toDateString())
            ->count();

        $overdue = Task::where('assignee_id', $user->id)
            ->where('deadline', '<', $now)
            ->whereHas('status', function ($query) {
                $query->where('is_completed', false);
            })
            ->count();

        $completedThisWeek = Task::where('assignee_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$startOfWeek, $endOfWeek])
            ->whereHas('status', function ($query) {
                $query->where('is_completed', true);
            })
            ->count();

        $timeLoggedThisWeek = time_log::where('user_id', $user->id)
            ->whereBetween('logged_at', [$startOfWeek, $endOfWeek])
            ->sum('minutes');

        return response()->json([
            'status' => 'success',
            'data' => [
                'My Tasks' => $myTasks->count(),
                'Due Today' => $dueToday->count(),
                'Overdue' => $overdue->count(),
                'Completed This Week' => $completedThisWeek->count(),
                'Time Logged This Week' => round($timeLoggedThisWeek / 60, 2) . ' hours',
            ]
        ]);
    }

    public function projectdashboard(Request $request, Project $project)
    {
        $tasks = $project->tasks()->with('status')->get();
        $totalTasks = $tasks->count();

        $completedTasks = $tasks->filter(function ($task) {
            return $task->status && $task->status->is_completed;
        })->count();

        $now = Carbon::now();

        // تسک‌های عقب‌افتاده (Overdue): ناتمومن و ددلاینشون گذشته 
        $overdueTasksCount = $tasks->filter(function ($task) use ($now) {
            return $task->deadline && Carbon::parse($task->deadline)->isPast() && $task->status && !$task->status->is_completed;
        })->count();

        // تسک‌های در حال پیشرفت (In Progress): 
        // استاتوسِ تکمیل‌شده ندارن و ددلاینشون نگذشته  (یعنی اوردو نیستن)
        $inProgressTasks = $tasks->filter(function ($task) use ($now) {
            $isCompleted = $task->status && $task->status->is_completed;
            $isOverdue = $task->deadline && Carbon::parse($task->deadline)->isPast();

            return !$isCompleted && !$isOverdue;
        })->count();

        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        $estimatedTimeHours = $tasks->sum('estimated_hours');
        $loggedMinutes = time_log::whereIn('task_id', $tasks->pluck('id'))->sum('minutes');

        $assigneeIds = $tasks->pluck('assignee_id')->unique()->filter();
        $membersSummary = User::whereIn('id', $assigneeIds)->get()->map(function ($user) use ($project) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'tasks_count' => Task::where('project_id', $project->id)
                    ->where('assignee_id', $user->id)
                    ->count(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'project_name' => $project->name,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'in_progress_tasks' => $inProgressTasks,
                // دیگه شامل تسک‌های اوردو (عقب‌افتاده) نمیشه
                'overdue_tasks' => $overdueTasksCount,
                'progress_percentage' => $progressPercentage,
                'estimated_time_hours' => $estimatedTimeHours,
                'logged_time_minutes' => $loggedMinutes,
                'logged_time_hours' => round($loggedMinutes / 60, 2),
                'members_summary' => $membersSummary,
            ]
        ]);
    }
}
