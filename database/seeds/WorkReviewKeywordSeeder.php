<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkReviewKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::table('work_review_keywords')->insert([
            ['value' => '涨姿势了','type' =>'a'],
            ['value' => '认识了新朋友','type' =>'a'],
            ['value' => '待遇超级棒','type' =>'a'],
            ['value' => '工资发放速度快','type' =>'a'],
            ['value' => '办公环境高大上','type' =>'a'],
            ['value' => '负责人很nice','type' =>'a'],
            ['value' => '工作内容轻松简单','type' =>'a'],
            ['value' => '工作氛围很好','type' =>'a'],
            ['value' => '工作餐很好吃','type' =>'a'],
            ['value' => '提成很高','type' =>'a'],
            ['value' => '下次还想来','type' =>'a'],
            ['value' => '负责人态度一般','type' =>'b'],
            ['value' => '工作氛围一般','type' =>'b'],
            ['value' => '不包餐','type' =>'b'],
            ['value' => '跟描述不符','type' =>'b'],
            ['value' => '待遇一般','type' =>'b'],
            ['value' => '提成一般','type' =>'b'],
            ['value' => '工资发放速度较慢','type' =>'b'],
            ['value' => '办公环境一般','type' =>'b'],
            ['value' => '工作内容繁复','type' =>'b'],
            ['value' => '学不到东西','type' =>'c'],
            ['value' => '工作辛苦','type' =>'c'],
            ['value' => '待遇很差','type' =>'c'],
            ['value' => '负责人很凶','type' =>'c'],
            ['value' => '不想再来','type' =>'c'],
            ['value' => '跟描述严重不符','type' =>'c'],
            ['value' => '拿不到提成','type' =>'c'],
            ['value' => '办公环境脏乱差','type' =>'c'],
            ['value' => '工作时间长','type' =>'c'],
            ['value' => '工资发放速度很慢','type' =>'c'],
            ['value' => '恶意克扣工资','type' =>'c']

        ]);
    }
}
