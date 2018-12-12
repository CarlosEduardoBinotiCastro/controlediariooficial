<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\DiarioData;
use DateTime;
use Illuminate\Support\Collection;


class FaturaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function carregarConfiguracao(){
        if(Gate::allows('administrador', Auth::user())){
            $confFatura = DB::table('configuracaofatura')->first();
            return view('fatura.configuracao', ['config' => $confFatura]);
        }else{
            return redirect('home');
        }
    }

    public function salvarConfiguracao(Request $request){
        $table = DB::table('configuracaofatura')->orderBy('configID');

        if($request->valorColuna != null && $request->largura != null){
            $table->where('configID', '=', $request->configID)->update(['largura' => $request->largura, 'valorColuna' => $request->valorColuna]);
            return redirect('home')->with('sucesso', 'Configurações Salvas');
        }else{
            return redirect()->back()->with('erro', 'Valores Em Brano');
        }
    }

    public function cadastrar(){

        if(Gate::allows('administrador', Auth::user())){
            // $usuarioCaderno = DB::table('usuariocaderno')->join('caderno', 'caderno.cadernoID', 'usuariocaderno.cadernoID')->where('usuarioID', '=', Auth::user()->id)->select('caderno.*')->get();
            $horaEnvio = Auth::user()->horaEnvio;
            // $documentos = TipoDocumento::orderBy('tipoDocumento');
            // $documentos->join('cadernotipodocumento', 'tipodocumento.tipoID',  '=', 'cadernotipodocumento.tipoID');
            // foreach($usuarioCaderno as $caderno){
            //     $documentos->orWhere('cadernotipodocumento.cadernoID', '=', $caderno->cadernoID);
            // }
            // $documentos->select('cadernotipodocumento.cadernoID', 'tipodocumento.tipoID', 'tipodocumento.tipoDocumento');
            // $documentos = $documentos->get();

            $diariosDatas = DiarioData::orderBy('diarioData', 'desc')->where('diarioData', '>', date('Y-m-d'))->get();
            $confFatura = DB::table('configuracaofatura')->first();

            // vericar datas limites para os diários

            $diariosDatasLimites = Collection::make([]);

            foreach($diariosDatas as $diario){

                $diaDiarioDate = new DateTime($diario->diarioData);
                $verificaDiaUtil = false;
                $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaDiarioDate->format('Y-m-d'))));

                do{
                    $finalDeSemana = date('N', strtotime($diaUtil));
                    if(!($finalDeSemana == '7' || $finalDeSemana == '6')){
                        if( !(DB::table('diasnaouteis')->where('diaNaoUtilData', '=', $diaUtil)->count()) ) {
                            $verificaDiaUtil = true;
                            $diariosDatasLimites->push(['diarioData' => $diario->diarioData, 'diarioDataID' => $diario->diarioDataID, 'numeroDiario' => $diario->numeroDiario, 'diaLimite' => $diaUtil]);
                        }else{

                        }
                    }

                    $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaUtil)));
                }while($verificaDiaUtil == false);

            }
            // fim dos limites para os diarios
            return view('fatura.cadastrar', [ 'diarioDatas' => json_encode($diariosDatasLimites), 'horaEnvio' => $horaEnvio, 'config' => $confFatura]);
        }else{
            return redirect('home');
        }

    }

}
