<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    protected $guarded = ['id'];

    public function task(){
        return $this->belongsToMany(task::class);
    }

      public function user(){
        return $this->belongsToMany(User::class);
    }

      public function workspace(){
        return $this->belongsToMany(workspace::class);
    }

    //   public function comment(){
    //     return $this->belongsToMany(comment::class);
    // }
}


