<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $guarded = ['id'];

    public function tasks(){
        return $this->belongsTo(task::class);
    }

    public function checklistitem(){
        return $this->hasMany(ChecklistItem::class);
    }
}
