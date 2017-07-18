<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Works extends Model
{
    protected $fillable = ['title','start_date','end_date','start_time','end_time','city',
        'district','address','need_num','pay_amount','settlement_type','pay_time','pay_type',
        'has_commission','commission','lunch','gender','contact','need_interview','interview_time',
        'interview_place','requirements','description','employer_id'];

    public function tags() {
        return $this->belongsToMany('App\Model\Tags','work_tag','work_id','tag_id');
    }

    public function skills() {
        return $this->belongsToMany('App\Model\Skills','work_skill','work_id','skill_id');
    }

    public function user() {
        return $this->belongsTo('App\Model\User','employer_id');
    }
}
