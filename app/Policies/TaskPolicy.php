<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;

class TaskPolicy
{
    /**
     *
     */
    public function viewAny(User $user, Workspace $workspace, Project $project): bool
    {
        return $user->hasWorkspacePermission($workspace, 'view_tasks');
    }

    /**
     * 
     */
    public function view(User $user, Workspace $workspace, Project $project, Task $task): bool
    {
        return $user->hasWorkspacePermission($workspace, 'view_tasks');
    }

    /**
     *  
     */
    public function create(User $user, Workspace $workspace, Project $project): bool
    {
        return $user->hasWorkspacePermission($workspace, 'create_tasks');
    }

    /**
     * 
     */
    public function update(User $user, Workspace $workspace, Project $project, Task $task): bool
    {
        return $user->hasWorkspacePermission($workspace, 'edit_tasks');
    }

    /**
     *
     */
    public function delete(User $user,Workspace $workspace,Project $project, Task $task): bool
    {
        // dd('test');
        // dd(
        //     $user->id,
        //     $task->project->workspace->id,
        //     $user->hasWorkspacePermission($task->project->workspace, 'delete_task')
        // );
        return $user->hasWorkspacePermission($task->project->workspace, 'delete_task');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, task $task): bool
    {
        return false;
    }
}
