<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table='tags';

    public function works() {
        return $this->belongsToMany('App\Model\Works','work_tag','tag_id','work_id');
    }
}
