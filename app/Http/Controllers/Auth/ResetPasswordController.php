<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|string|email:filter',
            'password' => 'required|string|confirmed',
        ];
    }

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));
        $user->password_changed_at = now();

        $user->save();

        $this->guard()->login($user);
    }

    public function showChangePasswordForm()
    {
        $userCheck = User::find(auth()->user()->id);

        if(!$userCheck) {
            return redirect()->back()->with('error', 'We cannot find your account!');
        }

        $tokenCheck = DB::table('password_resets')->where('email', $userCheck->email)->exists();

        if($tokenCheck === true) {
            DB::table('password_resets')->where('email', $userCheck->email)->delete();
        }

        DB::table('password_resets')->insert([
            'email' => $userCheck->email,
            'token' => Str::random(60),
            'created_at' => now(),
        ]);

        $getToken = DB::table('password_resets')->where('email', $userCheck->email)->first();

        return view('public.changePassword')->with([
            'email' => $getToken->email,
            'token' => $getToken->token,
        ]);
    }

    public function postChangePassword()
    {
        request()->validate([
            'token' => 'required',
            'email' => 'required|string|email:filter',
            'current-password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $tokenCheck = DB::table('password_resets')
                    ->where('email', request()->post('email'))
                    ->where('token', request()->post('token'))
                    ->exists();

        if(!$tokenCheck) {
            return redirect('/')->with('error', 'Email or Reset password token is invalid, please try again!');
        }

        $user = User::where('email', request()->post('email'))->first();

        if(Hash::check(request()->post('current-password'), $user->password)) {
            User::where('email', request()->post('email'))->update([
                'password' => bcrypt(request()->post('password')),
                'password_changed_at' => now(),
                'remember_token' => Str::random(60),
            ]);

            DB::table('password_resets')->where('email', request()->post('email'))->delete();

            return redirect('/')->with('status', 'Your password has successfully changed, enjoy it!');
        } else {
            return redirect()->back()->withErrors(['current-password' => 'Your entered password is wrong, try again!']);
        }
    }
}
