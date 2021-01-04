<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
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
    protected $redirectTo = '/';
    protected function redirectTo() {
        if(auth()->user()->isAdmin()) {
            return route('admin.dashboard');
        }
    }

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->username = $this->findUsername();
    }

    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    public function adminLogin()
    {
        return view('auth.admin');
    }

    protected function attemptLogin()
    {
        if(auth()->guard()->attempt([$this->findUsername() => request()->input('login'), 'password' => request()->password, 'disabled' => '0'])) {
            return $this->guard()->attempt($this->credentials(request()));
        }
    }

    protected function sendLoginResponse()
    {
        $previous_session = session()->getHandler()->read($this->guard()->user()->session_id);
        if ($previous_session) {
            session()->getHandler()->destroy($previous_session);
        }

        request()->session()->regenerate();

        $this->guard()->user()->session_id = session()->getId();
        $this->guard()->user()->save();
        $this->clearLoginAttempts(request());

        return $this->authenticated(request(), $this->guard()->user()) ?: redirect()->intended($this->redirectPath());
    }
}
