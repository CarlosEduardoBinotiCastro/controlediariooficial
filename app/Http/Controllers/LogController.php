<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LogController extends Controller
{
    //
    private $paginacao = 20;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar($descricao = null){

        if(Gate::allows('administrador', Auth::user()) ){


            $logs = Log::orderBy('logData', 'desc');


            if($descricao != null && $descricao != "tudo"){
                $descricao = str_replace('¨', '/', $descricao);
                $arrayPalavras = explode(' ', $descricao);
                foreach ($arrayPalavras as $palavra) {
                    $logs->where('log.logDescricao', 'like', '%' . $palavra . '%');
                }
            }

            $logs = $logs->paginate($this->paginacao);
            return view('log.listar', ['logs' => $logs]);

        }else{
            return redirect('/home');
        }
    }

    public function listarFiltro(Request $request){
        if($request->descricao != null){
            $descricao = $request->descricao;
            $descricao = str_replace('/', '¨', $descricao);
        }else{
            $descricao = "tudo";
        }


        if(($descricao == "tudo")){
            return redirect('log/listar');
        }else{
            return redirect()->route('listarLogs', ['descricao' => $descricao]);
        }

    }

}
