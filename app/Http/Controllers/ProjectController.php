<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\timelog;
use App\Models\workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(workspace $workspace)
    {
        // $projects = Project::whereHas('workspace.user', function ($query) {
        //     $query->where('users.id', Auth::id());
        // })->latest()->get();

        $projects = $workspace->projects()->latest()->get();

        return view('project.list', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'deadline' => 'required',
        ], [
            'workspace_id.required' => 'انتخاب فضای کاری الزامی است.',
            'workspace_id.exists'   => 'فضای کاری انتخاب شده معتبر نیست.',
            'name.required'         => 'وارد کردن نام پروژه الزامی است.',
            'name.max'              => 'نام پروژه نباید بیشتر از ۲۵۵ کاراکتر باشد.',
        ]);


        $project = Project::create([
            'workspace_id' => $validated['workspace_id'],
            'name'         => $validated['name'],
            'description'  => $validated['description'] ?? null,
            'created_by' => Auth::id(),
            'start_date' => now(),
            'deadline'   => $validated['deadline'],
        ]);

        // return redirect()
        //     ->route('workspaces.show', $project->workspace_id)
        //     ->with('success', 'پروژه جدید با موفقیت ایجاد شد!');


    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // $project->load('statuses');
        $totaltasks = $project->tasks()->count();
        $completedtasks = $project->tasks()->where('status', 'completed')->count();
        $progresspercentage = $totaltasks > 0 ? round($completedtasks / $totaltasks * 100) : 0;
        $taskid = $project->tasks()->pluck('id')->toArray();
        $totalloggedminutes = timelog::whereIn('task_id', $taskid)->sum('minutes');
        return view('project.show', compact('project', 'totalloggedminutes', 'progresspercentage'));
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
