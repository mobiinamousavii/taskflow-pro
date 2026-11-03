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
        return $this->belongsTo(User::class, 'assignee_id');
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments()
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


    // query scopeمثل یه تابع کمکی و میانبرهای سفارشی داخل اون قالبه که کمک میکنه شرط های طولانی پایگاه داده
    // مثل where ها خلاصه و مرتب و همه جا قابل استفاده کنی
    // یه ابزاری داخل مدله واسه کدای جستجو و فیلتر

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeStatus($query, $statusId)
    {
        return $query->where('status_id', $statusId);
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo($query, $assigneeId)
    {
        return $query->where('assignee_id', $assigneeId);
    }

    public function scopeCreator($query, $creatorId)
    {
        return $query->where('creator_id', $creatorId);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->whereNull('completed_at');
    }

    public function scopeDueDate($query, $date)
    {
        return $query->whereDate('deadline', $date);
    }
}
