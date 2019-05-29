<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Raaohs extends Model
{
    protected $table = 'raaohs';

    public function appropriationType() {
        return $this->belongsTo('App\AppropriationType','idappttype','recid');
    }

    public function function() {
        return $this->belongsTo('App\Functions','FFUNCCOD','FFUNCCOD');
    }

    public function fund() {
        return $this->belongsTo('App\Fund','FFUNDCOD','FFUNDCOD');
    }

    public function program() {
        return $this->belongsTo('App\Program','idprogram','recid');
    }

    public function source() {
        return $this->belongsTo('App\Source','idsource','recid');
    }
}
