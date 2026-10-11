<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\workspace;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(workspace $workspace, User $user, Project $project, Task $task)
    {
        $workspace = $task->project->workspace;
        $membership = $workspace->users()->where('user_id', $user->id)->first();
        if (!$membership) return false;
        $role = $membership->pivot->role;

        return in_array($role, ['admin', 'member']);
    }

    public function update(workspace $workspace, User $user, Project $project, Task $task)
    {
        $workspace = $task->project->workspace;
        $membership = $workspace->users()->where('user_id', $user->id)->first();
        if (!$membership) return false;
        $role = $membership->pivot->role;

        if ($role == 'admin') {
            return true;
        }
        // ممبر فقد تسکی که خودش ساخته یا بهش محول شده رو اپدیت کنه
        if ($role == 'member') {
            return $task->created_by === $user->id || $task->assignee_id === $user->id();
        }
        return false;
    }

    public function delete(workspace $workspace, User $user, Project $project, Task $task)
    {
        $workspace = $task->project->workspace;
        $membership = $workspace->users()->where('user_id', $user->id)->first();
        if (!$membership) return false;
        $role = $membership->pivot->role;

        if ($role == 'admin') {
            return true;
        }
        // ممبر فقد تسکی که خودش ساخته رو پاک کنه
        return $task->created_by === $user->id;
    }
}
