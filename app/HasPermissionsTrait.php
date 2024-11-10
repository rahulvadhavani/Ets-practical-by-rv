<?php

namespace App;

use App\Models\Permission;
use App\Models\Role;

trait HasPermissionsTrait
{
    // Check if the user has a specific permission, either directly or through a role
    public function hasPermissionTo($permissionSlug)
    {
        // Check if the user has the permission through any of their roles
        return $this->roles->some(function ($role) use ($permissionSlug) {
            return $role->permissions->contains('slug', $permissionSlug);
        });
    }
    public function hasPermissionToModule($permissionModule)
    {
        // Check if the user has the permission through any of their roles
        return $this->roles->some(function ($role) use ($permissionModule) {
            return $role->permissions->contains('module', $permissionModule);
        });
    }

    // Get all permissions the user has (direct + roles)
    public function getAllPermissions()
    {
        $permissions = $this->permissions;

        // Add permissions from roles
        $this->roles->each(function ($role) use ($permissions) {
            $permissions = $permissions->merge($role->permissions);
        });

        return $permissions->unique();
    }

    // Define relationship to permissions through roles
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    // Define relationship to roles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
