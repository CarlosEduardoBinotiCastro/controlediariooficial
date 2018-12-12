<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubCategoria;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\TipoDocumento;

class SubCategoriaController extends Controller
{
    //
    private $paginacao = 10;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listar(){

        if(Gate::allows('administrador', Auth::user())){

            $subcategorias = SubCategoria::orderBy('subcategoriaNome');
            $subcategorias = $subcategorias->paginate($this->paginacao);

            return view('subcategoria.listar', ['subcategorias' => $subcategorias]);

        }else{
            return redirect('/home');
        }
    }

    public function cadastrar(){

        if(Gate::allows('administrador', Auth::user())){

            $documentos = TipoDocumento::orderBy('tipoDocumento');
            $documentos = $documentos->get();

            return view('subcategoria.cadastrar', ['documentos' => $documentos]);

        }else{
            return redirect('/home');
        }
    }


}
