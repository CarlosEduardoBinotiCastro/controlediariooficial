<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Session;
use DateTime;
use App\DiasNaoUteis;
use App\DiarioData;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

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

            if(Gate::allows('faturas', Auth::user())){
                $diariosData = null;
                $diaLimite = null;
            }else{
                $diariosData = DiarioData::orderBy('diarioData')->where('diarioData', '>', date('Y-m-d'))->first();

                if($diariosData != null){

                    $diaDiarioDate = new DateTime($diariosData->diarioData);
                    $verificaDiaUtil = false;
                    $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaDiarioDate->format('Y-m-d'))));

                    do{
                        $finalDeSemana = date('N', strtotime($diaUtil));
                        if(!($finalDeSemana == '7' || $finalDeSemana == '6')){
                            if( !(DB::table('diasnaouteis')->where('diaNaoUtilData', '=', $diaUtil)->count()) ) {
                                $verificaDiaUtil = true;
                                $diaLimite = $diaUtil;
                            }else{

                            }
                        }

                        $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaUtil)));
                    }while($verificaDiaUtil == false);

                }else{
                    $diaLimite = null;
                }

            }

            return view('home', ['diasNaoUteis' => $diasNaoUteis, 'diarioData' => $diariosData, 'diaLimite' => $diaLimite]);

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

    public function pegarBrasao(){
        $file_path = storage_path("app/"."Brasao.png");
        return response()->file($file_path);
    }

}
