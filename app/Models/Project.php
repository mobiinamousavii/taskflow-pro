<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date'
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
        return $this->belongsTo(workspace::class, 'workspace_id');
    }

       public function status()
    {
        return $this->hasMany(project_status::class);
    }
    
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_users' , 'project_id' , 'user_id')->withPivot('role_id')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users' , 'project_id' , 'user_id')->withPivot('role_id')->withTimestamps();
    }
}
