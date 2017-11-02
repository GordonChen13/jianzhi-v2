<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WorkerReviews extends Model
{
    protected $table = "worker_reviews";
    protected $fillable = ['work_id','user_id','content','attitude_star','ability_star','description_match','created_at','updated_at','pic_path'];

    public function applicant() {
        return $this->belongsTo('App\Model\User','user_id');
    }

    public function useful() {
        return $this->belongsToMany('App\Model\User','worker_review_useful','review_id','user_id');
    }

    public function reply() {
        return $this->hasOne('App\Model\WorkerReviewReply','review_id');
    }

    public function keywords() {
        return $this->belongsToMany('App\Model\WorkerReviewKeywords','worker_review_keyword_map','review_id','keyword_id');
    }
}
