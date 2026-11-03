<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\time_log;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function personaldashboard(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        // ۱. تمام تسک‌های تخصیص‌یافته به کاربر
        $myTasks = Task::where('assignee_id', $user->id)->get();

        // ۲. کارهای امروز (ددلاین امروز)
        $dueToday = Task::where('assignee_id', $user->id)
            ->whereDate('deadline', $now->toDateString())
            ->get();

        // ۳. تسک‌های عقب‌افتاده (Overdue)
        $overdue = Task::where('assignee_id', $user->id)
            ->where('deadline', '<', $now)
            ->whereHas('status', function ($query) {
                $query->where('is_completed', false);
            })
            ->get();

        // ۴. تسک‌های تکمیل‌شده در هفته جاری
        $completedThisWeek = Task::where('assignee_id', $user->id)
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->whereHas('status', function ($query) {
                $query->where('is_completed', true);
            })
            ->get();

        // ۵. زمان‌های ثبت‌شده این هفته (مجموع دقایق)
        $timeLoggedThisWeek = time_log::where('user_id', $user->id)
            ->whereBetween('logged_at', [$startOfWeek, $endOfWeek])
            ->sum('minutes');

        return response()->json([
            'status' => 'success',
            'data' => [
                'my_tasks_count' => $myTasks->count(),
                'due_today_count' => $dueToday->count(),
                'overdue_count' => $overdue->count(),
                'completed_this_week_count' => $completedThisWeek->count(),
                'time_logged_this_week_minutes' => $timeLoggedThisWeek,
                'time_logged_this_week_hours' => round($timeLoggedThisWeek / 60, 2),
                'due_today_tasks' => $dueToday,
                'overdue_tasks' => $overdue,
            ]
        ]);
    }

    // داشبورد پروژه
  public function projectdashboard(Request $request, Project $project)
    {
        $tasks = $project->tasks()->get();
        $totalTasks = $tasks->count();
        
        $completedTasks = $tasks->filter(function ($task) {
            return $task->status && $task->status->is_completed;
        })->count();

        $inProgressTasks = $tasks->filter(function ($task) {
            return $task->status && !$task->status->is_completed;
        })->count();

        $now = Carbon::now();
        $overdueTasks = $tasks->filter(function ($task) use ($now) {
            return $task->deadline && Carbon::parse($task->deadline)->isPast() && $task->status && !$task->status->is_completed;
        })->count();

        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        $estimatedTime = $tasks->sum('estimated_time');
        $loggedTime = time_log::whereIn('task_id', $tasks->pluck('id'))->sum('minutes');

        // حل اعضا بدون نیاز به جدول واسط و با کمک جدول تسک‌ها
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
                'overdue_tasks' => $overdueTasks,
                'progress_percentage' => $progressPercentage,
                'estimated_time_minutes' => $estimatedTime,
                'logged_time_minutes' => $loggedTime,
                'logged_time_hours' => round($loggedTime / 60, 2),
                'members_summary' => $membersSummary,
            ]
        ]);
    }
}