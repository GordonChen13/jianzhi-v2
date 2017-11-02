<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $fillable = ['name','slogan','city','intro','industry','size'];

    public function members() {
        return $this->belongsToMany('App\Model\Employer','employer_company','company_id','employer_id')->withCount(['checkedWorks','userFollowers','reviews'])->withPivot('title');
    }

    public function owner() {
        return $this->belongsToMany('App\Model\Employer','employer_company','company_id','employer_id')->wherePivot('title','owner');
    }
}
