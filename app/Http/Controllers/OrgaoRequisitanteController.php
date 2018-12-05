<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrgaoRequisitante;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\User;

class OrgaoRequisitanteController extends Controller
{
    //
    private $paginacao = 10;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(){

        if(Gate::allows('administrador', Auth::user())){

            $orgaosRequisitantes = OrgaoRequisitante::orderBy('orgaoNome', 'desc');
            $orgaosRequisitantes = $orgaosRequisitantes->paginate($this->paginacao);
            return view('orgaorequisitante.listar', ['orgaosRequisitantes' => $orgaosRequisitantes]);

        }else{
            return redirect('/home');
        }
    }


    public function cadastrar(){
        if(Gate::allows('administrador', Auth::user())){
            return view('orgaorequisitante.cadastrar');
        }else{
            return redirect('/home');
        }
    }


    public function salvar(Request $request){

        $orgaoRequisitante = OrgaoRequisitante::orderBy('orgaoNome');

        switch ($this->validar($request)){

            case 1:
                return redirect()->back()->with("erro", "Órgão Requisitante já existente")->withInput();
            break;

            default:

                if(isset($request->orgaoID)){
                    $orgaoRequisitante->where('orgaoID', '=', $request->orgaoID)->update(['orgaoNome' => $request->orgaoNome]);
                    return redirect('/orgaorequisitante/listar')->with("sucesso", "Órgão Requisitante Editado");
                }else{
                    $orgaoRequisitante->insert(['orgaoNome' => $request->orgaoNome]);
                    return redirect('/orgaorequisitante/listar')->with("sucesso", "Órgão Requisitante Cadastrado");
                }

            break;

        }

    }

    public function editar($id){

        if(Gate::allows('administrador', Auth::user())){

            $orgaoRequisitante = OrgaoRequisitante::orderBy('orgaoID');
            $orgaoRequisitante->select('*')->where('orgaoID', '=', $id);
            $orgaoRequisitante = $orgaoRequisitante->first();

            $users = User::orderBy('name');
            $users->select('name');
            $users->where('orgaoID', '=', $id);
            $users = $users->get();


            return view('orgaorequisitante.editar', ['orgaoRequisitante' => $orgaoRequisitante, 'usuarios' => $users]);

        }else{
            return redirect('/home');
        }
    }

    public function validar($orgaoRequisitante){

        $verificador = OrgaoRequisitante::orderBy('orgaoNome');

        if(isset($orgaoRequisitante->orgaoID)){

            if( $verificador->select('*')->where('orgaoNome', '=', $orgaoRequisitante->orgaoNome)->where('orgaoID', '!=', $orgaoRequisitante->orgaoID)->count()){

                return 1;

            }

        }else{

            if( $verificador->select('*')->where('orgaoNome', '=', $orgaoRequisitante->orgaoNome)->count()){

                return 1;

            }

        }

    }


    public function deletar($id){

        if(Gate::allows('administrador', Auth::user())){

            $orgaoRequisitante = OrgaoRequisitante::orderBy('orgaoID');


            $users = User::orderBy('id');
            $users = $users->where('orgaoID', '=', $id)->get();

            // validar deletar OBS**

            if(sizeof($users) == 0){
                $orgaoRequisitante->where('orgaoID', '=', $id)->delete();
                return redirect('/orgaorequisitante/listar')->with("sucesso", "Órgão Requisitante Deletado");

            }else{
                return redirect()->back()->with(["erro" => "Impossível deletar pois existem usuários vinculados a este Órgão Requisitante!", 'usuarios' => $users]);
            }

        }else{
            return redirect('/home');
        }
    }



}
