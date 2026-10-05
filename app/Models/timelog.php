<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class timelog extends Model
{
    protected $guarded = ['id'];

    public function tasks(){
        return $this->belongsTo(Task::class);
    }

       public function users(){
        return $this->belongsTo(User::class);
    }
}
