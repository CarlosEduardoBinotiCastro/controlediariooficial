<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return [
            'login' => $request->email,
            'password' => $request->password,
        ];
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        // Verifica Se está ativo
        if($this->guard()->user()->statusID != 1 ){
            Auth::logout();
            return redirect()->back()->with("message", "Usuário Inativo para Acessar o Sistema")->withInput();
        }

        // Verifica Primeiro Login
        if($this->guard()->user()->primeiroLogin != 0){
            $user = User::orderBy('name')->where('id', '=', $this->guard()->user()->id)->update(['primeiroLogin' => 0]);
            return redirect('/usuario/editar/'.Auth::user()->id)->with("login", "Primeiro Login Detectado, Altere Sua Senha!");
        }

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }


}
