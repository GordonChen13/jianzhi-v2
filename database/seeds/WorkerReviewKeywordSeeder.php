<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkerReviewKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::table('worker_review_keywords')->insert([
            ['value' => '工作态度很好','type' =>'a'],
            ['value' => '办事效率很高','type' =>'a'],
            ['value' => '结果让人惊喜','type' =>'a'],
            ['value' => '下次还想合作','type' =>'a'],
            ['value' => '很有礼貌','type' =>'a'],
            ['value' => '注重细节','type' =>'a'],
            ['value' => '做事很靠谱','type' =>'a'],
            ['value' => '很积极主动','type' =>'a'],
            ['value' => '技术过关','type' =>'a'],
            ['value' => '实力强硬','type' =>'a'],
            ['value' => '工作态度一般','type' =>'b'],
            ['value' => '办事效率一般','type' =>'b'],
            ['value' => '完成效果一般','type' =>'b'],
            ['value' => '有迟到早退','type' =>'b'],
            ['value' => '不够积极主动','type' =>'b'],
            ['value' => '能力一般','type' =>'b'],
            ['value' => '礼貌一般','type' =>'b'],
            ['value' => '做事用心程度一般','type' =>'b'],
            ['value' => '工作效率很低','type' =>'c'],
            ['value' => '结果让人很不满意','type' =>'c'],
            ['value' => '工作态度很差','type' =>'c'],
            ['value' => '被放飞机了','type' =>'c'],
            ['value' => '不想再合作','type' =>'c'],
            ['value' => '迟到早退很严重','type' =>'c'],
            ['value' => '经常工作期间偷懒','type' =>'c'],
            ['value' => '跟网上描述严重不符','type' =>'c'],
            ['value' => '能力不足','type' =>'c']

        ]);
    }
}
