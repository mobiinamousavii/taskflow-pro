<?php

namespace App\Policies;

use App\Models\User;
use App\Models\workspace;

class WorkspacePolicy
{
    /**
     * Create a new policy instance.
     */
    private function workspaceRole(User $user, workspace $workspace): ?string
    {
        return $workspace->users()
            ->where('user_id', $user->id)
            ->first()?->pivot->role;
    }

    private function isManager(User $user, Workspace $workspace): bool
    {
        return in_array($this->workspaceRole($user, $workspace), ['owner', 'admin'], true);
    }

    // مشاهده ورک اسپیس مدیران برای کسایی که عضو ورک اسپیس باشن
    public function view($user, $workspace): bool
    {
        return $this->workspaceRole($user, $workspace) !== null;
    }

    // ساخت ورک اسپیس برای همه
    public function create($user): bool
    {
        return true;
    }

    // فقد مدیر و ادمین
    public function update($user, $workspace): bool
    {
        return $this->workspaceRole($user, $workspace);
    }

    // فقد مدیر
    public function delete(User $user, workspace $workspace): bool
    {
        $role = $this->workspaceRole($user, $workspace);
        return $role === 'owner';
    }

    public function managemembers($user, $workspace): bool
    {
        return $this->workspaceRole($user, $workspace);
    }
}
