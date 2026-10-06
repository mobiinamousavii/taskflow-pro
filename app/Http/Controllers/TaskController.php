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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // سلکت پروجکت ایدی یا سلکت پروجکت
        // dd($request->all());
        // $projects = Auth::user()->Projects ?? collect();
        $projects = Project::where('created_by' , Auth::id())->get();
        $selectedproject = $request->query('project_id');
        $statuses = collect();
        if ($selectedproject) {
            $project = Project::find($selectedproject);
        }
        $statuses = $project?->workspace?->statuses ?? collect();
        return view('task.create', compact('projects', 'selectedproject', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $task = Task::create($data);
        return redirect()->route('projects.show', $task->project_id)->with('success', 'تسک جدید با موفقیت انجام شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function updatestatus(Request $request, Task $task)
    {
        // اینجا برای اپدیت استاتوسه فقد
        // $task->authorize('update' , $task);
        // Gate::authorize('update', $task);

        $request->validate([
            'status_id' => 'required',
        ]);
        $task->update([
            'project_status_id' => $request->prject_status_id,
        ]);
        return back()->with('success', 'وضعیت تسک تغییر کرد');
    }

    public function update(Request $request, Task $task)
    {
        // $task->authorize('update', $task);
        //  Gate::authorize('update', $task);
        $task->update($request->only(['title', 'description', 'projct_status_id', ' due_data']));

        return redirect()->route('projects.show')->with('success', 'تسک بروزرسانی شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);
        $projectid = $task->project_id;
        $task->delete();
        return redirect()->route('project.show', $projectid)->with('success', 'تسک با موفقیت حذف شد');
    }

    public function timelogs(Request $request)
    {
        $data = $request->validate([
            'minuetes' => 'required',
            'description' => 'nullable',
            'logged_at' => 'required',
        ]);
        // $task->timelogs->create([
        //     'user_id' => Auth::user(),
        //     $data,
            
        // ]);
    }
}
