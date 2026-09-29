<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
 protected $guarded = ['id'];

    public function project(){
        return $this->belongsTo(Project::class);
    }

     public function creator(){
        return $this->belongsTo(User::class , 'creator_id');
    }

      public function assignee(){
        return $this->belongsTo(User::class , 'assignee_id');
    }
}
