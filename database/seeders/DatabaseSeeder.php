<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Tikweb\TikCmsApi\Database\Seeders\LanguageTableSeeder;
use Tikweb\TikCmsApi\Database\Seeders\PageGroupTableSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            LanguageTableSeeder::class,
            PageGroupTableSeeder::class,
            MuseiqHomepageSeeder::class,
        ]);
    }
}
