<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run(): void
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return str_starts_with($permission->title, 'dashboard_') || str_starts_with($permission->title, 'profile_');
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);
    }
}
