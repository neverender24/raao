<?php

namespace App\Http\Controllers;

use App\Raaohs;
use Illuminate\Http\Request;

class RaaohsController extends Controller
{
    public function __construct(Raaohs $model) {
        $this->model = $model;
    }

    public function index(Request $request) {
        $sortFields     = ['recid'];
		$length         = $request->length;
		$column         = $request->column;
        $dir            = $request->dir;
        
        $searchValue    = $request->search;
        $year           = $request->year;
        $ffunccod       = $request->ffunccod;
        $fund           = $request->fund;
        $appropriation   = $request->appropriation;

        $index = $this->model->with(['function', 'source'])
        ->orderBy($sortFields[$column], $dir);

        if ($searchValue) {
            $index->where(function($query) use($searchValue, $year){
                $query->where('FRAODESC','LIKE','%'.$searchValue.'%');
            });
        }

        if ($year) {
            $index->where(function($query) use($searchValue, $year){
                $query->where('tyear','LIKE','%'.$year.'%');
            });
        }

        if ($ffunccod) {
            $index->whereHas("function", function($q) use($ffunccod){
                return $q->where('FFUNCCOD','LIKE','%'.$ffunccod.'%');
            });
        }

        if ($fund) {
            $index->whereHas("fund", function($q) use($fund){
                return $q->where('FFUNDDES','LIKE','%'.$fund.'%');
            });
        }
        
        if ($appropriation) {
            $index->whereHas("appropriationType", function($q) use($appropriation){
                return $q->where('FAPPTYPE','LIKE','%'.$appropriation.'%');
            });
        }

        if (trim(auth()->user()->UserType) != 'Administrator') {
            $index = $index->whereHas('function', function($query){
                $query->has('users');
            });
        }

        $index = $index->paginate($length);

    	return ['data'=>$index, 'draw'=> $request->draw];
    }

}
