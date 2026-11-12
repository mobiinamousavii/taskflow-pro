<?php

namespace App\Http\Controllers;

use App\Models\project;
use App\Models\task;
use App\Models\task_dependencies;
use App\Models\workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Task_DependenciesController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, workspace $workspace, project $project, task $task)
    {
        $this->authorize('create', $task);
        $data = $request->validate([
            'depends_on_task_id' => [
                'required',
                Rule::exists('tasks', 'id')->where('project_id', $project->id),
            ]
        ]);
        if ($task->id == $data['depends_on_task_id']) {
            return response()->json([
                'message' => 'هیچ تسکی نمیتواند به خودش وابسته باشد',
            ]);
        }
        // dd("testing");
        $task->dependencies()->syncWithoutDetaching([(int) $data['depends_on_task_id']]);
        return response()->json([
            'status' => 'success',
            'message' => 'وابستگی با موفقیت ثبت شد',
            'dependencies' => $task->dependencies()->get(),
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(workspace $workspace, project $project, task $task, $dependency)
    {
        $this->authorize('delete', $task);
        // اگه بخام بر اساس تسک ایدی پاک کنم
        // $task->dependencies()->detach($dependency);

        // اگه بخام بر اساس ردیف خود وابستگی پاکش کنم
        DB::table('task_dependencies')
            ->where('id', $dependency)
            ->where('task_id', $task->id)
            ->delete();
        return response()->json([
            'message' => 'وابستگی تسک با موفقیت حذف شد',
        ]);
    }
}
