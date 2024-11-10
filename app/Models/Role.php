<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
    public function scopeNotAdmin($query)
    {
        return $query->where('is_super_admin', 0);
    }

    public static function  getRoles()
    {
        return self::select('id', 'name')->notAdmin()->get();
    }
}
