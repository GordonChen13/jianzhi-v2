<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'team';

    protected $fillable = ['name','slogan','city','intro','industry'];

    public function owner() {
        return $this->belongsToMany('App\Model\User','team_member','team_id','user_id')->wherePivot('title','队长');
    }

    public function members() {
        return $this->belongsToMany('App\Model\User','team_member','team_id','user_id')->withCount(['finishedWorks','favoriteWorks','userFollowers','reviews'])
            ->withPivot('title');
    }

    public function unCheckApplicants() {
        return $this->belongsToMany('App\Model\User','join_team','team_id','user_id')->withCount(['finishedWorks','favoriteWorks','userFollowers','reviews'])->wherePivot('status',0);
    }
}
