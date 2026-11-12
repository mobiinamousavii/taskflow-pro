<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;

class CommentPolicy
{
    /**
     *
     */
    public function viewAny(User $user, Workspace $workspace, Project $project, Task $task): bool
    {
        return $user->hasWorkspacePermission($workspace, 'comments.view');
    }

    /**
     * 
     */
    public function create(User $user, Workspace $workspace, Project $project, Task $task): bool
    {
        return $user->hasWorkspacePermission($workspace, 'comments.create');
    }

    /**
     * 
     */
    public function update(User $user, Workspace $workspace, Project $project, Task $task, Comment $comment): bool
    {
        $workspace = $comment->task->project->workspace;

        return $user->id === $comment->user_id || 
               $user->hasWorkspacePermission($workspace, 'comments.edit');
    }

    /**
     * 
     */
    public function delete(User $user, Workspace $workspace, Project $project, Task $task, Comment $comment): bool
    {
        $workspace = $comment->task->project->workspace;

        return $user->id === $comment->user_id || 
               $user->hasWorkspacePermission($workspace, 'comments.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, comment $comment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, comment $comment): bool
    {
        return false;
    }
}
