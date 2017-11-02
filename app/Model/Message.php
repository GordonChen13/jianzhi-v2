<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    public function fromUser() {
        return $this->belongsTo('App\Model\User','user_id');
    }

    public function toUser() {
        return $this->belongsTo('App\Model\User','target_id');
    }
}
