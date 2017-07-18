<?php

use Illuminate\Database\Seeder;
use App\Model\Roles;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles1 = Roles::create([
            'name' => "employer",
            'display_name' => "发布人",
            'description' => "兼职信息发布人"
        ]);
        $roles2 = Roles::create([
            'name' => "admin",
            'display_name' => "管理员",
            'description' => "网站管理员"
        ]);
    }
}
