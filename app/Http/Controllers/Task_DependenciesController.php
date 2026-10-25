<?php

namespace App\Http\Controllers;

use App\Models\project;
use App\Models\task;
use App\Models\task_dependencies;
use App\Models\workspace;
use Illuminate\Http\Request;

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
    public function store(Request $request,workspace $workspace,project $project, task $task)
    {
        $data = $request->validate([
            'depends_on_task_id' => 'required|exists:tasks,id|different:task'
        ]);
        if ($task->id == $data['depends_on_task_id']) {
            return response()->json([
                'massage' => 'هیچ تسکی نمیتواند به خودش وابسته باشد',
            ]);
        }
        // dd("testing");
        $task->dependencies()->syncWithoutDetaching([ (int) $data['depends_on_task_id']]);
        return response()->json([
            'status' => 'succes',
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
    public function destroy(task $task)
    {
        $task->depentencies()->detaching()->delete();
        return response()->json([
            'message' => 'وابستگی تسک با موفقیت حذف شد',
        ]);
    }
}
