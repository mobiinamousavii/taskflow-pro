<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'datetime',
        'deadline' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function status()
    {
        return $this->belongsTo(project_status::class, 'status_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

    public function subtasks()
    {
        return $this->hasMany(subtask::class);
    }

    // برای تسک های پیش نیازه
    public function dependencies()
    {
        return $this->belongsToMany(task::class, 'task_dependencies', 'task_id', 'depends_on_task_id');
    }

    // برای تسک های منتظر
    // اونایی که blocked میشن و بعد از قفل درمیان
    public function dependents()
    {
        return $this->belongsToMany(task::class, 'task_dependencies', 'depends_on_task_id', 'task_id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachment()
    {
        return $this->hasMany(Attachment::class);
    }

    public function activity_log()
    {
        return $this->hasMany(activity_log::class);
    }

    public function timelogs()
    {
        return $this->hasMany(time_log::class);
    }

    public function notification()
    {
        return $this->hasMany(notification::class);
    }
}
