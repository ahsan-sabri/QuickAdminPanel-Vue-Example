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
                'title' => 'Admin',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'Producer',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'Artist',
                'created_at'     => Carbon::now()
            ],
            [
                'title' => 'User',
                'created_at'     => Carbon::now()
            ],
        ];

        Role::insert($roles);
    }
}
