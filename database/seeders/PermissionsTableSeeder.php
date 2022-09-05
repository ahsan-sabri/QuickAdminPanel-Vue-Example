<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'title' => 'user_management_access',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'permission_create',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'permission_edit',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'permission_show',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'permission_delete',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'permission_access',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'role_create',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'role_edit',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'role_show',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'role_delete',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'role_access',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'user_create',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'user_edit',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'user_show',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'user_delete',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'user_access',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'dashboard_access',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'profile_access',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'cms_access',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'access_control_management_access',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'locale_management_access',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'language_access',
                'created_at'     => Carbon::now()
            ],
        ];

        Permission::insert($permissions);
    }
}
