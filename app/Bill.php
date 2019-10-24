<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    public function paychecks() {
        return $this->belongsToMany('App\Paycheck')->withPivot(['due_on', 'paid_on'])->withTimestamps();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
