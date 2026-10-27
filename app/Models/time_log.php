<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class time_log extends Model
{
    protected $guarded = ['id'];

    public function task()
    {
        return $this->belongsTo(time_log::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
