<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::table('report_reason')->insert([
            ['type' => 1,'display_name' => '发布虚假信息','description' => '发布虚假信息'],
            ['type' => 2,'display_name' => '可能涉及诈骗','description' => '可能涉及诈骗'],
            ['type' => 3,'display_name' => '可能涉及情色交易','description' => '可能涉及情色交易'],
            ['type' => 4,'display_name' => '其它','descrition' => '其它']
        ]
        );
    }
}
