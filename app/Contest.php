<?php

namespace App;
use Illuminate\Support\Str;

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

    public function editPath() {
        return url("/edit/contest/{$this->id}-". Str::slug($this->name));
    }

    public function showPath() {
        return url("/dashboard/single-contest/{$this->id}-" . Str::slug($this->name));
    }

    public function singlePath() {
        return url("/contest/contestant/{$this->id}-". Str::slug($this->name));
    }
}
