<?php

namespace App\Policies;

use App\Models\User;
use App\Models\workspace;
use Illuminate\Auth\Access\Response;

class WorkspacePolicy
{
    private function workspace()
    {
        return request()->route('workspace');
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, workspace $workspace): bool
    {
        return $user->hasWorkspacePermission($workspace, 'view_workspaces');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, workspace $workspace): bool
    {
           return $user->hasWorkspacePermission($workspace, 'edit_workspaces');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, workspace $workspace): bool
    {
        // dd('inside policy');
        return $user->hasWorkspacePermission($workspace, 'delete_workspaces');
    }


    public function addMember(User $user, Workspace $workspace): bool
    {
        return $user->hasWorkspacePermission($workspace, 'add_member');
    }

    /**
     * بررسی دسترسی برای مدیریت نقش‌ها (ساخت و مشاهده نقش‌ها)
     */
    public function manageRoles(User $user, Workspace $workspace): bool
    {
        return $user->hasWorkspacePermission($workspace, 'manage_roles');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, workspace $workspace): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, workspace $workspace): bool
    {
        return false;
    }
}
