<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public function contest() {
        return $this->belongsTo('App\Contest', 'contest_id');
    }
}
