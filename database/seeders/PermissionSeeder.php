<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cache::forget('permissions_cache');
        $modules = ['customers', 'suppliers', 'users', 'roles'];
        $operations = ['create', 'update', 'show', 'delete'];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        $data = [];
        foreach ($modules as $module) {
            foreach ($operations as $operation) {
                $data[] = [
                    'name' => ucfirst($module) . ' ' . ucfirst($operation),
                    'slug' => "{$module}.{$operation}",
                    'module' => ($module),
                    'description' => ucfirst($operation) . " permission for " . ucfirst($module),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        if (!empty($data)) {
            DB::table('permissions')->insert($data);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
