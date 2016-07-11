<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest', ['except' => 'logout']);
    }

    public function login() {

        if (\Auth::check()) {
            return redirect()->route('home');
        } else {
            return view('auth.index');
        }
    }

    public function getSocialRedirect($provider)
    {
        $providerKey = \Config::get('services.' . $provider);
        if(empty($providerKey)) {

            //return view('pages.status')->with('error', 'No such provider');
        }

        return \Socialite::driver($provider)->redirect();
    }

    public function getSocialHandle($provider)
    {
        $user = \Socialite::driver($provider)->user();
        $socialUser = null;

        $userCheck = User::where('email', '=', $user->email)->first();

        if(!empty($userCheck))  {

            \Auth::login($userCheck, true);
            return redirect()->route('home');
        }
    }

    public function logout() {

        \Auth::logout();
        return redirect()->route('auth.login');
    }
}
