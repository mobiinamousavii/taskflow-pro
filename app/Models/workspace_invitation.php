<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class workspace_invitation extends Model
{
    protected $guarded = ['id'];

    public function workspace(){
        return $this->belongsTo(workspace::class);
    }

    public function role()
{
    return $this->belongsTo(Role::class, 'role_id');
}
}
