<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    /**
     * دریافت اطلاعات کانبان
     *  ستونای پروژه همون وضعیت رو بهمراه تسکای داخل هر ستونو برای فرانت بفرسته
     */
    public function index(Workspace $workspace, Project $project)
    {
        // گرفتن وضعیت‌ها به همراه تسک‌های مرتب‌شده بر اساس position
        // کانبان بر اساس ورک اسپیس گرفته میشه  و با فیلتر پروژه هاشو میگیره 
        $statuses = $project->status()->with([
            'task' => function ($query) use ($project){
                $query->where('project_id' , $project->id)
                ->orderBy('position', 'asc');
            },
            'task.assignee',
            'task.creator',
            'task.status'
        ])->get();

        return response()->json([
            'status' => 'success',
            'data' => $statuses,
        ]);
    }

    /**
     * به‌روزرسانی وضعیت و موقعیت تسک هنگام جابه‌جایی در کانبان
     */
    public function updatePosition(Request $request, Workspace $workspace, Project $project, Task $task)
    {
        $request->validate([
            'status_id' => 'required|exists:project_statuses,id',
            'position' => 'required|integer|min:0',
        ]);

        $task->update([
            'status_id' => $request->status_id,
            'position' => $request->position,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'موقعیت و وضعیت تسک با موفقیت به‌روزرسانی شد.',
            'data' => $task->load('status')
        ]);
    }
}