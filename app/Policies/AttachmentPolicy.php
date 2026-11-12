<?php

namespace App\Policies;

use App\Models\User;
use App\Models\attachment;
use App\Models\project;
use App\Models\task;
use App\Models\workspace;
use Illuminate\Auth\Access\Response;

class AttachmentPolicy
{
      private function workspace()
    {
        return request()->route('workspace');
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user , workspace $workspace ,project $project , task $task): bool
    {
         return $user->hasWorkspacePermission($workspace , 'view_attachment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, attachment $attachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user , workspace $workspace ,project $project, task $task): bool
    {
         return $user->hasWorkspacePermission($workspace , 'create_attachment');
    }

    /**
     * Determine whether the user can update the model.  
     */
    public function update(User $user, attachment $attachment): bool
    {
         return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user,workspace $workspace, project $project,task $task, attachment $attachment): bool
    {
       $workspace = $attachment->task->project->workspace;

        return $user->id === $attachment->user_id || 
               $user->hasWorkspacePermission($workspace, 'delete_attachment');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, attachment $attachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, attachment $attachment): bool
    {
        return false;
    }
}
