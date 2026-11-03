<?php

namespace App\Http\Controllers;

use App\Models\project;
use App\Models\task;
use App\Models\time_log;
use App\Models\workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


// تایم لاگ برای اینه که مشخص بشه هرکاربر چه مقدار زمانی روی هر تسک کار کرده
class Time_logController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(workspace $workspace,project $project, task $task)
    {
        $timelogs = $task->timelogs()->with('user')->latest()->get();
        $totalMinutes = $timelogs->sum('minutes');

        return response()->json([
            'status' => 'success',
            'totalminutes' => $totalMinutes,
            'totalhours' => round($totalMinutes / 60, 2),
            'data' => $timelogs,
        ]);
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

    // logged_atتاریخ و زمانیکه اینکار انجام گرفته 
    // 
    public function store(Request $request, workspace $workspace, project $project, task $task)
    {
        $validated = $request->validate([
            'minutes' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
            'logged_at' => 'nullable|date',
        ]);

        $timeLog = $task->timeLogs()->create([
            'user_id' => Auth::id(),
            'minutes' => $validated['minutes'],
            'description' => $validated['description'] ?? null,
            'logged_at' => $validated['logged_at'] ?? now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'زمان با موفقیت ثبت شد.',
            'data' => $timeLog
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
    public function destroy(workspace $workspace, project $project, task $task, time_log $timeLog)
    {
        $timeLog->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'زمان ثبت‌شده با موفقیت حذف شد.'
        ]);
    }
}
