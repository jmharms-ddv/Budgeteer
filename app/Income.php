<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    paychecks() {
        return $this->hasMany('App\Paycheck');
    }
}
