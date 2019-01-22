<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Session;
use DateTime;
use App\DiasNaoUteis;
use Illuminate\Support\Facades\Gate;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function carregarHome(){

        if(Auth::user() != null){

            $diasNaoUteis = DiasNaoUteis::orderBy('diaNaoUtilData')->whereBetween('diaNaoUtilData',  [date('Y')."-01-01", date('Y')."-12-31"]);
            $diasNaoUteis = $diasNaoUteis->get();
            return view('home', ['diasNaoUteis' => $diasNaoUteis]);

        }else{
            return redirect('/login');
        }

    }

    public function pegarLogo(){
        $file_path = storage_path("app/"."logoSEMAD.png");
        return response()->file($file_path);
    }

    public function pegarLogoSis(){
        $file_path = storage_path("app/"."LogoSISPUDIO.png");
        return response()->file($file_path);
    }

}
