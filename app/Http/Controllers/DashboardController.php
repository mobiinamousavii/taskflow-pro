<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'total_projects'  => Project::where('created_by', $userId)->count(),
            'pending_tasks'   => Task::where('assignee_id', $userId)->whereIn('status', ['todo', 'in_progress'])->count(),
            'completed_tasks' => Task::where('assignee_id', $userId)->where('status', 'done')->count(),
        ];

        $projects = Project::where('created_by', $userId)
            ->withCount('tasks')
            ->latest()
            ->take(4)
            ->get();

        $kanbanTasks = Task::where('assignee_id', $userId)
            ->with('project')
            ->get()
            ->groupBy('status');

        return view('dashboard', compact('stats', 'projects', 'kanbanTasks'));
    }
}