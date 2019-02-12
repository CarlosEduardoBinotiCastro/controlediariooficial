<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Publicacao;
use Illuminate\Support\Facades\DB;
use App\Caderno;
use App\TipoDocumento;
use App\CadernoTipoDocumento;
use Illuminate\Support\Facades\Auth;
use App\DiarioData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use App\OrgaoRequisitante;
use Illuminate\Support\Facades\Session;
use DateTime;
use Illuminate\Support\Collection;
use App\Fatura;
use Illuminate\Support\Facades\File;
use BPDF;
use Zipper;


class PublicacoesController extends Controller
{
    //
    private $paginacao = 20;
    public $arquivos = array();
    public $diretorio = "";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listarFiltroApagadas(Request $request){

        if($request->nomeUsuario != null){
            $nome = $request->nomeUsuario;
        }else{
            $nome = "tudo";
        }

        if($request->protocolo != null){
            $protocolo = $request->protocolo;
        }else{
            $protocolo = "tudo";
        }

        if($request->diario != null){
            $diario = $request->diario;
        }else{
            $diario = "tudo";
        }

        if($request->titulo != null){
            $titulo = $request->titulo;
            $titulo = str_replace('/', '¨', $titulo);
        }else{
            $titulo = "tudo";
        }

        if($request->orgao != null){
            $orgao = $request->orgao;
        }else{
            $orgao = "tudo";
        }

        if(($diario == "tudo") && ($nome == "tudo") && ($protocolo == "tudo") && ($orgao == "tudo") && ($titulo == "tudo")){
            return redirect('publicacao/apagadas');
        }else{
            return redirect()->route('listarApagadas', ['nome' => $nome, 'protocolo' => $protocolo, 'diario' => $diario, 'orgao' => $orgao, 'titulo' => $titulo]);
        }

    }

