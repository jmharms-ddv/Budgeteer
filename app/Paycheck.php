<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paycheck extends Model
{
    income() {
        return $this->belongsTo('App\Income');
    },

    bills() {
        return $this->belongsToMany('App\Bill')->withPivot(['due_on', 'paid_on'])->withTimestamps();
    }
}
