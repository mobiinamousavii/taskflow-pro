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

}
