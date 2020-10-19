<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('layouts.app');
    }

    public function home() {

        $code = cache()->get(auth()->user()->recid);
        $otp = cache()->get('otp'.auth()->user()->recid);
        $log = cache()->get('log'.auth()->user()->recid);

        if (!$otp) {
            Auth::logout();
            return redirect('/');
        }
        
        if ($code && $otp && $log) {
            return view('otp');
        }

        return view('welcome');
        
    }
}
