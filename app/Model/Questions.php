<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = "questions";
    protected $fillable = ['user_id','work_id','content'];

    public function work() {
        return $this->belongsTo('App\Model\Works')->first();
    }

    public function wantAnswerUsers() {
        return $this->belongsToMany('App\Model\User','want_answer','question_id','user_id');
    }

    public function answers() {
        return $this->hasOne('App\Model\Answers','question_id');
    }
}
