<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model

{
    protected $guarded = ['id'];

    protected $casts = [
        'start_date'=>'date',
        'deadline' =>'date'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

       public function workspace()
    {
        return $this->belongsTo(workspace::class);
    }

      public function members(){
        return $this->belongsToMany(User::class , 'workspace_user')->withPivot('role')->withTimestamps();
     }

          public function users()
    {
        return $this->belongsToMany(User::class , 'project_users')->withPivot('role');
    }

}
