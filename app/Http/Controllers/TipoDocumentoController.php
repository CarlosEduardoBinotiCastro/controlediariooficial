<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoDocumento;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Publicacao;
use App\Caderno;
use App\CadernoTipoDocumento;
use Illuminate\Support\Facades\DB;

class TipoDocumentoController extends Controller
{
    //
    private $paginacao = 10;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $tiposDocumentos = TipoDocumento::orderBy('tipoDocumento');
            $tiposDocumentos = $tiposDocumentos->paginate($this->paginacao);
            return view('tipodocumento.listar', ['tiposDocumentos' => $tiposDocumentos]);

        }else{
            return redirect('/home');
        }
    }

    public function cadastrar(){
        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){
            return view('tipodocumento.cadastrar');
        }else{
            return redirect('/home');
        }
    }

    public function salvar(Request $request){

        $tipoDocumento = TipoDocumento::orderBy('tipoDocumento');

        switch ($this->validar($request)){

            case 1:
                return redirect()->back()->with("erro", "Matéria já existente")->withInput();
            break;

            default:

                if(isset($request->tipoID)){
                    DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Editou a Matéria '.$request->tipoDocumento]);

                    $tipoDocumento->where('tipoID', '=', $request->tipoID)->update(['tipoDocumento' => $request->tipoDocumento]);
                    return redirect('/tipodocumento/listar')->with("sucesso", "Matéria Editada");
                }else{
                    DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Cadastrou a Matéria '.$request->tipoDocumento]);
                    $tipoDocumento->insert(['tipoDocumento' => $request->tipoDocumento]);
                    return redirect('/tipodocumento/listar')->with("sucesso", "Matéria Cadastrada");
                }



            break;

        }

    }


    public function validar($tipoDocumento){

        $verificador = TipoDocumento::orderBy('tipoID');

        if(isset($tipoDocumento->tipoID)){

            if( $verificador->select('*')->where('tipoDocumento', '=', $tipoDocumento->tipoDocumento)->where('tipoID', '!=', $tipoDocumento->tipoID)->count()){

                return 1;

            }

        }else{

            if( $verificador->select('*')->where('tipoDocumento', '=', $tipoDocumento->tipoDocumento)->count()){

                return 1;

            }

        }

    }


    public function editar($id){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $tipoDocumento = TipoDocumento::orderBy('tipoID');
            $tipoDocumento->select('*')->where('tipoID', '=', $id);
            $tipoDocumento = $tipoDocumento->first();

            $publicacoes = Publicacao::orderBy('dataEnvio');
            $publicacoes->where('tipoID', '=', $id);
            $publicacoes = $publicacoes->get();

            $cadernos = Caderno::orderBy('caderno.cadernoID');
            $cadernos->join('cadernotipodocumento', 'cadernotipodocumento.cadernoID', 'caderno.cadernoID');
            $cadernos->join('tipodocumento', 'cadernotipodocumento.tipoID', 'tipodocumento.tipoID');
            $cadernos->select('caderno.cadernoNome');
            $cadernos = $cadernos->where('tipodocumento.tipoID', '=', $id)->get();


            return view('tipodocumento.editar', ['tipoDocumento' => $tipoDocumento, 'publicacoes' => $publicacoes, 'cadernos' => $cadernos]);

        }else{
            return redirect('/home');
        }
    }


    public function deletar($id){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $tipoDocumento = TipoDocumento::orderBy('tipoID');
            $cadernoTipoDocumento = CadernoTipoDocumento::orderby('tipoID');


            $publicacoes = Publicacao::orderBy('dataEnvio');
            $publicacoes = $publicacoes->where('tipoID', '=', $id)->get();
            $faturas =  DB::table('fatura')->where('tipoID', '=', $id)->get();
            // validar deletar OBS**

            if(sizeof($publicacoes) == 0){

                if(sizeof($faturas) == 0){
                DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Deletou a Matéria de id '.$id]);
                $cadernoTipoDocumento->where('tipoID', '=', $id)->delete();
                $tipoDocumento->where('tipoID', '=', $id)->delete();
                return redirect('/tipodocumento/listar')->with("sucesso", "Matéria Deletada");

                }else{

                    return redirect()->back()->with(["erro" => "Impossível deletar pois existem faturas vinculadas a esta matéria!", 'faturas' => $faturas]);

                }

            }else{

                return redirect()->back()->with(["erro" => "Impossível deletar pois existem faturas vinculadas a esta matéria!", 'publicacoes' => $publicacoes]);

            }


        }else{
            return redirect('/home');
        }
    }


}
