<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Works extends Model
{
    //TODO 增加审核兼职功能后，把status去掉
    protected $fillable = ['title','start_date','end_date','start_time','end_time','city',
        'district','address','need_num','pay_amount','settlement_type','pay_time','pay_type',
        'has_commission','commission','lunch','gender','contact','need_interview','interview_time',
        'interview_place','requirements','description','employer_id','status'];

    public function tags() {
        return $this->belongsToMany('App\Model\Tags','work_tag','work_id','tag_id');
    }

    public function skills() {
        return $this->belongsToMany('App\Model\Skills','work_skill','work_id','skill_id');
    }

    public function employer() {
        return $this->belongsTo('App\Model\User','employer_id');
    }

    public function questions() {
        return $this->hasMany('App\Model\Questions','work_id');
    }

    public function favoriteUser() {
        return $this->belongsToMany('App\Model\User','favorite_works','work_id','user_id');
    }

    public function applicants() {
        return $this->belongsToMany('App\Model\User','apply_works','work_id','user_id');
    }
}
