<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubCategoria;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\TipoDocumento;
use Illuminate\Support\Facades\DB;
use App\Fatura;

class SubCategoriaController extends Controller
{
    //
    private $paginacao = 10;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $subcategorias = SubCategoria::orderBy('subcategoriaNome');
            $subcategorias->join('tipodocumento', 'tipodocumento.tipoID', 'subcategoria.tipoID');
            $subcategorias->select('subcategoria.*', 'tipodocumento.tipoDocumento');
            $subcategorias = $subcategorias->paginate($this->paginacao);

            return view('subcategoria.listar', ['subcategorias' => $subcategorias]);

        }else{
            return redirect('/home');
        }
    }

    public function cadastrar(){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $documentos = TipoDocumento::orderBy('tipoDocumento');
            $documentos = $documentos->get();


            return view('subcategoria.cadastrar', ['documentos' => $documentos]);

        }else{
            return redirect('/home');
        }
    }

    public function salvar(Request $request){

        switch ($this->validar($request)){
            case 1:
                return redirect()->back()->with('erro', "Nome de subcategoria ja cadastrado!")->withInput();
            break;

            case 2:
                return redirect()->back()->with('erro', "Tamanho do Nome excedido!")->withInput();
            break;

            default:

            if(isset($request->subcategoriaID)){
                $subcategoria = SubCategoria::orderBy('subcategoriaNome');
                $subcategoria->where('subcategoriaID', '=', $request->subcategoriaID)->update(['subcategoriaNome' => $request->subcategoriaNome, 'tipoID' => $request->tipoID]);
                DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Editou a subcategoria '.$request->subcategoriaNome]);

                return redirect('/subcategoria/listar')->with('sucesso', 'Subcategoria Editada');
            }else{
                $subcategoria = SubCategoria::orderBy('subcategoriaNome');
                $subcategoria->insert(['subcategoriaNome' => $request->subcategoriaNome, 'tipoID' => $request->tipoID]);
                DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Cadastrou a subcategoria '.$request->subcategoriaNome]);

                return redirect('/subcategoria/listar')->with('sucesso', 'Subcategoria Cadastrada');
            }

            break;
        }


    }


    public function validar($request){

        if(isset($request->subcategoriaID)){

            if(DB::table('subcategoria')->where('subcategoriaNome', '=', $request->subcategoriaNome)->where('subcategoriaID', '!=', $request->subcategoriaID)->count()){
                return 1;
            }

            if(strlen($request->subcategoriaNome) > 100){
                return 2;
            }

        }else{

            if(DB::table('subcategoria')->where('subcategoriaNome', '=', $request->subcategoriaNome)->count()){
                return 1;
            }

            if(strlen($request->subcategoriaNome) > 100){
                return 2;
            }
        }

    }

    public function editar($subcategoriaID){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $documentos = TipoDocumento::orderBy('tipoDocumento');
            $documentos = $documentos->get();

            $subcategoria = SubCategoria::orderBy('subcategoriaNome');
            $subcategoria = $subcategoria->where('subcategoriaID', '=', $subcategoriaID)->first();

            return view('subcategoria.editar', ['documentos' => $documentos, 'subcategoria' => $subcategoria]);

        }else{
            return redirect('/home');
        }
    }

    public function deletar($subcategoriaID){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $subcategoria = SubCategoria::orderBy('subcategoriaNome');
            $faturas = Fatura::orderBy('protocoloCompleto')->where('subcategoriaID', '=', $subcategoriaID)->get();

            if(sizeof($faturas) == 0){
                $subcategoria->where('subcategoriaID', '=', $subcategoriaID)->delete();
                DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Deletou a subcategoria de id '.$subcategoriaID]);

                return redirect('/subcategoria/listar')->with('sucesso', 'Subcategoria Deletada');
            }else{
                return back()->with(['erro' => 'Impossível deletar, pois existem faturas vinculadas com essa subcategoria!', 'faturas' => $faturas]);
            }

        }else{
            return redirect('/home');
        }
    }

}
