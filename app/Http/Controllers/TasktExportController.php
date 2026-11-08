<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\workspace;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectExportController extends Controller
{
    // csv ینی مقادیر جداشده با کاما
    // یه فایل متنی برای ذخیره کردن داده های جدول بندی شده مثل اطلاعات دیتابیس اکسل یا لیست تسک ها
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

        $columns = ['Task Title', 'Status', 'Priority', 'Assignee', 'Estimated_hours', 'Logged Time (h)', 'Deadline'];

        $callback = function() use ($tasks, $columns) {
            $file = fopen('php://output', 'w');
            
            //  برای پشتیبانی صحیح از کاراکترهای فارسی در اکسل
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            //  هدر ستون‌ها
            fputcsv($file, $columns);

            //  داده‌های هر تسک
            foreach ($tasks as $task) {
                fputcsv($file, [
                    $task->title,
                    $task->status ? $task->status->name : 'N/A',
                    $task->priority ?? 'N/A',
                    $task->assignee ? $task->assignee->name : 'Unassigned',
                    $task->estimated_hours ?? 0,
                    $task->timeLogs()->sum('minutes') ? round($task->timeLogs()->sum('minutes') / 60, 2) : 0,
                    $task->deadline ? $task->deadline->format('Y-m-d H:i') : 'No Deadline',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}