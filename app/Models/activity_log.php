<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activity_log extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'old_value'=>'array',
        'new_value'=>'array',
    ];

    public function task(){
        return $this->belongsTo(task::class);
    }

   public function user(){
        return $this->belongsTo(User::class);
    }  
}
