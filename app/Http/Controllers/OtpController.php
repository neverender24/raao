<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function check_otp(Request $request) {
        $user = DB::table('otp')->where('user_id', auth()->user()->recid)->first();
        return response()->json($user);
    }

    public function verify_otp(Request $request) {
        return view('otp');
    }

    public function test_otp(Request $request) {

        $code = cache()->get(auth()->user()->recid);
        $otp = cache()->get('otp'.auth()->user()->recid);

        if (!$otp) {
            Auth::logout();
            return redirect('/');
        }

        if ($code == $request->code) {
            cache()->pull(auth()->user()->recid);
            cache()->forever('otp'.auth()->user()->recid, $code);
            return redirect('/');
        } else {
            return redirect('/');
        }
    }

    public function resend_otp(Request $request) {

        $user = User::where('recid', auth()->user()->recid)->first();
    
        if ($user) {

            $digits = 4;
            $code = rand(pow(10, $digits-1), pow(10, $digits)-1);

            cache()->forever($user->recid, $code);
            cache()->put('otp'.$user->recid, $code, now()->addMinutes(5));
            cache()->put('log'.$user->recid, $code, now()->addMinutes(60));
            
            Mail::to("neverender24@gmail.com")->send(new otp($code));

            return redirect('/');
        }

    }
}
