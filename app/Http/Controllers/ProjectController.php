<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $project = Project::where('creator', Auth::id())->latest()->get();
        return view('project.list', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $workspace = workspace::all();
        $selectworkspace = $request->query('workspace_id');
        return view('project.create', compact('workspace', 'selectworkspace'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
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
        ]);

        return redirect()
            ->route('workspaces.show', $project->workspace_id)
            ->with('success', 'پروژه جدید با موفقیت ایجاد شد!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $projct=Project::load('tasks');
        return view('project.show' , compact('project'));
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
