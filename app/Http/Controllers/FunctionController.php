<?php

namespace App\Http\Controllers;

use App\Functions;
use Illuminate\Http\Request;

class FunctionController extends Controller
{
    public function __construct(Functions $model) {
        $this->model = $model;
    }

    public function index() {

        if (trim(auth()->user()->UserType) != 'Administrator') {
            return $this->model->whereHas('users', function($q){
                $q->where('iduser', auth()->user()->recid);
            })->get();
        }

        return $this->model->all();
    }
}
