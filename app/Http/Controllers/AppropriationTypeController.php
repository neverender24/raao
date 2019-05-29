<?php

namespace App\Http\Controllers;

use App\AppropriationType;
use Illuminate\Http\Request;

class AppropriationTypeController extends Controller
{
    public function __construct(AppropriationType $model) {
        $this->model = $model;
    }

    public function index() {
        return $this->model->all();
    }
}
