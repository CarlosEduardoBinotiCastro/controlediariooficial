<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DiasNaoUteis;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\DB;


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
            $diasNaoUteis = DiasNaoUteis::orderBy('diaNaoUtilData');
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
                    DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Editou o Feriado/Facultativo '.$request->diaNaoUtilData]);
                    return redirect('/diasnaouteis/listar')->with("sucesso", "Dia Não Útil Editado");
                }else{
                    DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Cadastrou o Feriado/Facultativo '.$request->diaNaoUtilData]);
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
            DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Deletou o Feriado/Facultativo de id '.$id]);

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
