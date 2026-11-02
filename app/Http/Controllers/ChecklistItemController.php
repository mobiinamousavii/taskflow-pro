<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\project;
use App\Models\task;
use App\Models\workspace;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
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
    //  آیتم جدید به چک‌لیست
    public function store(Request $request, workspace $workspace, project $project, task $task, Checklist $checklist)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $item = $checklist->items()->create([
            'title' => $data['title'],
            'is_completed' => false,
        ]);

        return response()->json([
            'message' => 'آیتم با موفقیت اضافه شد',
            'data' => $checklist->load('items'), 
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
    public function destroy(workspace $workspace, project $project, task $task, Checklist $checklist, ChecklistItem $item)
    {
        $item->delete();

        return response()->json([
            'message' => 'آیتم حذف شد',
            'progress' => $checklist->fresh()->progress,
        ]);
    }

    // تغییر وضعیت تیک (Complete / Incomplete)
    public function tik(workspace $workspace, project $project, task $task, Checklist $checklist, ChecklistItem $item)

    {
        $item->update([
            'is_completed' => !$item->is_completed
        ]);

        return response()->json([
            'message' => 'وضعیت آیتم به‌روزرسانی شد',
            'data' => $item,
            'progress' => $checklist->fresh()->progress,
        ]);
    }
}
