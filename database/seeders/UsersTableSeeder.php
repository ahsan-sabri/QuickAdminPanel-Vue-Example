<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@museiq.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'created_at'     => Carbon::now()
            ],
        ];

        User::insert($users);
    }
}
