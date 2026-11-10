<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;

class ProjectPolicy
{
    /**
     * 
     */
    public function viewAny(User $user, Workspace $workspace): bool
    {
        return $user->hasWorkspacePermission($workspace, 'view_projects');
    }

    /**
     *  
     */
    public function view(User $user, Project $project): bool
    {
        return $user->hasWorkspacePermission($project->workspace, 'view_projects');
    }

    /**
     * 
     */
    public function create(User $user, Workspace $workspace): bool
    {
        return $user->hasWorkspacePermission($workspace, 'create_projects');
    }

    /**
     *  
     */
    public function update(User $user, Project $project): bool
    {
        return $user->hasWorkspacePermission($project->workspace, 'edit_projects');
    }

    /**
     *
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->hasWorkspacePermission($project->workspace, 'delete_projects');
    }

        /**
     * 
     */
    public function getmembers(User $user, Workspace $workspace): bool
    {
        return $user->hasWorkspacePermission($workspace, 'getmembers_projects');
    }

    /**
     *  
     */
    public function addmembers(User $user, Project $project): bool
    {
        return $user->hasWorkspacePermission($project->workspace, 'addmembers_projects');
    }

    /**
     *
     */
    public function removemembers(User $user, Project $project): bool
    {
        return $user->hasWorkspacePermission($project->workspace, 'removemembers_projects');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, project $project): bool
    {
        return false;
    }
}
