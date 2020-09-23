<?php

namespace App\Http\Controllers;

use App\Raaohs;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct(Schedule $model)
    {
        $this->model = $model;
    }

    public function save_schedule(Request $request) {
        
        $raoh = Raaohs::where('recid', $request->raoh_id)->first();
        $year = Carbon::now()->year;

        $this->model->create([
            "adverts" => $request->adverts,
            "submission" => $request->submission,
            "notice" => $request->notice,
            "contract" => $request->contract,
            "FALLTCOD" => $raoh->FALLTCOD,
            "FFUNCCOD" => $raoh->FFUNCCOD,
            "FFUNDCOD" => $raoh->FFUNDCOD,
            "idappttype" => $raoh->idappttype,
            "idsource" => $raoh->idsource,
            "year_" => $year,
        ]);
    }
}
