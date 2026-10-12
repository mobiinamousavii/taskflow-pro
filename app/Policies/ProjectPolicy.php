<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;

class ProjectPolicy
{
    // برای پیدا کردن نقش کاربر
    private function workspaceRole(User $user, Workspace $workspace): ?string
    {
        return $workspace->users()
            ->where('user_id', $user->id)
            ->first()?->pivot->role;
    }

    private function isManager(User $user, Workspace $workspace): bool
    {
        return in_array($this->workspaceRole($user, $workspace), ['owner', 'admin'], true);
    }

    public function view(User $user, Project $project): bool
    {
        if ($this->isManager($user, $project->workspace)) {
            return true;
        }
        // Member و Viewer فقط اگر عضو خود پروژه باشند
        return $project->users()->where('user_id', $user->id)->exists();
    }

    public function create(User $user, Workspace $workspace): bool
    {
        return $this->isManager($user, $workspace);
    }

    public function update(User $user, Project $project): bool
    {
        // return $this->isManager($user, $project->workspace);
        // فقد ممبری که عضو همین پروژست
        if ($this->isManager($user, $project->workspace)) {
            return true;
        }

        $role = $this->workspaceRole($user, $project->workspace);

        return $role === 'member'
            && $project->users()->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->isManager($user, $project->workspace);
    }

    public function manageMembers(User $user, Project $project): bool
    {
       
        $workspace = $project->workspace;

 

        if (!$workspace) {
            return false;
        }
        $workspaceuser = $workspace->users()->where('user_id', $user->id)->first();

        if (!$workspaceuser) {
            return false;
        }
        $role = $workspaceuser->pivot->role ?? null;

        return in_array($role, ['owner', 'admin']);
    }
}
