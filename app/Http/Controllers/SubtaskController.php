<?php

namespace App\Http\Controllers;

use App\Models\project;
use App\Models\subtask;
use App\Models\task;
use App\Models\workspace;
use Illuminate\Http\Request;

class SubtaskController extends Controller
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
        $data = $request->validate([
            'title' => 'required|string',
        ]);

        $subtasks = $task->subtask()->create([
            'title' => $data['title'],
            'is_complete' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'ساب تسک با موفقیت ایجاد شد',
            'data' => $subtasks,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(workspace $workspace, project $project, task $task)
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
    public function update(Request $request, workspace $workspace, project $project, task $task, subtask $subtask)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'is_complete'=>'sometimes|boolean'
        ]);

        $subtask->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'ساب تسک با موفقیت اپدیت شد',
            'data' => $subtask,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(subtask $subtask)
    {
        $subtask->delete();
        return response()->json([
            'message' => 'ساب تسک با موفقیت حذف شد',
        ]);
    }
}
