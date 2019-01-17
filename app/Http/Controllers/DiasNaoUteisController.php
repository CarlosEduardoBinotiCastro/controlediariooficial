<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DiasNaoUteis;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use DateTime;


class DiasNaoUteisController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    private $paginacao = 10;

    public function listar(){
        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){
            $diasNaoUteis = DiasNaoUteis::orderBy('diaID');
            $diasNaoUteis = $diasNaoUteis->paginate($this->paginacao);
            return view('diasnaouteis.listar', ['diasNaoUteis' => $diasNaoUteis]);
        }else{
            return redirect('/home');
        }

    }

    public function cadastrar(){
        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){
            return view('diasnaouteis.cadastrar');
        }else{
            return redirect('/home');
        }
    }


    public function salvar(Request $request){

        $diasNaoUteis = DiasNaoUteis::orderBy('diaID');

        switch ($this->validar($request)){

            case 1:
                return redirect()->back()->with("erro", "Data já existente")->withInput();
            break;

            default:

                if(isset($request->diaID)){
                    $diasNaoUteis->where('diaID', '=', $request->diaID)->update(['diaNaoUtilData' => $request->diaNaoUtilData, 'diaDescricao' => $request->diaDescricao]);
                    return redirect('/diasnaouteis/listar')->with("sucesso", "Dia Não Útil Editado");
                }else{
                    $diasNaoUteis->insert(['diaNaoUtilData' => $request->diaNaoUtilData, 'diaDescricao' => $request->diaDescricao]);
                    return redirect('/diasnaouteis/listar')->with("sucesso", "Dia Não Útil Cadastrado");
                }

            break;

        }

    }

    public function editar($id){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $diasNaoUteis = DiasNaoUteis::orderBy('diaID', 'desc');
            $diasNaoUteis->select('*')->where('diaID', '=', $id);
            $diasNaoUteis = $diasNaoUteis->first();

            $data = new DateTime($diasNaoUteis->diaNaoUtilData);
            $data = $data;


            if($data <= date('Y-m-d')){
                return redirect()->back()->with(["erro" => "Impossível editar datas passadas!"]);
            }else{
                return view('diasnaouteis.editar', ['diaNaoUtil' => $diasNaoUteis]);
            }

        }else{
            return redirect('/home');
        }
    }

    public function deletar($id){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $diasNaoUteis = DiasNaoUteis::orderBy('diaID');
            $diasNaoUteis->select('*')->where('diaID', '=', $id);
            $diasNaoUteis->delete();

            return redirect()->back()->with("sucesso", "Dia Não Útil Deletado");

        }else{
            return redirect('/home');
        }
    }

    public function validar($diasNaoUteis){

        $verificador = DiasNaoUteis::orderBy('diaID');

        if(isset($diasNaoUteis->diaID)){

            if( $verificador->select('*')->where('diaNaoUtilData', '=', $diasNaoUteis->diaNaoUtilData)->where('diaID', '!=', $diasNaoUteis->diaID)->count()){

                return 1;

            }

        }else{

            if( $verificador->select('*')->where('diaNaoUtilData', '=', $diasNaoUteis->diaNaoUtilData)->count()){

                return 1;

            }

        }

    }

}
