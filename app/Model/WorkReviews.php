<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WorkReviews extends Model
{
    protected $table = "work_reviews";
    protected $fillable = ['work_id','employer_id','user_id','content','treat_star','pay_speed','description_match','created_at','updated_at','pic_path'];

    public function work() {
        return $this->belongsTo('App\Model\Works','work_id');
    }

    public function employer() {
        return $this->belongsTo('App\Model\Employer','employer_id');
    }

    public function useful() {
        return $this->belongsToMany('App\Model\User','work_review_useful','review_id','user_id');
    }

    public function reply() {
        return $this->hasOne('App\Model\WorkReviewReply','review_id');
    }

    public function keywords() {
        return $this->belongsToMany('App\Model\WorkReviewKeywords','work_review_keyword_map','review_id','keyword_id');
    }
}
