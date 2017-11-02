<?php

namespace App\Services;

use App\Model\Tags;
use App\Model\User;
use App\Model\Works;
use Carbon\Carbon;

class WorkService {


    public function __construct() {

    }

    /*
     * 返回$user的个性推荐兼职
     *
     * @return collections
     * */
    public function getRecommendWorks(User $user) {
        $works = $user->works()->get();
        $tags = array();
        foreach ($works as $work) {
            $workTags = $work->tags()->get();
            foreach ($workTags as $workTag) {
                array_push($tags,$workTag->name);
            }
        }
        $tagsCount = array_count_values($tags);
        $length = count($tagsCount);
        $recommendWorks = collect();
        foreach ($tagsCount as $key => $value) {
            $percent = $value/$length;
            $takeNum = round($percent * 20);
            if ($takeNum > 0) {
                $tagWorks = Tags::where('name',$key)->first()->works()->where('status',1)->orderBy('created_at','desc')->take($takeNum)->get();
                if (count($tagWorks) > 0) {
                    $recommendWorks = $recommendWorks->merge($tagWorks);
                }
            }
        }
        if (count($recommendWorks) < 20) {
            $addWorks = Works::where('status',1)->orderBy('created_at','desc')->take(20 - count($recommendWorks))->get();
            $recommendWorks = $recommendWorks->merge($addWorks)->unique('id');
        }
        return $recommendWorks;
    }

    /*
     * 返回本周热门兼职
     *
     * @return collections
     * */
    public function getHotWorks($order) {
        $orderBy = 'created_at';
        if ($order == 'view') {
            $orderBy = 'page_view';
        } else if ($order == 'favorite') {
            $orderBy = 'favorite_user_count';
        } else if ($order == 'apply') {
            $orderBy = 'applicants_count';
        }
        $weekAgo = Carbon::now()->subWeek();
        $works = Works::withCount(['questions','favoriteUser','applicants'])->where('status',1)->whereDate('created_at','>',$weekAgo->toDateString())->orderBy($orderBy,'desc')->paginate(20);
        return $works;
    }

    /**根据兼职Id返回相似兼职
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getSimilarWorks($id) {
        $work = Works::find($id);
        $tags = $work->tags()->get();
        $similarWorks = collect();
        $i = 0;
        if (count($tags) > 0) {
            while (count($similarWorks) <20) {
                foreach ($tags as $tag) {
                    $similarWork = $tag->works()->where('status',1)->orderBy('created_at','desc')->skip($i)->first();
                    if (empty($similarWork) || $similarWork->id == $id) {
                        break;
                    } else {
                        $similarWorks = $similarWorks->merge($similarWork);
                    }
                }
                $i = $i + 1;
                if ($i > 20) {
                    $addWorks = Works::where('status',1)->orderBy('created_at','desc')->take(20 - count($similarWorks))->get();
                    $similarWorks = $similarWorks->merge($addWorks);
                    break;
                }
            }
        } else {
            $similarWorks = Works::where('status',1)->orderBy('created_at','desc')->take(20)->get();
        }
        $similarWorks = $similarWorks->reject(function ($value,$key) use($id) {
            return $value->id == $id;
        });
        return $similarWorks;
    }

}