<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\project;
use App\Models\Task;
use App\Models\workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChecklistController extends Controller
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
    public function store(Request $request,workspace $workspace, project $project, Task $task)
    {
        $data=$request->validate([
            'title' => 'required|string',
        ]);
        $checklist = $task->checklist()->create([
            'title' => $data['title'],
            // 'task_id'=>$task->id,
            'is_complete'=>false,
        ]);
        return response()->json([
            'message' => 'چک لیست با موفقیت ایجاد شد',
            'data' => $checklist,
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
    public function update(Request $request, Checklist $checklist)
    {
        $data = $request->validate([
            'title' => 'required|string',
        ]);
        $checklist->update($data);

        return response()->json([
            'message' => 'چک لیست با موفقیت اپدیت شد',
            'data' => $checklist,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checklist $checklist)
    {
        $checklist->delete();
        return response()->json([
            'message'=>'چک لیست با موفقیت حذف شد',
        ]);
    }
}
