<?php

namespace App\Http\Controllers;

use App\Models\workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ورک اسپیس هایی که کاربر مالکشونه
        $workspaces = workspace::where('owner_id', Auth::id())->latest()->get();
        return view('workspace.list', compact('workspaces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('workspace.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'string',
            'max:255',
            'description' => 'nullable',
        ]);
        workspace::create([
            'owner_id' => Auth::id(),
            'name' =>$request->name,
            'description' =>$request->description,
        ]);
        return redirect()->route('workspaces.index')->with('success' , 'فضای کاری جدید ساخته شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $workspace=workspace::with('projects')->findOrFail($id);
         return view('workspace.show' , compact('workspace'));
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
