<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Raaods extends Model
{
    protected $table = 'raaods';

    public function raaoh() {
        return $this->belongsTo('App\Raaoh', 'idraao', 'recid');
    }

    public function ooe() {
        return $this->belongsTo('App\Ooes', 'idooe', 'recid');
    }

    public function payee() {
        return $this->belongsTo('App\Payee', 'idpayee', 'recid');
    }

    public function approp() {
        return $this->belongsTo('App\Raaod', 'idraao', 'recid');
    }
}
