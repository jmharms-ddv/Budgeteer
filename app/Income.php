<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    public function paychecks() {
        return $this->hasMany('App\Paycheck');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
