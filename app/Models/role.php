<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{
       protected $guarded = ['id'];

    public function permissions(){
        return $this->belongsToMany(permission::class , 'role_permissions' , 'role' , 'permission_id');
    }
}
