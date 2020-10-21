<?php

namespace App\Http\Controllers;

use App\Raaods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaaodsController extends Controller
{
    public function __construct(Raaods $model) {
        $this->model = $model;
    }

    public function getRaaods(Request $request) {
        $sortFields     = ['recid'];
		$length         = $request->length;
		$column         = $request->column;
		$dir            = $request->dir;
        $searchValue    = $request->search;
        $recid          = $request->recid;

        $index = $this->model->with(['ooe'])
        ->leftjoin('ooes','ooes.recid','=','raaods.idooe')
        ->select(
            'idooe',
            'idraao',
            'raaods.recid', 
            'aipcode', 
            'FOOEDESC',
            'famount as tbalance',
            DB::raw('SUM( if(entrytype = 1, famount, 0)) as tapprop'),
            DB::raw('SUM( if(entrytype = 2, famount, 0)) as tallot'),
            DB::raw('SUM( if(entrytype = 3, famount, 0)) as toblig')
        ) 
        ->where('idraao', $recid);

        $this->search($index, $searchValue);
		$index = $index->groupBy('idooe')->orderBy("ooes.FACTCODE",'asc')->orderBy("FOOEDESC",'asc')->paginate($length);

    	return ['data'=>$index, 'draw'=> $request->draw];
    }

    public function getRaaodsLedger(Request $request) {

        $sortFields     = ['recid'];
		$length         = $request->length;
		$column         = $request->column;
		$dir            = $request->dir;
        $searchValue    = $request->search;
        $idraao          = $request->idraao;
        $idooe          = $request->idooe;

        $index = $this->model->with(['ooe', 'payee']) 
        ->select(
            'idooe', 
            'recid', 
            'fdate',
            'fparticulars',
            'aipcode', 
            'frefno',
            'idpayee',
            DB::raw('if(entrytype = 1, famount, 0) as tapprop'),
            DB::raw('if(entrytype = 2, famount, 0) as tallot'),
            DB::raw('if(entrytype = 3, famount, 0) as toblig')
        ) 
        ->where('idraao', $idraao)
        ->where('idooe', $idooe)
        ->where('entrytype','>',0);

        $this->search($index, $searchValue);
		$index = $index->orderBy("raaods.fdate", "asc")->get(); //->paginate($length);

    	return ['data'=>$index, 'draw'=> $request->draw];
    }

    public function search($collection, $searchValue) {
        if ($searchValue) {
            return $collection->where(function($query) use($searchValue){
                $query->orWhere('recid','LIKE','%'.$searchValue.'%');
            });
        }
    }
}
