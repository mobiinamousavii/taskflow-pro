<?php

namespace App\Policies;

use App\Models\User;
use App\Models\workspace;
use Illuminate\Auth\Access\Response;

class WorkspacePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    // این متد قبل از همه متدا اجرا میشه 
    // اگه کاربر اونر باشه دسترسی مطلق به همه چی داره
    public function before(User $user, string $ability, workspace $workspace): ?bool
    {
        if ($workspace->owner->id === $user->id || $workspace->getuserrole($user) === 'owner') {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, workspace $workspace): bool
    {
        // مطمئن بشیم کاربر فقط ورک اسپیسایی که خودش ساخته یا به عنوان عضو بهش دعوت شده میبینه
        // سمت چپ  بررسی میکنه ایا کاربر فعلی اونر سازنده هست؟
        // سمت راست چک میکنه ایا کاربر تو جدول واسط  ورک اسپیس وجود داره
        return $workspace->userhaspermission($user , 'view_workspace');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, workspace $workspace): bool
    {
        // فقد اگه ایدی کاربر مساوی اونر بود بتونه true برگردونه
      return $workspace->userhaspermission($user , 'update_workspace');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, workspace $workspace): bool
    {
        // return $workspace->owner_id === $user->id;
        return false;
    }

    public function addmember(User $user, workspace $workspace): bool
    {
        return $workspace->userhaspermission($user , 'add_member');
    }


   public function changerole(User $user, workspace $workspace): bool
    {
        return $workspace->userhaspermission($user , 'changerole');
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
