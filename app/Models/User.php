<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_users', 'user_id', 'workspace_id')
                    ->withPivot('role_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_users', 'user_id', 'project_id');
    }

      public function comment()
    {
        return $this->hasMany(Comment::class);
    }

         public function attachment()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * بررسی اینکه آیا کاربر در این ورک‌اسپیس پرمیشن خاصی دارد یا خیر
     */
public function hasWorkspacePermission(Workspace $workspace, string $permissionName): bool
{
    $workspaceUser = $this->workspaces()
        ->where('workspaces.id', $workspace->id)
        ->first();

    if (!$workspaceUser || !$workspaceUser->pivot || !$workspaceUser->pivot->role_id) {
        return false;
    }

    $roleId = $workspaceUser->pivot->role_id;
    $role = Role::find($roleId);

    if (!$role) {
        return false;
    }

    // اگر نقش کاربر owner باشد، دسترسی کامل دارد
    if (strtolower($role->name) === 'owner') {
        return true;
    }

    $singularPermission = Str::singular($permissionName);
    $pluralPermission = Str::plural($permissionName);

    return DB::table('role_permissions')
        ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
        ->where('role_permissions.role_id', $roleId)
        ->where('role_permissions.workspace_id', $workspace->id)
        ->where(function ($query) use ($permissionName, $singularPermission, $pluralPermission) {
            $query->where('permissions.slug', $permissionName)
                  ->orWhere('permissions.slug', $singularPermission)
                  ->orWhere('permissions.slug', $pluralPermission);
        })
        ->exists();
}
}