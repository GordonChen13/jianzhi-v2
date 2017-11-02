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
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id');
    }

    public function interviewFailedWorks() {
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id')->wherePivot('status',-2);
    }

    public function rejectedWorks() {
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id')->wherePivot('status',-1);
    }

    public function applyingWorks() {
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id')->wherePivot('status',0);
    }

    public function interviewingWorks() {
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id')->wherePivot('status',1);
    }

    public function workingWorks() {
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id')->wherePivot('status',2);
    }

    public function reviewingWorks() {
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id')->wherePivot('status',3);
    }

    public function finishedWorks() {
        return $this->belongsToMany('App\Model\Works','apply_works','user_id','work_id')->wherePivot('status',4);
    }

    public function passedWorks() {
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

    public function team() {
        return $this->belongsToMany('App\Model\Team','team_member','user_id','team_id');
    }

    public function unReadMessages() {
        return $this->hasMany('App\Model\Message','target_id','id')->where('status',0);
    }

    public function unReadMessagesFromUser($user_id) {
        return $this->hasMany('App\Model\Message','target_id','id')->where('user_id',$user_id)->where('status',0);
    }
    public function receivedMessages() {
        return $this->hasMany('App\Model\Message','target_id','id');
    }

    public function sentMessages() {
        return $this->hasMany('App\Model\Message','user_id','id');
    }

    public function thanks() {
        return $this->hasMany('App\Model\ThanksUser','user_id','id');
    }

}
