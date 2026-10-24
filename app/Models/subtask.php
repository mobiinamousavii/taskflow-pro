<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subtask extends Model
{
    protected $guarded = ['id'];

    public function task(){
        return $this->belongsTo(task::class);
    }

       public function checklist(){
        return $this->belongsTo(Checklist::class);
    }
}