    public function apagadas($nome = null, $protocolo = null, $diario = null, $orgao = null, $titulo = null){
        if(!Gate::allows('faturas', Auth::user())){
        $publicacoes = Publicacao::orderBy('protocoloAno', 'desc')->orderBy('protocolo', 'desc');
        $orgaos = OrgaoRequisitante::orderBy('orgaoNome')->get();

        $publicacoes->join('users as criado', 'criado.id', 'publicacao.usuarioID');
        $publicacoes->join('users as apagado', 'apagado.id', 'publicacao.usuarioIDApagou');
        $publicacoes->join('diariodata', 'diariodata.diarioDataID', 'publicacao.diarioDataID');
        $publicacoes->join('situacao', 'situacao.situacaoID', 'publicacao.situacaoID');
        $publicacoes->join('orgaorequisitante', 'orgaorequisitante.orgaoID', 'publicacao.orgaoID');

        if($nome != null && $nome != "tudo"){
            $arrayPalavras = explode(' ', $nome);
            foreach ($arrayPalavras as $palavra) {
                $publicacoes->where('criado.name', 'like', '%' . $palavra . '%');
            }
        }

        if($protocolo != null && $protocolo != "tudo"){
            if(strlen($protocolo) > 7){
                $publicacoes->where('protocoloCompleto', '=', $protocolo);
            }else{
                $publicacoes->where('protocolo', '=', null);
            }

        }

        if($diario != null && $diario != "tudo"){
            $publicacoes->where('diariodata.diarioData', '=', $diario);
        }

        if($titulo != null && $titulo != "tudo"){
            $titulo = str_replace('¨', '/', $titulo);
            $arrayPalavras = explode(' ', $titulo);
            foreach ($arrayPalavras as $palavra) {
                $publicacoes->where('publicacao.titulo', 'like', '%' . $palavra . '%');
            }
        }

        if($orgao != null && $orgao != "tudo"){
            $publicacoes->where('publicacao.orgaoID', '=', $orgao);
        }

        $publicacoes->where('situacao.situacaoNome', '=', "Apagada");

        if(!( Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()) ) ){
            $publicacoes->where('publicacao.orgaoID', '=', Auth::user()->orgaoID);

        }

        $publicacoes->select('publicacao.*', 'situacao.situacaoNome', 'diariodata.diarioData', 'diariodata.numeroDiario', 'criado.name as nomeUsuarioCriou', 'apagado.name as nomeUsuarioApagou', 'orgaorequisitante.orgaoNome');
        $publicacoes = $publicacoes->paginate($this->paginacao);

        return view('publicacao.apagadas', ['publicacoes' => $publicacoes, 'orgaos' => $orgaos]);
        }else{
            return redirect('home');
        }
    }


    public function listar($nome = null, $protocolo = null, $diario = null, $situacao = null, $orgao = null, $titulo = null){

        if(!Gate::allows('faturas', Auth::user())){

        $situacoes = DB::table('situacao')->get();
        $orgaos = OrgaoRequisitante::orderBy('orgaoNome')->get();


        $publicacoes = Publicacao::orderBy('protocoloAno', 'desc')->orderBy('protocolo', 'desc');

        $publicacoes->join('users', 'users.id', 'publicacao.usuarioID');
        $publicacoes->join('diariodata', 'diariodata.diarioDataID', 'publicacao.diarioDataID');
        $publicacoes->join('situacao', 'situacao.situacaoID', 'publicacao.situacaoID');
        $publicacoes->join('orgaorequisitante', 'orgaorequisitante.orgaoID', 'publicacao.orgaoID');

        if($nome != null && $nome != "tudo"){
            $arrayPalavras = explode(' ', $nome);
            foreach ($arrayPalavras as $palavra) {
                $publicacoes->where('users.name', 'like', '%' . $palavra . '%');
            }
        }

        if($titulo != null && $titulo != "tudo"){
            $titulo = str_replace('¨', '/', $titulo);

            $arrayPalavras = explode(' ', $titulo);
            foreach ($arrayPalavras as $palavra) {
                $publicacoes->where('publicacao.titulo', 'like', '%' . $palavra . '%');
            }
        }

        if($protocolo != null && $protocolo != "tudo"){
            if(strlen($protocolo) > 7){
                $publicacoes->where('protocoloCompleto', '=', $protocolo);
            }else{
                $publicacoes->where('protocolo', '=', null);
            }
        }

        if($diario != null && $diario != "tudo"){
            $publicacoes->where('diariodata.diarioData', '=', $diario);
        }

        if($situacao != null && $situacao != "tudo"){
            $publicacoes->where('situacao.situacaoNome', '=', $situacao);
        }

        if($orgao != null && $orgao != "tudo"){
                $publicacoes->where('publicacao.orgaoID', '=', $orgao);
        }


        if(!( Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()) ) ){
            $publicacoes->where('publicacao.orgaoID', '=', Auth::user()->orgaoID);
        }

        $publicacoes->select('publicacao.*', 'situacao.situacaoNome', 'diariodata.diarioData', 'diariodata.numeroDiario', 'diariodata.diarioPublicado', 'users.name as nomeUsuario', 'orgaorequisitante.orgaoNome');
        $publicacoes = $publicacoes->paginate($this->paginacao);

        $faturas = Fatura::orderBy('protocoloCompleto');
        $faturas->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');
        $faturas->where('situacao.situacaoID', '=', 4);
        $faturas = $faturas->get();
        return view('publicacao.listar', ['publicacoes' => $publicacoes, 'situacoes' => $situacoes, 'orgaos' => $orgaos, 'faturas' => $faturas]);

        }else{
            return redirect('home');
        }
    }


    public function listarFiltro(Request $request){

        if($request->nomeUsuario != null){
            $nome = $request->nomeUsuario;
        }else{
            $nome = "tudo";
        }

        if($request->protocolo != null){
            $protocolo = $request->protocolo;
        }else{
            $protocolo = "tudo";
        }

        if($request->diario != null){
            $diario = $request->diario;
        }else{
            $diario = "tudo";
        }

        if($request->titulo != null){
            $titulo = $request->titulo;
            $titulo = str_replace('/', '¨', $titulo);
        }else{
            $titulo = "tudo";
        }

        if($request->situacao == "tudo" ){
            $situacao = "tudo";
        }else{
            $situacao = $request->situacao;
        }

        if($request->orgao != null){
            $orgao = $request->orgao;
        }else{
            $orgao = "tudo";
        }


        if(($diario == "tudo") && ($nome == "tudo") && ($protocolo == "tudo") && ($situacao == "tudo") && ($orgao == "tudo") && ($titulo == "tudo")){
            return redirect('publicacao/listar');
        }else{
            return redirect()->route('listarPublicacoes', ['nome' => $nome, 'protocolo' => $protocolo, 'diario' => $diario, 'situacao' => $situacao, 'orgao' => $orgao, 'titulo' => $titulo]);
        }

    }


    public function cadastrar(){

        if(!Gate::allows('faturas', Auth::user())){

        $cadernoFatura = DB::table('configuracaofatura')->select('cadernoID')->first();

        if($cadernoFatura->cadernoID != null){
            $usuarioCaderno = DB::table('usuariocaderno')->join('caderno', 'caderno.cadernoID', 'usuariocaderno.cadernoID')->where('usuarioID', '=', Auth::user()->id)->where('caderno.cadernoID', '!=', $cadernoFatura->cadernoID)->select('caderno.*')->get();
        }else {
            $usuarioCaderno = DB::table('usuariocaderno')->join('caderno', 'caderno.cadernoID', 'usuariocaderno.cadernoID')->where('usuarioID', '=', Auth::user()->id)->select('caderno.*')->get();
        }

        $horaEnvio = Auth::user()->horaEnvio;
        $documentos = TipoDocumento::orderBy('tipoDocumento');
        $documentos->join('cadernotipodocumento', 'tipodocumento.tipoID',  '=', 'cadernotipodocumento.tipoID');
        foreach($usuarioCaderno as $caderno){
            $documentos->orWhere('cadernotipodocumento.cadernoID', '=', $caderno->cadernoID);
        }



        $documentos->select('cadernotipodocumento.cadernoID', 'tipodocumento.tipoID', 'tipodocumento.tipoDocumento');
        $documentos = $documentos->get();

        $diariosDatas = DiarioData::orderBy('diarioData')->where('diarioData', '>', date('Y-m-d'))->get();


        // vericar datas limites para os diários

        $diariosDatasLimites = Collection::make([]);

        foreach($diariosDatas as $diario){

            $diaDiarioDate = new DateTime($diario->diarioData);
            $verificaDiaUtil = false;
            $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaDiarioDate->format('Y-m-d'))));

            do{
                $finalDeSemana = date('N', strtotime($diaUtil));
                if(!($finalDeSemana == '7' || $finalDeSemana == '6')){
                    if( !(DB::table('diasnaouteis')->where('diaNaoUtilData', '=', $diaUtil)->count()) ) {
                        $verificaDiaUtil = true;
                        $diariosDatasLimites->push(['diarioData' => $diario->diarioData, 'diarioDataID' => $diario->diarioDataID, 'numeroDiario' => $diario->numeroDiario, 'diaLimite' => $diaUtil]);
                    }else{

                    }
                }

                $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaUtil)));
            }while($verificaDiaUtil == false);

        }

        // fim dos limites para os diarios
        return view('publicacao.cadastrar', ['usuarioCaderno' => $usuarioCaderno, 'documentos' => $documentos, 'diarioDatas' => json_encode($diariosDatasLimites), 'horaEnvio' => $horaEnvio]);

        }else{
            return redirect('home');
        }
    }


    public function salvar(Request $request){

        // organiza os arquivos em um array

        $contador = 0;
        $post = $request->all();

        if(!isset($request->manterArquivo)){

            $files = array();
            foreach ($request->all() as $key => $value) {
                $pattern = '/arquivo/';
                if(preg_match($pattern, $key)){

                    array_push($files, $value);
                    unset($post[$key]);
                }
                $contador += 1;
            }

            // o index do array de arquivo sempre será 0
            array_push($post, $files);

        }

        switch ($this->validar($post)){

            case 1:
                return redirect()->back()->with('erro', "Arquivos não devem exceder o tamanho de 30 MB!")->withInput();
            break;

            case 2:
                return redirect()->back()->with('erro', "Arquivo na extensão incorreta!")->withInput();
            break;

            case 3:
                return redirect()->back()->with('erro', "Tamanho da descrição excedida!")->withInput();
            break;

            case 4:
                return redirect()->back()->with('erro', "Tamanho do título excedido!")->withInput();
            break;

            case 5:
                return redirect()->back()->with('erro', "Data de envio ultrapassada!")->withInput();
            break;

            case 6:
                return redirect()->back()->with('erro', "Arquivos com nomes iguais não podem existir!")->withInput();
            break;

            default:

                if(isset($request->protocolo)){

                    if($request->protocolo!= null){
                        if(strlen($request->protocolo) > 7){
                            $protocoloCompleto = $request->protocolo;
                        }else{
                            return redirect()->back()->with('erro', "Protocolo invalido!")->withInput();
                        }
                    }else{
                        return redirect()->back()->with('erro', "Protocolo invalido!")->withInput();
                    }


                    if(Session::get('protocoloEditar') != $request->protocolo){
                        return redirect()->back()->with('erro', "Protocolo invalido (Tentativa de Violar o Sistema) !!")->withInput();
                    }


                    try {

                        if(!isset($request->manterArquivo)){

                            DB::beginTransaction();

                            // @mudar
                            $publicacao = Publicacao::orderBy('dataEnvio')->where('protocoloCompleto', '=', $request->protocolo)->first();
                            $this->diretorio = $publicacao->protocoloAno."/".$request->protocolo;

                            // buscando arquivos antigos no banco

                            $arquivosAntigos =  DB::table('publicacaoarquivo')->where('protocoloCompleto', '=', $request->protocolo)->select('arquivo')->get();


                            foreach($arquivosAntigos as $arquivoAntigo){
                                if(file_exists(storage_path("app/".$publicacao->protocoloAno."/".$request->protocolo."/".$arquivoAntigo->arquivo))){
                                    File::delete(storage_path("app/".$publicacao->protocoloAno."/".$request->protocolo."/".$arquivoAntigo->arquivo));
                                }
                            }


                            // deletando os arquivos antigos no banco
                            DB::table('publicacaoarquivo')->where('protocoloCompleto', '=', $request->protocolo)->delete();

                            // salvando asrquivos

                            foreach ($post[0] as $arquivo) {
                                array_push($this->arquivos,$arquivo->getClientOriginalName());
                                $arquivo->storeAs($this->diretorio."/", $arquivo->getClientOriginalName());
                            }

                            $contador = 0;
                            foreach ($this->arquivos as $arquivo) {
                                DB::table('publicacaoarquivo')->insert(['protocoloCompleto' => $request->protocolo, 'arquivo' =>$post[0][$contador]->getClientOriginalName()]);
                                $contador++;
                            }


                            // alterando no banco
                            DB::table('publicacao')->where('protocoloCompleto', '=', $protocoloCompleto)->update(['cadernoID' => $request->cadernoID, 'tipoID' => $request->tipoID, 'usuarioID' => Auth::user()->id, 'diarioDataID' => $request->diarioDataID, 'dataEnvio' => date('Y-m-d H:i:s'), 'titulo' => $request->titulo, 'descricao' => $request->descricao, 'situacaoID' => 4, 'rejeitadaDescricao' => null]);
                        }else{

                            DB::beginTransaction();
                            DB::table('publicacao')->where('protocoloCompleto', '=', $protocoloCompleto)->update(['cadernoID' => $request->cadernoID, 'tipoID' => $request->tipoID, 'usuarioID' => Auth::user()->id, 'diarioDataID' => $request->diarioDataID, 'dataEnvio' => date('Y-m-d H:i:s'), 'titulo' => $request->titulo, 'descricao' => $request->descricao, 'situacaoID' => 4, 'rejeitadaDescricao' => null]);
                        }
                        DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Editou uma Publicação de protocolo '.$protocoloCompleto]);

                        DB::commit();

                        Session::forget('protocoloEditar');

                        return redirect('/publicacao/listar')->with('sucesso', 'Publicação Editada com Sucesso');

                    } catch (\Exception $e) {

                        if(!isset($request->manterArquivo)){
                            DB::rollBack();


                            if(file_exists(storage_path("app/".$this->diretorio))){
                                Storage::deleteDirectory($this->diretorio);
                            }

                            DB::beginTransaction();
                                DB::table('publicacao')->where('protocoloCompleto', '=', $request->protocolo)->update(['situacaoID' => 2, 'usuarioIDApagou' => Auth::user()->id, 'dataApagada' => date('Y-m-d H:i:s')]);
                                DB::table('publicacaoarquivo')->where('protocoloCompleto', '=', $request->protocolo)->delete();
                            DB::commit();

                            return redirect('home')->with('erro', "Um erro crítico durante a operação ocorreu! Foi necessário a remoção da publicação no sistema. Por favor tente enviar novamente!".$e->getMessage());
                        }else{
                            DB::rollBack();
                            return redirect()->back()->with('erro', "Um erro durante a operação ocorreu!".$e->getMessage())->withInput();
                        }

                    }


                }else{
                    try {

                        if(DB::table('publicacao')->where('protocoloAno', '=', date('Y'))->count() ){
                            $protocolo = DB::table('publicacao')->where('protocoloAno', '=', date('Y'))->max('protocolo') + 1;
                        }else{
                            $protocolo = 0;
                        }

                        DB::beginTransaction();
                        $this->verificaProtocolo($protocolo, $post);

                        return redirect('/publicacao/listar')->with('sucesso', 'Publicação Enviada com Sucesso');

                    } catch (\Exception $e) {


                        if(file_exists(storage_path("app/".$this->diretorio))){
                            Storage::deleteDirectory($this->diretorio);
                        }


                        DB::rollBack();
                        return redirect()->back()->with('erro', "Um erro durante a operação ocorreu!".$e->getMessage())->withInput();
                    }

                }

            break;

        }

    }

    public function verificaProtocolo($protocolo, $request){
        if(DB::table('publicacao')->where('protocoloAno', '=', date('Y'))->where('protocolo', '=', $protocolo)->count()){
            $protocolo++;
            $this->verificaProtocolo($protocolo, $request);
        }else {
            DB::table('publicacao')->insert(['situacaoID' => 4, 'cadernoID' => $request['cadernoID'], 'tipoID' => $request['tipoID'], 'usuarioID' => Auth::user()->id, 'diarioDataID' => $request['diarioDataID'], 'dataEnvio' => date('Y-m-d H:i:s'), 'titulo' => $request['titulo'], 'descricao' => $request['descricao'], 'protocolo' => $protocolo, 'protocoloAno' => date('Y'), 'protocoloCompleto' => $protocolo.date('Y').'PUB', 'orgaoID' => Auth::user()->orgaoID]);


            // @mudar
            $this->diretorio = date('Y')."/".$protocolo.date('Y').'PUB';

            // $this->diretorio =  $protocolo.date('Y').'PUB';
            File::makeDirectory(storage_path("app/".$this->diretorio));


            foreach ($request[0] as $arquivo) {
                array_push($this->arquivos,$arquivo->getClientOriginalName());
                $arquivo->storeAs($this->diretorio."/", $arquivo->getClientOriginalName());
            }

            $contador = 0;
            foreach ($this->arquivos as $arquivo) {
                DB::table('publicacaoarquivo')->insert(['protocoloCompleto' => $protocolo.date('Y').'PUB', 'arquivo' =>$request[0][$contador]->getClientOriginalName()]);
                $contador++;
            }

            DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Cadastrou uma Publicação de protocolo '.$protocolo.date('Y').'PUB']);

            DB::commit();
        }
    }

    public function validar($request){

        $diarioTemp = DiarioData::orderBy('diarioDataID')->where('diarioDataID', '=', $request['diarioDataID'])->first();

        if(!isset($request['manterArquivo'])){

            // Foreach para cada arquivo no upload
            $tamanhoArquivo = 0;
            foreach ($request[0] as $arquivo) {

                $extensões = array('pdf', 'docx', 'odt', 'rtf', 'doc', 'xlsx', 'xls');
                $extensao = pathinfo($arquivo->getClientOriginalName(), PATHINFO_EXTENSION);

                if(!in_array($extensao, $extensões)){
                    return 2;
                }


                $tamanhoArquivo += ((filesize($arquivo) / 1024)/1024);
            }

            if($tamanhoArquivo > 30){
                return 1;
            }

            // verifica se existe arquivos com nomes iguais
            foreach ($request[0] as $arquivo) {
                $contador = 0;
                foreach ($request[0] as $comparar) {
                    if($arquivo->getClientOriginalName() == $comparar->getClientOriginalName()){
                        $contador++;
                    }
                }
                if($contador > 1){
                    return 6;
                }
            }

        }

        if(strlen($request['titulo']) > 100){
            return 4;
        }

        // Verificação do lado do servidor sobre a data do envio par o diario !

        $diaDiarioDate = new DateTime($diarioTemp['diarioData']);
        $verificaDiaUtil = false;
        $diaUtil = date('Y-m-d', strtotime($diaDiarioDate->format('Y-m-d')));

        do{
            $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaUtil)));
            $finalDeSemana = date('N', strtotime($diaUtil));
            if(!($finalDeSemana == '7' || $finalDeSemana == '6')){
                if( !(DB::table('diasnaouteis')->where('diaNaoUtilData', '=', $diaUtil)->count()) ) {
                    $verificaDiaUtil = true;
                }else{
                }
            }

        }while($verificaDiaUtil == false);

        if($diaUtil <= date('Y-m-d')){
            if($diaUtil == date('Y-m-d')){
                if(Auth::user()->horaEnvio >= date('H:i:s')){

                }else{
                    return 5;
                }
            }else{
                return 5;
            }
        }

        // fim da verificação do lado do servidor
    }



    public function editar($protocolo){

        if(!Gate::allows('faturas', Auth::user())){

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                $protocoloCompleto = $protocolo;
            }else{
                $protocoloCompleto = null;
            }
        }



        // Verifica se essa publicação foi apagada

        $usuarioIDApagou = Publicacao::orderBy('protocoloAno', 'desc');
        $usuarioIDApagou->where('protocoloCompleto', '=', $protocoloCompleto);
        $usuarioIDApagou = $usuarioIDApagou->first();


        //verifica se a publicação é nula!
        //se não, verifica se o usuario é comum e esta tentando entrar com protocolo de uma publicação que não é dele

        if($usuarioIDApagou != null){
            if(!( Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()) ) && Auth::user()->orgaoID != $usuarioIDApagou->orgaoID){
                return redirect('/home')->with('erro', 'Você não tem permissão!');
              }
            // Busca todos os dados da visualização

            $publicacao = Publicacao::orderBy('protocoloAno', 'desc')->orderBy('protocolo', 'desc');

            if($usuarioIDApagou->usuarioIDApagou != null){
                $publicacao->join('users as apagado', 'apagado.id', 'publicacao.usuarioIDApagou');
            }

            $publicacao->join('users as criado', 'criado.id', 'publicacao.usuarioID');
            $publicacao->join('diariodata', 'diariodata.diarioDataID', 'publicacao.diarioDataID');
            $publicacao->join('situacao', 'situacao.situacaoID', 'publicacao.situacaoID');
            $publicacao->join('caderno', 'caderno.cadernoID', 'publicacao.cadernoID');
            $publicacao->join('tipodocumento', 'tipodocumento.tipoID', 'publicacao.tipoID');
            $publicacao->where('protocoloCompleto', '=', $protocoloCompleto);
            if($usuarioIDApagou->usuarioIDApagou != null){
                $publicacao->select('publicacao.*', 'caderno.cadernoNome', 'tipodocumento.tipoDocumento', 'diariodata.*', 'situacao.*', 'criado.name as nomeUsuarioCriado', 'apagado.name as nomeUsuarioApagado');
            }else{
                $publicacao->select('publicacao.*', 'caderno.cadernoNome', 'tipodocumento.tipoDocumento', 'diariodata.*', 'situacao.*', 'criado.name as nomeUsuarioCriado');
            }
            $publicacao = $publicacao->first();

        }else{
            return redirect('/home')->with('erro', 'Não existe publicação com esse protocolo!');
        }


        // verifica pode editar

        if($usuarioIDApagou->usuarioIDApagou != null){
            $podeEditar = false;
        }else{
            if(  ( Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()) ) && $publicacao->situacaoNome != "Publicada" && $publicacao->situacaoNome != "Apagada"){
                $podeEditar = true;
            }else{
                if ($publicacao->situacaoNome == "Apagada" || $publicacao->situacaoNome == "Publicada" || $publicacao->situacaoNome == "Aceita" ){
                    $podeEditar = false;
                }else{
                    $podeEditar = true;
                }
            }
        }

        // Se pode editar então carrega os dados para edição e retorna view

        if($podeEditar){
            Session::put('protocoloEditar', $protocolo);
            $situacoes = DB::table('situacao')->get();

            $cadernoFatura = DB::table('configuracaofatura')->select('cadernoID')->first();

            if($cadernoFatura->cadernoID != null){
                $usuarioCaderno = DB::table('usuariocaderno')->join('caderno', 'caderno.cadernoID', 'usuariocaderno.cadernoID')->where('usuarioID', '=', Auth::user()->id)->where('caderno.cadernoID', '!=', $cadernoFatura->cadernoID)->select('caderno.*')->get();
            }else {
                $usuarioCaderno = DB::table('usuariocaderno')->join('caderno', 'caderno.cadernoID', 'usuariocaderno.cadernoID')->where('usuarioID', '=', Auth::user()->id)->select('caderno.*')->get();
            }


            $documentos = TipoDocumento::orderBy('tipoDocumento');
            $documentos->join('cadernotipodocumento', 'tipodocumento.tipoID',  '=', 'cadernotipodocumento.tipoID');
            foreach($usuarioCaderno as $caderno){
                $documentos->orWhere('cadernotipodocumento.cadernoID', '=', $caderno->cadernoID);
            }
            $documentos->select('cadernotipodocumento.cadernoID', 'tipodocumento.tipoID', 'tipodocumento.tipoDocumento');
            $documentos = $documentos->get();

            $diariosDatas = DiarioData::orderBy('diarioData')->where('diarioData', '>', date('Y-m-d'))->get();
            $horaEnvio = Auth::user()->horaEnvio;
            // Inicio da verificação dos dias limites

            $diariosDatasLimites = Collection::make([]);

            foreach($diariosDatas as $diario){

                $diaDiarioDate = new DateTime($diario->diarioData);
                $verificaDiaUtil = false;
                $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaDiarioDate->format('Y-m-d'))));

                do{
                    $finalDeSemana = date('N', strtotime($diaUtil));
                    if(!($finalDeSemana == '7' || $finalDeSemana == '6')){
                        if( !(DB::table('diasnaouteis')->where('diaNaoUtilData', '=', $diaUtil)->count()) ) {
                            $verificaDiaUtil = true;
                            $diariosDatasLimites->push(['diarioData' => $diario->diarioData, 'diarioDataID' => $diario->diarioDataID, 'numeroDiario' => $diario->numeroDiario, 'diaLimite' => $diaUtil]);
                        }else{

                        }
                    }

                    $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaUtil)));
                }while($verificaDiaUtil == false);

            }
            // fim dos limites para os diarios

            return view('publicacao.editar', ['publicacao' => $publicacao, 'podeEditar' => $podeEditar, 'diarioDatas' => $diariosDatas, 'documentos' => $documentos, 'usuarioCaderno' => $usuarioCaderno, 'situacao' => $situacoes, 'diarioDatas' => json_encode($diariosDatasLimites), 'horaEnvio' => $horaEnvio]);
        }else{
            return redirect('home')->with('erro', 'Você não pode realizar essa ação!');
        }

        }else{
            return redirect('home');
        }
    }


    public function ver($protocolo){

        if(!Gate::allows('faturas', Auth::user())){

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                $protocoloCompleto = $protocolo;
            }else{
                $protocoloCompleto = null;
            }
        }

        // Verifica se essa publicação foi apagada

        $usuarioIDApagou = Publicacao::orderBy('protocoloAno', 'desc');
        $usuarioIDApagou->where('protocoloCompleto', '=', $protocoloCompleto);
        $usuarioIDApagou = $usuarioIDApagou->first();


        //verifica se a publicação é nula!
        //se não, verifica se o usuario é comum e esta tentando entrar com protocolo de uma publicação que não é dele

        if($usuarioIDApagou != null){
            if(!(  Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()) ) && Auth::user()->orgaoID != $usuarioIDApagou->orgaoID){
                return redirect('/home')->with('erro', 'Você não tem permissão!');
              }
            // Busca todos os dados da visualização

            $publicacao = Publicacao::orderBy('protocoloAno', 'desc')->orderBy('protocolo', 'desc');

            if($usuarioIDApagou->usuarioIDApagou != null){
                $publicacao->join('users as apagado', 'apagado.id', 'publicacao.usuarioIDApagou');
            }

            $publicacao->join('users as criado', 'criado.id', 'publicacao.usuarioID');
            $publicacao->join('diariodata', 'diariodata.diarioDataID', 'publicacao.diarioDataID');
            $publicacao->join('situacao', 'situacao.situacaoID', 'publicacao.situacaoID');
            $publicacao->join('caderno', 'caderno.cadernoID', 'publicacao.cadernoID');
            $publicacao->join('tipodocumento', 'tipodocumento.tipoID', 'publicacao.tipoID');
            $publicacao->join('orgaorequisitante', 'orgaorequisitante.orgaoID', 'publicacao.orgaoID');
            $publicacao->join('orgaorequisitante as orgaousuario', 'orgaousuario.orgaoID', 'criado.orgaoID');
            $publicacao->where('protocoloCompleto', '=', $protocoloCompleto);
            if($usuarioIDApagou->usuarioIDApagou != null){
                $publicacao->select('publicacao.*', 'caderno.cadernoNome', 'tipodocumento.tipoDocumento', 'diariodata.*', 'situacao.*', 'criado.name as nomeUsuarioCriado', 'apagado.name as nomeUsuarioApagado', 'orgaorequisitante.orgaoNome', 'criado.email as emailUsuarioEmitiu', 'criado.telefoneSetor as telefoneSetorUsuarioEmitiu', 'criado.telefoneCelular as telefoneCelularUsuarioEmitiu', 'orgaousuario.orgaoNome as orgaoNomeUsuario');
            }else{
                $publicacao->select('publicacao.*', 'caderno.cadernoNome', 'tipodocumento.tipoDocumento', 'diariodata.*', 'situacao.*', 'criado.name as nomeUsuarioCriado', 'orgaorequisitante.orgaoNome', 'criado.email as emailUsuarioEmitiu', 'criado.telefoneSetor as telefoneSetorUsuarioEmitiu', 'criado.telefoneCelular as telefoneCelularUsuarioEmitiu', 'orgaousuario.orgaoNome as orgaoNomeUsuario');
            }
            $publicacao = $publicacao->first();

        }else{
            return redirect('/home')->with('erro', 'Não existe publicação com esse protocolo!');
        }


        // pega a url voltar e salva
        if(url()->previous() != url()->current()){
            Session::put('urlVoltar', url()->previous());
        }


        return view('publicacao.ver', ['publicacao' => $publicacao]);

        }else{
            return redirect('home');
        }
    }


    // Download de Publicações pelo protocolo
    public function download($protocolo){

        if(!Gate::allows('faturas', Auth::user())){

        $publicacao = Publicacao::orderBy('protocoloAno', 'desc');
        $publicacao->join('caderno', 'caderno.cadernoID', 'publicacao.cadernoID');
        $publicacao->join('tipodocumento', 'tipodocumento.tipoID', 'publicacao.tipoID');
        $publicacao->join('users as criado', 'criado.id', 'publicacao.usuarioID');
        $publicacao->join('diariodata', 'diariodata.diarioDataID', 'publicacao.diarioDataID');

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                $publicacao->where('protocoloCompleto', '=', $protocolo);
            }else{
                $publicacao->where('protocolo', '=', null);
            }
        }else{
            $publicacao->where('protocolo', '=', null);
        }

        $publicacao = $publicacao->first();

        if($publicacao == null){

            return redirect()->back()->with('erro', 'Protocolo não encontrado!');
        }

        if($publicacao->usuarioIDApagou != null){
            return redirect()->back()->with('erro', 'Arquivo não encontrado!');
        }

        if ($publicacao != null) {
            if(!( Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()) ) && Auth::user()->orgaoID != $publicacao->orgaoID){
                return redirect()->back()->with('erro', 'Você não tem permissão!');
            }
        }else{
            return redirect()->back()->with('erro', 'Arquivo não encontrado!');
        }




        // if(file_exists(storage_path("app/".$protocolo))){

        //     $files = glob(storage_path("app/".$protocolo."/*"));
        //     Zipper::make(storage_path("app/".$protocolo."/").$protocolo.'.zip')->add($files)->close();

        //     return response()->download(storage_path("app/".$protocolo."/".$protocolo.'.zip'))->deleteFileAfterSend(true);

        // }else{
        //     return redirect()->back()->with('erro', 'Arquivo não Encontrado!');
        // }

        // @mudar
        if(file_exists(storage_path("app/".$publicacao->protocoloAno."/".$protocolo))){

            $files = glob(storage_path("app/".$publicacao->protocoloAno."/".$protocolo."/*"));

            if(sizeof($files) > 1){
                Zipper::make(storage_path("app/".$publicacao->protocoloAno."/".$protocolo."/").$protocolo.'.zip')->add($files)->close();
                return response()->download(storage_path("app/".$publicacao->protocoloAno."/".$protocolo."/".$protocolo.'.zip'))->deleteFileAfterSend(true);
            }else{
                $arquivosPublicação = DB::table('publicacaoarquivo')->where('protocoloCompleto', '=', $publicacao->protocoloCompleto)->first();
                return Response::download(storage_path("app/".$publicacao->protocoloAno."/".$protocolo."/".$arquivosPublicação->arquivo));
            }


        }else{
            return redirect()->back()->with('erro', 'Arquivo não Encontrado!');
        }

        }else{
            return redirect('home');
        }
    }


    // função para aceitar uma publicação
    public function aceitar(Request $request){
        $protocolo = $request->protocolo;
        $publicacao = Publicacao::orderBy('protocoloAno', 'desc');

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                if(DB::table('publicacao')->where('protocoloCompleto', '=', $protocolo)->count()){
                    $publicacao->where('protocoloCompleto', '=', $protocolo);
                }else{
                    return redirect()->back()->with(['erro' => 'Publicação não encontrada!']);
                }
            }else{
                return redirect()->back()->with(['erro' => 'Publicação não encontrada!']);
            }
        }else{
            return redirect()->back()->with(['erro' => 'Publicação não encontrada!']);
        }

        DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Aceitou uma Publicação de protocolo '.$protocolo]);
        $publicacao->update(['situacaoID' => 3, 'rejeitadaDescricao' => null]);
        return redirect()->to(Session::get('urlVoltar'))->with('sucesso', 'Publicação Aceita!');
    }


    //Função para apagar uma publicação
    public function apagar(Request $request){

        $protocolo = $request->protocolo;

        $publicacaoApagar  = Publicacao::orderBy('dataEnvio')->where('protocoloCompleto', '=', $protocolo)->first();
        $publicacao = Publicacao::orderBy('protocoloAno', 'desc');

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                $publicacao->where('protocoloCompleto', '=', $protocolo);
            }else{
                return redirect()->back()->with('erro', 'Publicação não encontrada!');
            }
        }else{
            return redirect()->back()->with('erro', 'Publicação não encontrada!');
        }
        DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Apagou uma Publicação de protocolo '.$protocolo]);

        // verifica se existe o arquivo e o deleta;

        //@mudar

        if(file_exists(storage_path("app/".$publicacaoApagar->protocoloAno."/".$request->protocolo))){
            Storage::deleteDirectory($publicacaoApagar->protocoloAno."/".$request->protocolo);
        }

        // if(file_exists(storage_path("app/".$request->protocolo))){
        //     Storage::deleteDirectory($request->protocolo);
        // }
        DB::table('publicacaoarquivo')->where('protocoloCompleto', '=', $request->protocolo)->delete();
        $publicacao->update(['situacaoID' => 2, 'usuarioIDApagou' => Auth::user()->id, 'dataApagada' => date('Y-m-d H:i:s')]);

        return redirect()->back()->with('sucesso', 'Publicação Apagada!');

    }

    // função para publicar
    public function publicar(Request $request){
        $protocolo = $request->protocolo;
        $publicacao = Publicacao::orderBy('protocoloAno', 'desc');

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                $publicacao->where('protocoloCompleto', '=', $protocolo);
            }else{
                return redirect()->back()->with('erro', 'Publicação não encontrada!');
            }
        }else{
            return redirect()->back()->with('erro', 'Publicação não encontrada!');
        }
        DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Publicou uma Publicação de protocolo '.$protocolo]);

        $publicacao->update(['situacaoID' => 1]);

        return redirect()->back()->with('sucesso', 'Publicação Publicada!');
    }


    // função para publicar
    public function Rejeitar(Request $request){

        $protocolo = $request->protocolo;
        $publicacao = Publicacao::orderBy('protocoloAno', 'desc');

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                $publicacao->where('protocoloCompleto', '=', $protocolo);
            }else{
                return redirect()->back()->with(['erro' => 'Publicação não encontrada!']);
            }
        }else{
            return redirect()->back()->with(['erro' => 'Publicação não encontrada!']);
        }

        if(strlen($request->descricao) >= 255){
            return redirect()->back()->with(['erro' => 'Tamanho da descrição excedida!']);
        }
        DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Rejeitou uma Publicação de protocolo '.$protocolo]);

        $publicacao->update(['situacaoID' => 5, 'rejeitadaDescricao' => $request->descricao]);

        return redirect()->to(Session::get('urlVoltar'))->with('sucesso', 'Publicação Rejeitada!');
    }


    public function gerarComprovante($protocolo){

        if(!Gate::allows('faturas', Auth::user())){

            if($protocolo != null){
                if(strlen($protocolo) > 7){
                    $protocoloCompleto = $protocolo;
                }else{
                    $protocoloCompleto = null;
                }
            }

            // Verifica se essa publicação foi apagada

            $usuarioIDApagou = Publicacao::orderBy('protocoloAno', 'desc');
            $usuarioIDApagou->where('protocoloCompleto', '=', $protocoloCompleto);
            $usuarioIDApagou = $usuarioIDApagou->first();


            //verifica se a publicação é nula!
            //se não, verifica se o usuario é comum e esta tentando entrar com protocolo de uma publicação que não é dele

            if($usuarioIDApagou != null){
                if(!( Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()) ) && Auth::user()->orgaoID != $usuarioIDApagou->orgaoID){
                    return redirect('/home')->with('erro', 'Você não tem permissão!');
                  }
                // Busca todos os dados da visualização

                $publicacao = Publicacao::orderBy('protocoloAno', 'desc')->orderBy('protocolo', 'desc');

                if($usuarioIDApagou->usuarioIDApagou != null){
                    $publicacao->join('users as apagado', 'apagado.id', 'publicacao.usuarioIDApagou');
                }

                $publicacao->join('users as criado', 'criado.id', 'publicacao.usuarioID');
                $publicacao->join('diariodata', 'diariodata.diarioDataID', 'publicacao.diarioDataID');
                $publicacao->join('situacao', 'situacao.situacaoID', 'publicacao.situacaoID');
                $publicacao->join('caderno', 'caderno.cadernoID', 'publicacao.cadernoID');
                $publicacao->join('tipodocumento', 'tipodocumento.tipoID', 'publicacao.tipoID');
                $publicacao->join('orgaorequisitante', 'orgaorequisitante.orgaoID', 'criado.orgaoID');
                $publicacao->where('protocoloCompleto', '=', $protocoloCompleto);
                if($usuarioIDApagou->usuarioIDApagou != null){
                    $publicacao->select('publicacao.*', 'caderno.cadernoNome', 'tipodocumento.tipoDocumento', 'diariodata.*', 'situacao.*', 'criado.name as nomeUsuarioCriado', 'apagado.name as nomeUsuarioApagado', 'orgaorequisitante.orgaoNome');
                }else{
                    $publicacao->select('publicacao.*', 'caderno.cadernoNome', 'tipodocumento.tipoDocumento', 'diariodata.*', 'situacao.*', 'criado.name as nomeUsuarioCriado', 'orgaorequisitante.orgaoNome');
                }
                $publicacao = $publicacao->first();

                 // foto do cabeçalho do comprovante
                $path = storage_path("app/"."top.jpg");

                $pdf = BPDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

                $pdf->setPaper('a4', 'portrait')->loadView('publicacao.comprovante', ['publicacao' => $publicacao, 'path' => $path]);

                return $pdf->stream('comprovante.pdf');

            }else{
                return redirect('/home')->with('erro', 'Não existe publicação com esse protocolo!');
            }

        }else{
            return redirect('/home')->with('erro', 'Você não tem permissão!');
        }
    }

}
