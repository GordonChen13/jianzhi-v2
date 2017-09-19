<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function works() {
        return $this->hasMany('App\Model\Works');
    }

    public function employerWorks() {
        return $this->hasMany('App\Model\Works','employer_id','id');
    }

    public function applyingWorks() {
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id')->wherePivot('status',0);
    }

    public function finishedWorks() {
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id')->wherePivotIn('status',[2,3,4]);
    }

    public function favoriteWorks() {
        return $this->belongsToMany('App\Model\Works','favorite_works','user_id','work_id');
    }

    public function userFollowers() {
        return $this->belongsToMany('App\Model\User','follow','to_id','from_id')->wherePivot('status',11);
    }

    public function employerFollowers() {
        return $this->belongsToMany('App\Model\User','follow','to_id','from_id')->wherePivot('status',21);
    }
    public function companyFollowers() {
        return $this->belongsToMany('App\Model\User','follow','to_id','from_id')->wherePivot('status',31);
    }

    public function followingUsers() {
        return $this->belongsToMany('App\Model\User','follow','from_id','to_id')->wherePivot('status',11);
    }

    public function followingEmployers() {
        return $this->belongsToMany('App\Model\User','follow','from_id','to_id')->wherePivot('status',21);
    }

    public function followingCompanys() {
        return $this->belongsToMany('App\Model\User','follow','from_id','to_id')->wherePivot('status',31);
    }
    public function reviews() {
        return $this->hasMany('App\Model\WorkerReviews','user_id');
    }



}
