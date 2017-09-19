<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TagsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(SkillsTableSeeder::class);
        $this->call(ReportReasonSeeder::class);
        $this->call(WorkReviewKeywordSeeder::class);
        $this->call(WorkerReviewKeywordSeeder::class);

    }
}
