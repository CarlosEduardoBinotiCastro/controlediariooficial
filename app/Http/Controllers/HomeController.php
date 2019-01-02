<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Session;
use DateTime;

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
     * @return \Illuminate\Http\Response
     */

    public function carregarHome(){


            if(Auth::user()->primeiroLogin == 0){
                $user = new User();
                $userController = new UserController();
                return view('home');
            }else{
                $user = User::orderBy('name')->where('id', '=', Auth::user()->id)->update(['primeiroLogin' => 0]);
                return redirect('/usuario/editar/'.Auth::user()->id)->with("login", "Primeiro Login Detectado, Altere Sua Senha!");
            }
    }

    public function pegarLogo(){
        $file_path = storage_path("app/"."Brasão_de_Cachoeiro_de_Itapemirim_ES.png");
        return response()->file($file_path);
    }




}
