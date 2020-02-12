<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date'
    ];

    public function contestants() {
        return $this->hasMany('App\Contestant', 'contest_id');
    }
}
