<?php

namespace App\Policies;

use App\Models\User;
use App\Models\workspace;

class BasePolicy
{
    /**
     * Create a new policy instance.
     */
    // یه بیس پالیسی برای یسری چیزای تکراری که داخل هرکدوم از پالیسی های جدا ننویسمشون
    public function hasworkspacerole(User $user, workspace $workspace, array|string $roles): bool
    {
        $roles = (array) $roles;
        $userroles = $workspace->getuserrole($user);
        $workspaceuser = $workspace->users()->where('user_id', $user->id)->first;
        if (!$workspaceuser) {
            return false;
        }
        return in_array($workspaceuser->pivot->role , $roles , true);
    }
}
