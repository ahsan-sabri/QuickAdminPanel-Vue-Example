<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Admin',
                'created_at'     => Carbon::now()
            ],
            [
                'id'    => 2,
                'title' => 'User',
                'created_at'     => Carbon::now()
            ],
        ];

        Role::insert($roles);
    }
}
