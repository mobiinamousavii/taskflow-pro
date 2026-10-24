<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class workspace extends Model
{
   protected $guarded = ['id'];

   public function users()
   {
      return $this->belongsToMany(User::class, 'workspace_users', 'workspace_id', 'user_id')->withPivot('role_id');
   }

   public function projects()
   {
      return $this->hasMany(Project::class);
   }

   public function roles()
   {
      return $this->hasMany(role::class);
   }

   public function owner()
   {
      return $this->belongsTo(User::class);
   }

   public function members()
   {
      return $this->belongsToMany(User::class, 'workspace_user')->withPivot('role')->withTimestamps();
   }

    public function invitations()
   {
      return $this->hasMany(workspace_invitation::class);
   }

   public function status()
   {
      return $this->hasMany(project_status::class);
   }

   //   نقش فرذ از جدول ورک اسپیس یوزر
   //   اگه کاربر owner باشه
   public function getuserrole(User $user)
   {
      if ($this->owner_id === $user->id) {
         return 'owner';
      }

      //  نقش از جدول ورک اسپیس  یوزر
      $member = $this->users()->where('user_id', $user->id)->first();
      return $member ? $member->pivot->role : null;
   }

   // بررسی دسترسی ها بر اساس فایل پرمیشن کانفیگ
   public function userhaspermission(user $user, string $ability): bool
   {
      if ($this->owner_id === $user->id || $this->getuserrole($user) === 'owner') {
         return true;
      }
      $role = $this->getuserrole($user);
      if (!$role) {
         return false;
      }
      $rolepermissions = config("permissions.workspace_roles.{$role}", []);
      return in_array($ability, $rolepermissions);
   }
}
