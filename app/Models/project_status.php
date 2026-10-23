<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class project_status extends Model
{
    protected $guarded = ['id'];

    public function task(){
        return $this->hasMany(task::class , 'status_id');
    }

        public function project(){
        return $this->belongsTo(project::class);
    }
}
