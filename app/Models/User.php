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
        return $this->belongsToMany(workspace::class , 'workspace_users' , 'user_id' , 'workspace_id')->withPivot('role');
    }

     public function projects()
    {
        return $this->belongsToMany(Project::class );
    }

   

public function hasPermissionTo(string $permissionSlug, int $workspaceId): bool
{
    // ۱. گرفتن نقش کاربر در این ورک‌اسپیس خاص
    $role = DB::table('workspace_user')
        ->where('user_id', $this->id)
        ->where('workspace_id', $workspaceId)
        ->value('role');

    if (!$role) {
        return false;
    }

    // ۲. مالکان ورک‌اسپیس معمولاً تمام دسترسی‌ها رو دارند
    if ($role === 'owner') {
        return true;
    }

    // ۳. بررسی وجود پرمیشن برای این نقش و این ورک‌اسپیس
    return DB::table('role_permissions')
        ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
        ->where('role_permissions.role', $role)
        ->where('permissions.slug', $permissionSlug)
        ->where('permissions.workspace_id', $workspaceId)
        ->exists();
}
}
