<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{

    public function works() {
        return $this->belongsToMany('App\Model\Works','work_skill','skill_id','work_id');
    }
}
