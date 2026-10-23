<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(workspace $workspace,Project $project)
    {
        // اصلاح نام رابطه به tasks و استفاده از روابط صحیح assignee و creator
        $tasks = $project->tasks()->with(['assignee', 'creator', 'status'])->latest()->paginate(8);
        
        return response()->json([
            'status' => 'success',
            'data' => $tasks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        // dd($request->all());
        // اضافه کردن خودکار کاربر لاگین‌شده به عنوان ایجادکننده تسک
        $data = $request->validated();
        $data['creator_id'] = Auth::id();

        $task = Task::create($data);

        return response()->json([
            'message' => 'تسک با موفقیت ایجاد شد',
            'data' => $task->load(['assignee', 'creator', 'status']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(workspace $workspace, Project $project, Task $task)
    {
        $task->load(['assignee', 'creator', 'status']);
        
        return response()->json([
            'status' => 'success',
            'data' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, workspace $workspace,Project $project , Task $task)
    {
        $task->update($request->validated());

        return response()->json([
            'message' => 'تسک با موفقیت به‌روزرسانی شد',
            'data' => $task->load(['assignee', 'creator', 'status']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(workspace $workspace,Project $project, Task $task)
    {
        $task->delete();
        
        return response()->json([
            'message' => 'تسک با موفقیت حذف شد',
            'data' => $task,
        ]);
    }

    /**
     * Update task status (برای بورد کانبان و تغییر وضعیت سریع)
     */
    public function updateStatus(Request $request,workspace $workspace,Project $project, Task $task)
    {
        $request->validate([
            'status_id' => 'required|exists:project_statuses,id',
        ]);

        $task->update([
            'status_id' => $request->status_id,
        ]);

        return response()->json([
            'message' => 'وضعیت تسک با موفقیت تغییر کرد',
            'data' => $task->load('status')
        ]);
    }


    // goft harja virayesh task mojaz bod taghir position ya drag & drop ham mojaz
}