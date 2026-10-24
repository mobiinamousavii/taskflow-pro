<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attachment extends Model
{
    protected $guarded = ['id'];

    public function task(){
        return $this->belongsTo(task::class);
    }

       public function subtask(){
        return $this->belongsTo(subtask::class);
    }
}
