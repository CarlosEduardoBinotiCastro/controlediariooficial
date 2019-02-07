<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DiarioData;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Publicacao;
use App\Fatura;
use DateTime;
use Illuminate\Support\Facades\DB;



class DiarioDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private $paginacao = 10;

    public function listar($diario = null){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){
            $diariosDatas = DiarioData::orderBy('diarioData', 'desc');

            if($diario != null){
                $diariosDatas->where('numeroDiario', '=', $diario);
            }

            $diariosDatas = $diariosDatas->paginate($this->paginacao);
            return view('diariodata.listar', ['diariosDatas' => $diariosDatas]);
        }else{
            return redirect('/home');
        }

    }

    public function cadastrar(){
        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){
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

                    $diario = DB::table('diariodata')->orderBy('diarioData')->where('diarioDataID', '=', $request->diarioDataID)->first();

                    if($diario == null){
                        return redirect()->back()->with('erro', 'Diário não encontrado!');
                    }

                    if($diario->diarioPublicado == null){
                        $diarioData->where('diarioDataID', '=', $request->diarioDataID)->update(['diarioData' => $request->diarioData, 'numeroDiario' => $request->numeroDiario]);
                        DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Editou o diário '.$request->numeroDiario]);
                    }else{
                        return redirect()->back()->with('erro', 'Impossível editar diário anexado!');
                    }

                    return redirect('/diariodata/listar')->with("sucesso", "Diario Oficial Editado");
                }else{
                    DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Cadastrou o diário '.$request->numeroDiario]);

                    $diarioData->insert(['diarioData' => $request->diarioData, 'numeroDiario' => $request->numeroDiario]);
                    return redirect('/diariodata/listar')->with("sucesso", "Diario Oficial Cadastrado");
                }



            break;

        }

    }

    public function editar($id){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $diarioData = DiarioData::orderBy('diarioDataID');
            $diarioData->select('*')->where('diarioDataID', '=', $id);
            $diarioData = $diarioData->first();

            $publicacoes = Publicacao::orderBy('dataEnvio');
            $publicacoes->where('diarioDataID', '=', $id);
            $publicacoes = $publicacoes->get();

            $data = new DateTime($diarioData->diarioData);
            $data = $data->format('d/m/Y');


            if($data < date('d/m/Y')){
                return redirect()->back()->with(["erro" => "Impossível editar datas passadas !"]);
            }else{
                return view('diariodata.editar', ['diarioData' => $diarioData, 'publicacoes' => $publicacoes]);
            }


        }else{
            return redirect('/home');
        }
    }


    public function deletar($id){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $diarioData = DiarioData::orderBy('diarioDataID');

            $dataDiario = DiarioData::orderBy('diarioDataID');
            $dataDiario->select('*')->where('diarioDataID', '=', $id);
            $dataDiario = $dataDiario->first();

            $publicacoes = Publicacao::orderBy('dataEnvio');
            $publicacoes = $publicacoes->where('diarioDataID', '=', $id)->get();
            $faturas = Fatura::orderBy('dataEnvioFatura');
            $faturas = $faturas->where('diarioDataID', '=', $id)->get();

            // validar deletar OBS**
            if(sizeof($publicacoes) == 0){

                if(sizeof($faturas) == 0){
                    $data = new DateTime($dataDiario->diarioData);
                    $data = $data->format('d/m/Y');

                    if($data < date('d/m/Y')){
                        return redirect()->back()->with(["erro" => "Impossível deletar datas passadas !"]);
                    }else{
                        DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Deletou o diário '.$dataDiario->numeroDiario]);
                        $diarioData->where('diarioDataID', '=', $id)->delete();

                        if($dataDiario->diarioPublicado != null){
                            if(file_exists(storage_path("app/diarios/".$ano."/".$dataDiario->diarioPublicado))){
                                File::delete(storage_path("app/diarios/".$ano."/".$dataDiario->diarioPublicado));
                            }
                        }


                        return redirect('/diariodata/listar')->with("sucesso", "Diario Oficial Deletado");
                    }
                }else{
                    return redirect()->back()->with(["erro" => "Impossível deletar pois existem faturas vinculadas a este diário !", 'faturas' => $faturas]);
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


    public function listarFiltro(Request $request){

        if(isset($request->dataFinal)){
            if(($request->dataFinal != null && $request->dataFinal != "tudo") && ($request->dataInicial!= null && $request->dataInicial != "tudo")){
                if($request->dataInicial > $request->dataFinal){
                    return redirect()->back()->with('erro', 'Data inicial deve ser menor que data final!');
                }else{
                    $dataInicial = $request->dataInicial;
                    $dataFinal = $request->dataFinal;
                }
            }else{

                if(($request->dataInicial != null && $request->dataInicial != "tudo") && ($request->dataFinal == null || $request->dataFinal == "tudo")){
                    return redirect()->back()->with('erro', 'Ao filtrar por período, preencha as duas datas!');
                }
                if(($request->dataFinal != null && $request->dataFinal != "tudo") && ($request->dataInicial!= null || $request->dataInicial == "tudo")){
                    return redirect()->back()->with('erro', 'Ao filtrar por período, preencha as duas datas!');
                }
                $dataInicial = "tudo";
                $dataFinal = "tudo";
            }

        }else{
            $dataInicial = "tudo";
            $dataFinal = "tudo";
        }

        if($request->diario != null){
            $diario = $request->diario;
        }else{
            $diario = "tudo";
        }

        if($dataInicial == "tudo" && $dataFinal == "tudo" && $diario == "tudo"){
            return redirect('/diariodata/listar');
        }else{
            return redirect()->route('listarDiarios', ['diario'=>$diario]);
            // return redirect()->route('listarDiarios', ['diario'=>$diario, 'dataInicial'=>$dataInicial, 'dataFinal'=>$dataFinal]);
        }

    }


    public function anexarDiario(Request $request){

        // validando

        if(((filesize($request->arquivo) / 1024)/1024) > 30){
            return redirect()->back()->with('erro', 'Tamanho do arquivo excedido!');
        }

        if(pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION) != "pdf"){
            return redirect()->back()->with('erro', 'arquivo na extensão errada!');
        }

        $diario = DB::table('diariodata')->orderBy('diarioData')->where('diarioDataID', '=', $request->diarioDataID)->first();

        if($diario == null){
            return redirect()->back()->with('erro', 'Diário não encontrado!');
        }

        try {

            $data = explode('-', $diario->diarioData);
            $ano = $data[0];


            $filename = $diario->numeroDiario."_".$diario->diarioData.".".pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION);

            $request->arquivo->storeAs("diarios/".$ano."/", $filename);

            DB::table('diariodata')->orderBy('diarioData')->where('diarioDataID', '=', $request->diarioDataID)->update(['diarioPublicado' => $filename]);

            return redirect()->back()->with('sucesso', 'Diário anexado!');

        } catch (\Exception $e) {

            if(file_exists(storage_path("app/diarios/".$ano."/".$filename))){
                File::delete([storage_path("app/diarios/".$ano."/".$filename)]);
            }

            return redirect()->back()->with('erro', 'um erro ocorreu! ERRO: '.$e->getMessage());

        }

    }

    public function remover(Request $request){

        $diario = DB::table('diariodata')->orderBy('diarioData')->where('diarioDataID', '=', $request->diarioDataID)->first();

        if($diario == null){
            return redirect()->back()->with('erro', 'Diário não encontrado!');
        }

        $data = explode('-', $diario->diarioData);
        $ano = $data[0];

        if(file_exists(storage_path("app/diarios/".$ano."/".$diario->diarioPublicado))){

            File::delete(storage_path("app/diarios/".$ano."/".$diario->diarioPublicado));
        }

        DB::table('diariodata')->orderBy('diarioData')->where('diarioDataID', '=', $request->diarioDataID)->update(['diarioPublicado' => null]);

        return redirect()->back()->with('sucesso', 'Arquivo removido do Diário!');
    }


    public function download($id){
        $diario = DB::table('diariodata')->orderBy('diarioData')->where('diarioDataID', '=', $id)->first();

        if($diario == null){
            return redirect()->back()->with('erro', 'Diário não encontrado!');
        }

        $data = explode('-', $diario->diarioData);
        $ano = $data[0];

        if(file_exists(storage_path("app/diarios/".$ano."/".$diario->diarioPublicado))){
            return Response::download(storage_path("app/diarios/".$ano."/".$diario->diarioPublicado));
        }else{
            return redirect()->back()->with('erro', 'Arquivo não encontrado!');
        }

    }

}
