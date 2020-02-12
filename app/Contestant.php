<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contestant extends Model
{
    protected $fillable = [
        'name',
        'email',
        'city',
        'phone',
        'contest_id',
        'description'
    ];

    public function contestant() {
        return $this->belongsTo('App\Contest', 'contest_id');
    }

    public function votes() {
        return $this->hasMany('App\Vote', 'contestant_id');
    }
}
