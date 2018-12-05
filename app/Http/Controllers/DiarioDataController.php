<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DiarioData;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Publicacao;
use DateTime;



class DiarioDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private $paginacao = 10;

    public function listar(){
        if(Gate::allows('administrador', Auth::user())){
            $diariosDatas = DiarioData::orderBy('diarioData', 'desc');
            $diariosDatas = $diariosDatas->paginate($this->paginacao);
            return view('diariodata.listar', ['diariosDatas' => $diariosDatas]);
        }else{
            return redirect('/home');
        }

    }

    public function cadastrar(){
        if(Gate::allows('administrador', Auth::user())){
            return view('diariodata.cadastrar');
        }else{
            return redirect('/home');
        }
    }

    public function salvar(Request $request){

        $diarioData = DiarioData::orderBy('diarioDataID');

        switch ($this->validar($request)){

            case 1:
                return redirect()->back()->with("erro", "Número de diário já existente")->withInput();
            break;

            default:

                if(isset($request->diarioDataID)){
                    $diarioData->where('diarioDataID', '=', $request->diarioDataID)->update(['diarioData' => $request->diarioData, 'numeroDiario' => $request->numeroDiario]);
                    return redirect('/diariodata/listar')->with("sucesso", "Diario Oficial Editado");
                }else{
                    $diarioData->insert(['diarioData' => $request->diarioData, 'numeroDiario' => $request->numeroDiario]);
                    return redirect('/diariodata/listar')->with("sucesso", "Diario Oficial Cadastrado");
                }



            break;

        }

    }

    public function editar($id){

        if(Gate::allows('administrador', Auth::user())){

            $diarioData = DiarioData::orderBy('diarioDataID');
            $diarioData->select('*')->where('diarioDataID', '=', $id);
            $diarioData = $diarioData->first();

            $publicacoes = Publicacao::orderBy('dataEnvio');
            $publicacoes->where('diarioDataID', '=', $id);
            $publicacoes = $publicacoes->get();

            $data = new DateTime($diarioData->diarioData);
            $data = $data->format('d/m/Y');


            if($data <= date('d/m/Y')){
                return redirect()->back()->with(["erro" => "Impossível editar datas passadas !"]);
            }else{
                return view('diariodata.editar', ['diarioData' => $diarioData, 'publicacoes' => $publicacoes]);
            }


        }else{
            return redirect('/home');
        }
    }


    public function deletar($id){

        if(Gate::allows('administrador', Auth::user())){

            $diarioData = DiarioData::orderBy('diarioDataID');

            $dataDiario = DiarioData::orderBy('diarioDataID');
            $dataDiario->select('*')->where('diarioDataID', '=', $id);
            $dataDiario = $dataDiario->first();

            $publicacoes = Publicacao::orderBy('dataEnvio');
            $publicacoes = $publicacoes->where('diarioDataID', '=', $id)->get();
            // validar deletar OBS**
            if(sizeof($publicacoes) == 0){

                $data = new DateTime($dataDiario->diarioData);
                $data = $data->format('d/m/Y');

                if($data <= date('d/m/Y')){
                    return redirect()->back()->with(["erro" => "Impossível deletar datas passadas !"]);
                }else{
                    $diarioData->where('diarioDataID', '=', $id)->delete();
                    return redirect('/diariodata/listar')->with("sucesso", "Diario Oficial Deletado");
                }


            }else{

                return redirect()->back()->with(["erro" => "Impossível deletar pois existem publicações vinculadas a este diário !", 'publicacoes' => $publicacoes]);

            }


        }else{
            return redirect('/home');
        }
    }

    public function validar($diarioData){

        $verificador = DiarioData::orderBy('diarioDataID');

        if(isset($diarioData->diarioDataID)){

            if( $verificador->select('*')->where('numeroDiario', '=', $diarioData->numeroDiario)->where('diarioDataID', '!=', $diarioData->diarioDataID)->count()){

                return 1;

            }

        }else{

            if( $verificador->select('*')->where('numeroDiario', '=', $diarioData->numeroDiario)->count()){

                return 1;

            }

        }

    }
}
