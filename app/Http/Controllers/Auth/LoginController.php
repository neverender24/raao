<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Mail\otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'verify_otp';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

       $user = User::where('UserName', $request->username)
                    ->where('UserPassword',md5($request->password))
                    ->first();
    
        if ($user) {

            $digits = 4;
            $code = rand(pow(10, $digits-1), pow(10, $digits)-1);

            if (!cache()->get('log'.$user->recid)) {
                cache()->forever($user->recid, $code);
                cache()->put('otp'.$user->recid, $code, now()->addMinutes(5));
                cache()->put('log'.$user->recid, $code, now()->addMinutes(60));
                
                Mail::to("neverender24@gmail.com")->send(new otp($code));
            }



            Auth::login($user, true);
        }
       
       return redirect('/');
    }

    
}
