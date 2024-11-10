<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            $permissions = Cache::remember('permissions_cache', 1440, function () {
                return Permission::get();
            });
            $permissions->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->is_super_admin ? true :  $user->hasPermissionTo($permission->slug);
                });
            });
            $modules = $permissions->pluck('module')->unique();
            $modules->map(function ($module) {
                Gate::define(strtolower($module), function ($user) use ($module) {
                    return $user->is_super_admin ? true :  $user->hasPermissionToModule($module);
                });
            });
        } catch (\Exception $e) {
            report($e);
        }
    }
}
