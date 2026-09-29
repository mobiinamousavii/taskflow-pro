<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class workspace extends Model
{
     protected $guarded = ['id'];

   public function users(){
        return $this->belongsToMany(User::class)->withPivot('role');
     }

     public function projects(){
        return $this->hasMany(Project::class);
     }

      public function owner(){
        return $this->belongsTo(User::class);
     }

      public function members(){
        return $this->belongsToMany(User::class , 'workspace_user')->withPivot('role')->withTimestamps();
     }
}
