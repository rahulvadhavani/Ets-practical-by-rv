<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{

    protected $guarded = ['id'];
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
