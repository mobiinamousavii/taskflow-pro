<?php

namespace App\Http\Controllers;

use App\Models\project;
use App\Models\task;
use App\Models\workspace;
use Illuminate\Http\Request;

//  هدفش اینه که تمام اتفاقات مهم پروژه و تسک رو بت کنه تا یه تایم لاین داشته باشم
// اینکه چه کسی چه کاری انجام داده
class Activity_logController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Workspace $workspace, Project $project, Task $task)
    {
        $activities = $task->activity_log()
                            ->with('user:id,name')
                            ->latest()
                            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'message' => 'تایم‌لاین فعالیت‌ها با موفقیت دریافت شد.',
            'data' => $activities
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    // برای اینکه کنترلر خلوت تر بشه از observer استفاد کردم
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
