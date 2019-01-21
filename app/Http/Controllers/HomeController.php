<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Session;
use DateTime;
use App\DiasNaoUteis;


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

        $diasNaoUteis = DiasNaoUteis::orderBy('diaNaoUtilData')->whereBetween('diaNaoUtilData',  [date('Y')."-01-01", date('Y')."-12-31"]);
        $diasNaoUteis = $diasNaoUteis->get();
        return view('home', ['diasNaoUteis' => $diasNaoUteis]);

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
