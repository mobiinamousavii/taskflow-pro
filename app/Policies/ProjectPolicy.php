<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\workspace;
use Illuminate\Support\Facades\Request;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user,  $workspace = null)
    {
        if ($workspace && !$workspace instanceof \App\Models\workspace) {
            $workspace = workspace::findOrFail($workspace);
        }
        if (!$workspace)
            return false;
        $membership = $workspace->users()->where('user_id', $user->id)->first();
        // if (!$membership) return false;
        dd([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id ?? null,
            'membership_found' => (bool)$membership,
            'user_role' => $membership->pivot->role ?? 'none',
        ]);
        return $membership && in_array($membership->pivot->role, ['owner', 'admin']);
    }

    public function store(User $user, workspace $workspace, Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
        $project = $workspace->projects()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => $request->user()->id,
        ]);
        return response()->json([
            'message' => 'پروژه با موفقیت ساخته شد',
            'data' => $project,
        ]);
    }

    public function update(User $user, workspace $workspace,  Project $project)
    {
        $workspace = $project->workspace;
        $membership = $workspace->users()->where('user_id', $user->id)->first();
        if (!$membership) return false;
        return in_array($membership->pivot->role, ['owner', 'admin']);
    }

    public function delete(User $user, workspace $workspace, Project $project)
    {
        $workspace = $project->workspace;
        $membership = $workspace->users()->where('user_id', $user->id)->first();
        if (!$membership) return false;
        return in_array($membership->pivot->role, ['owner', 'admin']);
    }
}
