<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;


    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function workspaces()
    {
        return $this->belongsToMany(workspace::class, 'workspace_users', 'user_id', 'workspace_id')->withPivot('role_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class , 'project_users' , 'project_id' , 'user_id');
    }


    // این بخش برای اینکه ببینم کاربر تو ورک اسپیس چیکارست
    public function hasWorkspacePermission(Workspace $workspace, string $permissionName)
    {
        //جدول واسط ورک اسپیس یوزر رو چک میکنه تا بفهمه این کاربر اصلا عضو
        // این ورک اسپیس هست یا نه و چه رول ایدی ای داره
        $workspaceUser = $this->workspaces()
            ->where('workspaces.id', $workspace->id)
            ->first();

        if (!$workspaceUser || !$workspaceUser->pivot->role_id) {
            return false;
        }

        // اطلاعات رول رو پیدا میکنه اگه رولش اونر بود سترسی مطلق
        $role = Role::find($workspaceUser->pivot->role_id);

        if (!$role) {
            return false;
        }

        if ($role->name === 'owner') {
            return true;
        }

        // dd([
        //     'workspace_ID' => $workspace->id,

        //     'permission name' => $permissionName,
        //      'database role permission'=>DB::table('role_permissions')
        //      ->where('workspace_id' , $workspace->id)->get()

        // ]);
        $roleid = $workspaceUser->pivot->role_id;
        $role = role::find($roleid);


        // اگه رولش عادی بود جدول رول پرمیشن کوئری میزنه  تا ببینه
        // این نقش تو این ورک اسپیس ایدی به این پرمیشن دسترسی داره یا نه
        // استفاده از whereIn به جای where تا هم مفرد و هم جمع پشتیبانی بشه
        return DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.workspace_id', $workspace->id)
            ->whereIn('role_permissions.role', [$role->name, $role->name . 's']) // چک کردن همزمان member و members
            ->where('permissions.slug', $permissionName)
            ->exists();
    }
}
