<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('role_permission')->truncate();
        $dateTime = now();
        $data = [
            [
                'name' => 'Super Admin',
                'description' => 'Has access to all system features and settings.',
                'is_super_admin' => true,
                'created_by' => null,
                'created_at' => $dateTime
            ],
            [
                'name' => 'Admin',
                'description' => 'Admin can manage the system but with limited access compared to Super Admin.',
                'is_super_admin' => false,
                'created_by' => null,
                'created_at' => $dateTime
            ],
            [
                'name' => 'Manager',
                'description' => 'Manager can manage certain resources within the system.',
                'is_super_admin' => false,
                'created_by' => null,
                'created_at' => $dateTime
            ]
        ];
        $rolePermissionData = [
            [
                'role_id' => 2,
                'permission_id' => '1',
            ],
            [
                'role_id' => 2,
                'permission_id' => '2',
            ],
            [
                'role_id' => 2,
                'permission_id' => '3',
            ],
            [
                'role_id' => 2,
                'permission_id' => '4',
            ],
            [
                'role_id' => 3,
                'permission_id' => '1',
            ],
            [
                'role_id' => 3,
                'permission_id' => '2',
            ],
            [
                'role_id' => 3,
                'permission_id' => '3',
            ],
            [
                'role_id' => 3,
                'permission_id' => '4',
            ],

        ];
        DB::table('roles')->insert($data);
        DB::table('role_permission')->insert($rolePermissionData);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
