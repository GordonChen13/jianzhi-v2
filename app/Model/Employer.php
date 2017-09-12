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
    public function works() {
        return $this->hasMany('App\Model\Works', 'employer_id', 'id');
    }

    public function followers() {
        return $this->hasMany('App\Model\Users');
    }
}