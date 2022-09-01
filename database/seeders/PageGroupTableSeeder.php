<?php /** @noinspection PhpMultipleClassDeclarationsInspection */


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Tikweb\TikCmsApi\Models\PageGroup;

class PageGroupTableSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $groups = [
            [
                'group_name'=>'Home',
            ],
            [
                'group_name'=>'Services',
            ]
        ];

        foreach ($groups as $item) {
            $mGroup = new PageGroup();
            $mGroup->group_name = $item['group_name'];
            $mGroup->language = 'en';
            $mGroup->save();
        }
    }
}
