<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paycheck extends Model
{
    public function income() {
        return $this->belongsTo('App\Income');
    }

    public function bills() {
        return $this->belongsToMany('App\Bill')->withPivot(['amount', 'amount_project', 'due_on', 'paid_on'])->withTimestamps();
    }
}
