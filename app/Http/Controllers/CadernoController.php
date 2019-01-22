<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caderno;
use App\TipoDocumento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Publicacao;
use App\User;
use App\CadernoTipoDocumento;
use App\Log;

class CadernoController extends Controller
{
    //
    private $paginacao = 10;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $cadernos = Caderno::orderBy('cadernoNome');
            $cadernos = $cadernos->paginate($this->paginacao);

            return view('caderno.listar', ['cadernos' => $cadernos]);

        }else{
            return redirect()->route('home');
        }

    }

    public function cadastrar(){
        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $documentos = TipoDocumento::orderBy('tipoDocumento');
            $documentos = $documentos->get();
            return view('caderno.cadastrar', ['documentos' => $documentos]);

        }else{
            return redirect()->route('home');
        }
    }



    public function salvar(Request $request){


        switch ($this->validar($request)){

            case 1:
                return redirect()->back()->with('erro', "Um caderno Deve ter no mínimo 1 matéria!");
            break;

            case 2:
                return redirect()->back()->with('erro', "Já existe um caderno com esse nome!");
            break;

            default:
                $tipoDocumentos = json_decode($request->tipoDocumentos);


            if(isset($request->cadernoID)){

                DB::beginTransaction();
                try {
                    DB::table('caderno')->where('cadernoID', '=', $request->cadernoID)->update(['cadernoNome' => $request->cadernoNome]);

                    DB::table('cadernotipodocumento')->where('cadernoID', '=', $request->cadernoID)->delete();
                    foreach($tipoDocumentos as $documento){
                        DB::table('cadernotipodocumento')->insert(['tipoID' => $documento->tipoID, 'cadernoID' => $request->cadernoID]);
                    }
                    DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Editou o caderno '.$request->cadernoNome]);
                    DB::commit();

                    return redirect('/caderno/listar')->with("sucesso", "Caderno Editado");
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->with('erro', $e);
                }

            }else{
                DB::beginTransaction();
                try {
                    DB::table('caderno')->insert(['cadernoNome' => $request->cadernoNome]);
                    $cadernoID = DB::table('caderno')->max('cadernoID');

                    foreach($tipoDocumentos as $documento){
                        DB::table('cadernotipodocumento')->insert(['tipoID' => $documento->tipoID, 'cadernoID' => $cadernoID]);
                    }
                    DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Cadastrou o caderno '.$request->cadernoNome]);
                    DB::commit();

                    return redirect('/caderno/listar')->with("sucesso", "Caderno Cadastrado");
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->with('erro', $e);
                }
            }

            break;

        }


    }

    public function editar($id){
        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $documentosCaderno = TipoDocumento::orderBy('tipoDocumento');
            $documentosCaderno->join('cadernotipodocumento', 'cadernotipodocumento.tipoID', 'tipodocumento.tipoID');
            $documentosCaderno->where('cadernotipodocumento.cadernoID', '=', $id);
            $documentosCaderno->select('tipodocumento.tipoID', 'tipoDocumento');
            $documentosCaderno = $documentosCaderno->get();

            $documentos = TipoDocumento::orderBy('tipoDocumento');
            $documentos->select('tipoID', 'tipoDocumento');
            foreach($documentosCaderno as $documentoCaderno){
                $documentos->where('tipoID', '!=', $documentoCaderno->tipoID);
            }
            $documentos = $documentos->get();

            $caderno = Caderno::orderBy('cadernoNome')->where('cadernoID', '=', $id)->first();



            return view('caderno.editar', ['caderno' => $caderno, 'documentos' => $documentos, 'documentosCaderno' => $documentosCaderno]);
        }else{
            return redirect()->route('home');
        }
    }


    public function validar($request){

        if($request->tipoDocumentos == "[]"){
            return 1;
        }

        if(isset($request->cadernoID)){
            if(Caderno::orderBy('cadernoNome')->where('cadernoNome', '=', $request->cadernoNome)->where('cadernoID', '!=', $request->cadernoID)->count()){
                return 2;
            }

        }else{
            if(Caderno::orderBy('cadernoNome')->where('cadernoNome', '=', $request->cadernoNome)->count()){
                return 2;
            }
        }

    }

    public function deletar($id){
        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $publicacoes = Publicacao::orderBy('dataEnvio');
            $publicacoes->where('cadernoID', '=', $id);
            $publicacoes = $publicacoes->get();

            $usuariosCaderno = DB::table('usuariocaderno')->orderBy('usuarioID');
            $usuariosCaderno->where('cadernoID', '=', $id);
            $usuariosCaderno = $usuariosCaderno->get();

            if(sizeOf($publicacoes) > 0){

                return redirect()->back()->with(["erro" => "impossível deletar pois existem publicações vinculadas a este caderno !", 'publicacoes' => $publicacoes]);

            }else{

                if(sizeOf($usuariosCaderno) > 0){
                    $usuarios = User::orderBy('name');


                    foreach($usuariosCaderno as $usuarioCaderno){
                        $usuarios->orWhere('id', '=', $usuarioCaderno->usuarioID);
                    }

                    $usuarios = $usuarios->get();

                    return redirect()->back()->with(["erro" => "impossível deletar pois existem usuários vinculados a este caderno !", 'usuarios' => $usuarios]);
                }else{

                    $caderno = Caderno::orderBy('cadernoNome');
                    $caderno->where('cadernoID', '=', $id)->delete();

                    $cadernoTipoDocumento = CadernoTipoDocumento::orderBy('cadernoID');
                    $cadernoTipoDocumento->where('cadernoID', '=', $id)->delete();
                    DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Deletou o caderno de id '.$id]);
                    return redirect('/caderno/listar')->with("sucesso", "Caderno Deletado");

                }

            }

        }else{
            return redirect()->route('home');
        }
    }


}
