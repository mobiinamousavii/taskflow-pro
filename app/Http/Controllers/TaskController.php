<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

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
        $project=Project::all();
        $selectedproject=$request->query('project_id');
        return view('task.create' , compact('project' , 'selectedproject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $data=$request->validated();
        $task=Task::create($data);
        return redirect()->route('projects.show' , $task->project_id)->with('success' , 'تسک جدید با موفقیت انجام شد');
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
    public function update(Request $request, string $id)
    {
        //برای استاتوس
        $validated=$request->validated([
            'status'=>''
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $projectid=$task->project_id;
        $task->delete();
        return redirect()->route('project.show' , $projectid)->with('success' , 'تسک با موفقیت حذف شد');
    }
}
