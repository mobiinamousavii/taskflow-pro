<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\workspace;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectExportController extends Controller
{
    public function exportcsv(workspace $workspace, Project $project): StreamedResponse
    {

        $fileName = 'project-' . $project->id . '-tasks-' . now()->format('Y-m-d') . '.csv';

        $tasks = $project->tasks()
            ->with(['status', 'assignee'])
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Task', 
            'Status', 
            'Priority', 
            'Assignee', 
            'Estimated Time', 
            'Logged Time', 
            'Deadline'
        ];

        $callback = function() use ($tasks, $columns) {
            $file = fopen('php://output', 'w');
            
            // برای پشتیبانی از حروف فارسی در اکسل
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // درج هدر ستون‌ها
            fputcsv($file, $columns);

            // درج داده‌های هر تسک
            foreach ($tasks as $task) {
                $estimated = $task->estimated_hours ?? 0;
                $loggedMinutes = $task->timeLogs()->sum('minutes');
                $loggedHours = $loggedMinutes ? round($loggedMinutes / 60, 2) : 0;

                fputcsv($file, [
                    $task->title,
                    $task->status ? $task->status->name : 'N/A',
                    $task->priority ?? 'N/A',
                    $task->assignee ? $task->assignee->name : 'Unassigned',
                    $estimated,
                    $loggedHours,
                    $task->deadline ? optional($task->deadline)->format('Y-m-d H:i') : 'No Deadline',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}