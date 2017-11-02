<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    protected $table = "answers";
    protected $fillable = ['question_id','employer_id','content'];

    public function question() {
        return $this->belongsTo('App\Model\Questions');
    }

}
