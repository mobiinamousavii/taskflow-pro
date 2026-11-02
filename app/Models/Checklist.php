<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['progress']; // برای اینکه درصد پیشرفت  JSON خروجی بشه

    public function tasks(){
        return $this->belongsTo(task::class);
    }

    public function items(){
        return $this->hasMany(ChecklistItem::class);
    }

    // برای محاسبه درصد پیشرفت چک لیست
    public function getProgressAttribute()
    {
        $total = $this->items()->count();
        if ($total === 0) {
            return 0;
        }

        $completedItems = $this->items()->where('is_completed', true)->count();

        return round(($completedItems / $total) * 100);
    }
}
