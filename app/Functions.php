<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Functions extends Model
{
    protected $table = 'functions';

    public function users() {
        return $this->hasMany('App\AccountAccess', 'ffunccod', 'FFUNCCOD');
    }
}
