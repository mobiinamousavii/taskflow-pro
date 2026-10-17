<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Task::class, 'task');
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $tasks = $project->task()->with('asignee_id', 'creator_id')->latest()->paginate(8);
        return response()->json($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $task = Task::create($data);


        
        return response()->json([
            'message' => 'success',
            'data' => $task,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project , Task $task)
    {
        $task->load(['asignee_id' , 'creator_id']);
        return response()->json([$task]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return response()->json([
            'message'=>'success',
            'data'=>$task,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $projectid = $task->project_id;
        $task->delete();
        return response()->json([
            'message'=>'تسک با موفقیت حذف شد',
            'data'=>$task,
        ]);
    }

    // 
        public function updatestatus(Request $request, Task $task)
    {
        $request->validate([
            'status_id' => 'required',
        ]);
        $task->update([
            'project_status_id' => $request->prject_status_id,
        ]);
        return back()->with('success', 'وضعیت تسک تغییر کرد');
    }

    // public function timelogs(Request $request)
    // {
    //     $data = $request->validate([
    //         'minuetes' => 'required',
    //         'description' => 'nullable',
    //         'logged_at' => 'required',
    //     ]);
    // }
}
