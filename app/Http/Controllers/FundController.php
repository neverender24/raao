<?php

namespace App\Http\Controllers;

use App\Fund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function __construct(Fund $model) {
        $this->model = $model;
    }

    public function index() {
        return $this->model->all();
    }
}
