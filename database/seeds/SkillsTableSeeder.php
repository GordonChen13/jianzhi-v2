<?php

use Illuminate\Database\Seeder;
use App\Model\Skills;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skill1 = Skills::create([
            'name' => "英语",
            'display_name' => "英语",
            'description' => ""
        ]);
        $skill2 = Skills::create([
            'name' => "计算机",
            'display_name' => "计算机",
            'description' => " "
        ]);
        $skill3 = Skills::create([
            'name' => "网页设计",
            'display_name' => "网页设计",
            'description' => " "
        ]);
        $skill4 = Skills::create([
            'name' => "photoshop",
            'display_name' => "photoshop",
            'description' => " "
        ]);
        $skill5 = Skills::create([
            'name' => "php",
            'display_name' => "php",
            'description' => " "
        ]);
        $skill6 = Skills::create([
            'name' => "java",
            'display_name' => "java",
            'description' => " "
        ]);
        $skill7 = Skills::create([
            'name' => "mysql",
            'display_name' => "mysql",
            'description' => " "
        ]);
    }
}
