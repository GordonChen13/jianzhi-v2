<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 17-9-6
 * Time: 下午2:52
 */

namespace App\Model;

use App\Model\User;


class Employer extends User
{
    protected $table = 'users';

    public function works() {
        return $this->hasMany('App\Model\Works', 'employer_id', 'id');
    }

    public function checkedWorks() {
        return $this->hasMany('App\Model\Works', 'employer_id', 'id')->where('status','>',0);
    }

    public function applyingWorks() {
        return $this->hasMany('App\Model\Works', 'employer_id', 'id')->where('status',1);
    }

    public function finishedWorks () {
        return $this->hasMany('App\Model\Works', 'employer_id', 'id')->where('status','>',2);
    }
    public function reviews() {
        return $this->hasMany('App\Model\WorkReviews', 'employer_id', 'id');
    }

    public function company() {
        return $this->belongsToMany('App\Model\Company','employer_company','employer_id','company_id');
    }

    public function userFollowers() {
        return $this->belongsToMany('App\Model\User','follow','to_id','from_id')->wherePivot('status',12);
    }

    public function employerFollowers() {
        return $this->belongsToMany('App\Model\User','follow','to_id','from_id')->wherePivot('status',22);
    }
    public function companyFollowers() {
        return $this->belongsToMany('App\Model\User','follow','to_id','from_id')->wherePivot('status',32);
    }

    public function followingUsers() {
        return $this->belongsToMany('App\Model\User','follow','from_id','to_id')->wherePivot('status',21);
    }

    public function followingEmployers() {
        return $this->belongsToMany('App\Model\User','follow','from_id','to_id')->wherePivot('status',22);
    }

    public function followingCompanys() {
        return $this->belongsToMany('App\Model\User','follow','from_id','to_id')->wherePivot('status',23);
    }

    public function thanks() {
        return $this->hasMany('App\Model\ThanksEmployer','employer_id','id');
    }
}