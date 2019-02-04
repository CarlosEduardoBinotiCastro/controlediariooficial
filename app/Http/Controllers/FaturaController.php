<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\DiarioData;
use DateTime;
use Illuminate\Support\Collection;
use App\Caderno;
use App\TipoDocumento;
use App\SubCategoria;
use App\OrgaoRequisitante;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Fatura;
use App\Situacao;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpWord\Style\Line;
use TPDF;
use COM;
use BPDF;



class FaturaController extends Controller
{
    //

    private $paginacao = 20;
    public $fileOriginal = "";
    public $fileFormatado = "";
    public $fileVisualizado = "";
    public $diretorio = "";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function carregarConfiguracao(){
        if(Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())){
            if(Gate::allows('cadernoFatura', Auth::user())){
                $confFatura = DB::table('configuracaofatura')->first();
                $cadernos = Caderno::orderBy('cadernoNome')->get();
                return view('fatura.configuracao', ['config' => $confFatura, 'cadernos' => $cadernos]);
            }else{
                return redirect('home');
            }
        }else{
            return redirect('home');
        }
    }

    public function salvarConfiguracao(Request $request){
        $table = DB::table('configuracaofatura')->orderBy('configID');

        if($request->valorColuna != null && $request->largura != null){


            $request->valorColuna = str_replace(',','.',$request->valorColuna);
            $request->largura = str_replace(',','.',$request->largura);

            if(is_numeric($request->valorColuna) && is_numeric($request->largura)){
                $table->where('configID', '=', $request->configID)->update(['largura' => $request->largura, 'valorColuna' => $request->valorColuna, 'cadernoID' => $request->cadernoID]);

                DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Salvou configuração de fatura ']);

                return redirect('home')->with('sucesso', 'Configurações Salvas');
            }else{
                if(!is_numeric($request->valorColuna)){
                    return redirect()->back()->with('erro', 'Valor não numérico em valor da coluna');
                }
                if(!is_numeric($request->largura)){
                    return redirect()->back()->with('erro', 'Valor não numérico em largura');
                }
            }
        }else{
            return redirect()->back()->with('erro', 'Valores Em Brano');
        }
    }

    public function cadastrar(){

        if(Gate::allows('administrador', Auth::user())|| Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){
            if(Gate::allows('cadernoFatura', Auth::user())){
                $horaEnvio = Auth::user()->horaEnvio;

                //Copiar $diariosDatas = DiarioData::orderBy('diarioData')->where('diarioData', '>', date('Y-m-d'))->get();
                $confFatura = DB::table('configuracaofatura')->first();

                if($confFatura->cadernoID != null){

                    $documentos = TipoDocumento::orderBy('tipoDocumento');
                    $documentos->join('cadernotipodocumento', 'cadernotipodocumento.tipoID', 'tipodocumento.tipoID');
                    $documentos->join('caderno', 'caderno.cadernoID', 'cadernotipodocumento.cadernoID');
                    $documentos->where('caderno.cadernoID', '=', $confFatura->cadernoID);
                    $documentos = $documentos->get();

                    $subcategorias = SubCategoria::orderBy('subcategoriaNome');
                    foreach ($documentos as $documento) {
                        $subcategorias->orWhere('tipoID', '=', $documento->tipoID);
                    }
                    $subcategorias = $subcategorias->get();

                }else{
                    return redirect('home')->with('erro', 'Nenhum caderno vinculado com faturas! Vincule nas configurações da fatura.');
                }

                return view('fatura.cadastrar', [ 'config' => $confFatura, 'documentos' => $documentos, 'subcategorias' => $subcategorias]);

            }else{
                return redirect('home');
            }
        }else{
            return redirect('home');
        }

    }

    public function formatar(Request $request){

        // Toda Vez que formata ele verifica se existem arquivos antigos na pasta temp, se existir os deleta.
        $path = storage_path("app/public/temp/");
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                $filelastmodified = filemtime($path . $file);
                //24 hours in a day * 3600 seconds per hour
                if((time() - $filelastmodified) > 1*3600)
                {
                    File::delete($path . $file);
                }
            }
            closedir($handle);
        }
        // fim do delete


        switch ($this->validar($request)){

            case 1:
                return redirect()->back()->with('erro', "Arquivo na extensão incorreta!")->withInput();
            break;

            case 2:
                return redirect()->back()->with('erro', "Arquivo com tamanho maior que 30MB!")->withInput();
            break;

            // case 3:
            //     return redirect()->back()->with('erro', "Nome do requisitante não pode possuir caracteres especiais!")->withInput();
            // break;

            // case 4:
            //     return redirect()->back()->with('erro', "Data de envio ultrapassada!")->withInput();
            // break;

            case 5:
                return redirect()->back()->with('erro', "Fatura Já enviada Para o Sistema!")->withInput();
            break;

            case 6:
                return redirect()->back()->with('erro', "Arquivo temporário não existe mais!")->withInput();
            break;

            case 7:
                return redirect()->back()->with('erro', "Observação com tamanho excedido!")->withInput();
            break;

            case 8:
                return redirect()->back()->with('erro', "Empresa com tamanho excedido!")->withInput();
            break;

            case 9:
                return redirect()->back()->with('erro', "Requisitante com tamanho excedido!")->withInput();
            break;

            case 10:
                return redirect('home')->with('erro', "Parece que algumas informações vieram desformatadas, verifique se o navegador esta com javascript funcionando (aperte f12), ou tente mudar de navegador!")->withInput();
            break;

            default:

                // carrega configurações da fatura
                $faturaConfig = DB::table('configuracaofatura')->get();

                $fontSize = 10;      // Muda o Tamnho da Letra       |   MUDAR CASO MUDE ALGUM DIA
                $fontFamily = "Times";  //   A familia da fonte.    |   MUDAR CASO MUDE ALGUM DIA

                // cria o nome dos arquivos temporarios da fatura
                $fileName = $request->cpfCnpj.'-'.date('Y-m-d-H-i-s')."_temp".'.'.pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION);
                $fileNameFormat = $request->cpfCnpj.'-'.date('Y-m-d-H-i-s')."_format"."_temp".'.'.pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION);

                $request->arquivo->storeAs("public/temp/", $fileName);


                $arquivo = \PhpOffice\PhpWord\IOFactory::load(storage_path("app/public/temp/". $fileName));




                // "PhpOffice\PhpWord\Element\TextRun" textrun class
                // "PhpOffice\PhpWord\Element\Text" text class


                // CONFIGURAÇÕES DO TEXTO DO DOCUMENTO

                // ALTERA INFORMAÇÕES PERTINENTE AO TEXTO, TAIS COMO TAMANHO DE LETRA, ALINHAMENTO E TIPO DE FONTE
                $contaParagrafos = 0;
                $contaTexto = 0;
                $passouTitulo = false;
                // dd($arquivo);
                try {

                    foreach($arquivo->getSections()[0]->getElements() as $txtRunOuTxt){

                        // Verifica se é o titulo, 1 pragrafo geralmente é o titulo, e centraliza o titulo

                        if(!(get_class($txtRunOuTxt) == "PhpOffice\PhpWord\Element\TextBreak")){

                            if($contaTexto == 0){

                                $txtRunOuTxt->fontStyle->bold = true;
                                $txtRunOuTxt->paragraphStyle->alignment = "center";
                            }else{
                                // $txtRunOuTxt->paragraphStyle->spacing = 0;
                                    $txtRunOuTxt->paragraphStyle->setSpaceAfter(0);
                                    $txtRunOuTxt->paragraphStyle->setSpaceBefore(0);
                                    $txtRunOuTxt->paragraphStyle->alignment = "both";

                                // dd($txtRunOuTxt);
                            }



                        if(get_class($txtRunOuTxt) == "PhpOffice\PhpWord\Element\TextRun"){
                            foreach ($txtRunOuTxt->getElements() as $txt) {


                                $txt->fontStyle->name = $fontFamily;
                                $txt->fontStyle->size = $fontSize;

                            }
                            $contaTexto += 1;
                        }else{

                            if($txtRunOuTxt->getText() != null){
                                $txtRunOuTxt->fontStyle->name = $fontFamily;
                                $txtRunOuTxt->fontStyle->size = $fontSize;
                                $contaTexto += 1;
                            }else{

                                // elimina os espaços em branco dos paragrafos, menos o espaço entre o titulo e o texto.
                                if(!$passouTitulo){

                                    // verifica se ja passou do titulo

                                    if($contaTexto >= 1 ){
                                        $passouTitulo = true;
                                    }else{
                                        $removeLinha = $arquivo->getSections()[0]->getElements();
                                        unset($removeLinha[$contaParagrafos]);
                                        $arquivo->getSections()[0]->elements = $removeLinha;
                                    }
                                }else{
                                    $removeLinha = $arquivo->getSections()[0]->getElements();
                                    unset($removeLinha[$contaParagrafos]);
                                    $arquivo->getSections()[0]->elements = $removeLinha;
                                }
                            }


                        }

                        $contaParagrafos += 1;
                    }else{

                        $removeLinha = $arquivo->getSections()[0]->getElements();
                        unset($removeLinha[$contaParagrafos]);
                        $arquivo->getSections()[0]->elements = $removeLinha;
                        $contaParagrafos += 1;
                    }

                }

                    //ALTERA AS INFORMAÇÕES PERTINENTE A PAGINA, LARGURA DE MARGEM E TAMANHO DE PAPEL

                $arquivo->getSections()[0]->getStyle()->setMarginBottom("0");
                $arquivo->getSections()[0]->getStyle()->setMarginLeft("0");
                $arquivo->getSections()[0]->getStyle()->setMarginTop("0");
                $arquivo->getSections()[0]->getStyle()->setMarginRight("0");
                $arquivo->getSections()[0]->getStyle()->setColsNum(0);
                $arquivo->getSections()[0]->getStyle()->setColsSpace(0);


                $arquivo->getSections()[0]->getStyle()->paper->width = intval(567*$faturaConfig[0]->largura);  //dinamico em twip
                $arquivo->getSections()[0]->getStyle()->setPageSizeW((string)intval(567*$faturaConfig[0]->largura)); //dinamico em twip
                $arquivo->getSections()[0]->getStyle()->paper->sizes["A4"][0] = $faturaConfig[0]->largura*10; //dinamico em mm

                // $arquivo->getSections()[0]->getStyle()->paper->height = null;
                // $arquivo->getSections()[0]->getStyle()->setPageSizeH(null);

                // FIM DAS CONFIGURAÇÕES


                // cria e salva o arquivo formatado no diretorio temporario
                $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($arquivo, "Word2007");
                $objectWriter->save(storage_path("app/public/temp/".$fileNameFormat));


                // cria um array com todas as informações da fatura para carrega-la na pagina de visualização
                $filtro = $request->all();
                unset($filtro['arquivo']);

                $arquivosArray = array('arquivoOriginal' => $fileName, 'arquivoFormatado' => $fileNameFormat);

                $filtro += $arquivosArray;
                // fim da criação do array da fatura


                // carrega informações para carregar a pagina de visualização

                $documento = TipoDocumento::orderBy('tipoDocumento');
                $documento->where('tipoID', '=', intval($filtro['tipoID']));
                $documento = $documento->first();


                if($filtro['subcategoriaID'] != "NaoPossui"){
                    $subcategoria = SubCategoria::orderBy('subcategoriaNome');
                    $subcategoria = $subcategoria->where('subcategoriaID', '=', intval($filtro['subcategoriaID']))->first();
                }else{
                    $subcategoria = null;
                }

                // $diariosData = DiarioData::orderBy('diarioData', 'desc')->where('diarioData', '>', date('Y-m-d'))->where('diarioDataID', '=', $filtro['diarioDataID'])->first();
                // $data = new DateTime($diariosData->diarioData);
                // $data = $data->format('d/m/Y');

                // if($subcategoria != null){
                //     $infoArray = array('subcategoriaNome' => $subcategoria->subcategoriaNome, 'tipoDocumento' => $documento->tipoDocumento, 'diario' => 'N° '.$diariosData->numeroDiario.'   Data: '.$data);
                // }else{
                //     $infoArray = array('subcategoriaNome' => "Não Possui", 'tipoDocumento' => $documento->tipoDocumento, 'diario' => 'N° '.$diariosData->numeroDiario.'   Data: '.$data);
                // }

                if($subcategoria != null){
                    $infoArray = array('subcategoriaNome' => $subcategoria->subcategoriaNome, 'tipoDocumento' => $documento->tipoDocumento);
                }else{
                    $infoArray = array('subcategoriaNome' => "Não Possui", 'tipoDocumento' => $documento->tipoDocumento);
                }

                $filtro += $infoArray;

                // fim das informações


                // dia limite

                    // $diaDiarioDate = new DateTime($diariosData->diarioData);
                    // $verificaDiaUtil = false;
                    // $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaDiarioDate->format('Y-m-d'))));
                    // do{
                    //     $finalDeSemana = date('N', strtotime($diaUtil));
                    //     if(!($finalDeSemana == '7' || $finalDeSemana == '6')){
                    //         if( !(DB::table('diasnaouteis')->where('diaNaoUtilData', '=', $diaUtil)->count()) ) {
                    //             $verificaDiaUtil = true;
                    //         }else{
                    //         }
                    //     }
                    //     if($verificaDiaUtil == false){
                    //         $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaUtil)));
                    //     }
                    // }while($verificaDiaUtil == false);

                // fim do limite

                // calculo de centimetragem
                $info = $this->centimetragem($fileNameFormat, $request->cpfCnpj);
                $centimetragem = $info['centimetragem'];
                $fileVisualizacao = $info['file'];

                // necessario criar um json para se entendido pelo javascript
                // return view('fatura.formatada', ['formatada' => $arquivo, 'faturaConfig' => $faturaConfig, 'fatura' => $filtro, 'dataLimite' => $diaUtil, 'centimetragem' => $centimetragem, 'arquivoVisualizacao' => $fileVisualizacao]);
                return view('fatura.formatada', ['formatada' => $arquivo, 'faturaConfig' => $faturaConfig, 'fatura' => $filtro, 'centimetragem' => $centimetragem, 'arquivoVisualizacao' => $fileVisualizacao]);

                } catch (\Exception $e) {

                    return redirect()->back()->with('erro', 'Falha na formatação do arquivo, verifique se está usando o template e tente novamente. Erro: '.$e->getMessage());

                }

            break;
        }
    }


    public function downloadTemp($arquivoFormatadoTemp){
        if(file_exists(storage_path("app/public/temp/".$arquivoFormatadoTemp))){

            return Response::download(storage_path("app/public/temp/".$arquivoFormatadoTemp), 'visualizacao.docx');
        }else{
            return redirect('home')->with('erro', 'Arquivo não Encontrado!');
        }
    }

    public function salvar(Request $request){



        switch ($this->validar($request)){

            case 1:
                return redirect('home')->with('erro', "Arquivo na extensão incorreta!");
            break;

            case 2:
                return redirect('home')->with('erro', "Arquivo com tamanho maior que 30MB!");
            break;

            case 3:
                 return redirect('home')->with('erro', "Valores da fatura não são consistentes!");
            break;

            // case 4:
            //     return redirect('home')->with('erro', "Data de envio ultrapassada!");
            // break;

            case 5:
                return redirect('home')->with('erro', "Fatura Já enviada Para o Sistema!");
            break;

            case 6:
                return redirect('home')->with('erro', "Arquivo temporário não existe mais!");
            break;

            case 7:
                return redirect('home')->with('erro', "Observação com tamanho excedido!")->withInput();
            break;

            case 8:
                return redirect('home')->with('erro', "Empresa com tamanho excedido!")->withInput();
            break;

            case 9:
                return redirect('home')->with('erro', "Requisitante com tamanho excedido!")->withInput();
            break;

            case 10:
                return redirect('home')->with('erro', "Parece que algumas informações vieram desformatadas, verifique se o navegador esta com javascript funcionando (aperte f12), ou tente mudar de navegador!")->withInput();
            break;


            default:

                $this->fileOriginal = str_replace('_temp','',$request->arquivoOriginal);
                $this->fileFormatado = str_replace('_temp','',$request->arquivoFormatado);
                $this->fileVisualizado = str_replace('_temp','',$request->arquivoVisualizado);

                try {

                    $copiaOriginal = File::move(storage_path("app/public/temp/".$request->arquivoOriginal),storage_path("app/".$this->fileOriginal));
                    $copiaFormatado = File::move(storage_path("app/public/temp/".$request->arquivoFormatado),storage_path("app/".$this->fileFormatado));
                    $copiaVisualizado = File::move(storage_path("app/public/temp/".$request->arquivoVisualizado),storage_path("app/".$this->fileVisualizado));

                    if(DB::table('fatura')->where('protocoloAno', '=', date('Y'))->count() ){
                        $protocolo = DB::table('fatura')->where('protocoloAno', '=', date('Y'))->max('protocolo') + 1;
                    }else{
                        $protocolo = 0;
                    }

                    DB::beginTransaction();
                    $this->verificaProtocolo($protocolo, $request);
                    return redirect('/fatura/listar')->with('sucesso', 'Fatura Enviada com Sucesso');
                } catch (\Exception $e) {

                    if(file_exists(storage_path("app/".$this->fileOriginal))){
                        Storage::delete([$this->fileOriginal]);
                    }
                    if(file_exists(storage_path("app/".$this->fileFormatado))){
                        Storage::delete([$this->fileFormatado]);
                    }
                    if(file_exists(storage_path("app/".$this->fileVisualizado))){
                        Storage::delete([$this->fileVisualizado]);
                    }

                    if(file_exists(storage_path("app/".$this->diretorio))){
                        Storage::delete($this->diretorio);
                    }

                    DB::rollBack();

                    return redirect('home')->with('erro', "Um erro durante a operação ocorreu!".$e->getMessage());
                }

            break;

        }

    }

    public function verificaProtocolo($protocolo, $request){
        if(DB::table('fatura')->where('protocoloAno', '=', date('Y'))->where('protocolo', '=', $protocolo)->count()){
            $protocolo++;
            $this->verificaProtocolo($protocolo, $request);
        }else {
            if($request->subcategoriaID == "NaoPossui"){
                $request->subcategoriaID = null;
            }
            DB::table('fatura')->insert(['situacaoID' => 4, 'subcategoriaID' => $request->subcategoriaID, 'tipoID' => $request->tipoID, 'diarioDataID' => $request->diarioDataID, 'dataEnvioFatura' => date('Y-m-d H:i:s'), 'arquivoOriginal' => $this->fileOriginal, 'arquivoFormatado' => $this->fileFormatado, 'arquivoVisualizacao' => $this->fileVisualizado, 'largura' => $request->largura, 'centimetragem' => $request->centimetragem, 'valorColuna' => $request->valorColuna, 'valor' => $request->valor, 'observacao' => $request->observacao, 'cpfCnpj' => $request->cpfCnpj, 'empresa' => $request->empresa, 'requisitante' => $request->requisitante, 'protocolo' => $protocolo, 'protocoloAno' => date('Y'), 'protocoloCompleto' => $protocolo.date('Y').'FAT', 'usuarioID' => Auth::user()->id, 'telefoneFixo' => $request->telefoneFixo, 'telefoneCelular' => $request->telefoneCelular, 'email' => $request->email]);

            //@mudar
            // mudança no storage dos arquivos
            $this->diretorio = date('Y').'/'.$protocolo.date('Y').'FAT';

            // $this->diretorio = $protocolo.date('Y').'FAT';

            $arquivo = \PhpOffice\PhpWord\IOFactory::load(storage_path("app/".$this->fileFormatado));
            $arquivo->getSections()[0]->addText('Protocolo: '.$protocolo.date('Y').'FAT', array('bold'=>true, 'size'=>10, 'name'=>'Times'));

            $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($arquivo, "Word2007");
            $objectWriter->save(storage_path("app/".$this->fileFormatado));

            File::makeDirectory(storage_path("app/".$this->diretorio));

            $copiaOriginal = File::move(storage_path("app/".$this->fileOriginal),storage_path("app/".$this->diretorio."/".$this->fileOriginal));
            $copiaFormatado = File::move(storage_path("app/".$this->fileFormatado),storage_path("app/".$this->diretorio."/".$this->fileFormatado));
            $copiaVisualizado = File::move(storage_path("app/".$this->fileVisualizado),storage_path("app/".$this->diretorio."/".$this->fileVisualizado));
            DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Cadastrou uma Fatura de protocolo '.$protocolo.date('Y').'FAT']);

            DB::commit();
        }
    }

    public function validar($request){

        $doc = str_split($request->cpfCnpj);
        $pattern = '.';
        if(in_array($pattern, $doc)){
            return 10;
        }

        if(strlen($request->observacao) > 255){
            return 7;
        }

        if(strlen($request->empresa) > 200){
            return 8;
        }

        if(strlen($request->requisitante) > 200){
            return 9;
        }

        if(isset($request->arquivo)){
            if(pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION) == "docx"){

            }else{
                return 1;
            }

            $tamanhoArquivo = (filesize($request->arquivo) / 1024)/1024;
            if($tamanhoArquivo >= 30){
                return 2;
            }
        }


        $valorColuna =  DB::table('configuracaofatura')->get();
        if ($valorColuna[0]->valorColuna != $request->valorColuna)
        {
             return 3;
        }else{
            $centimetragem = Session::get('centimetragem');
            $valorFatura = $valorColuna[0]->valorColuna*number_format($centimetragem, 2, ".", "");
            $valorFatura = number_format($valorFatura, 2, ".", "");
            // dd($valorFatura  . '    /    ' . $request->valor);
            if($request->valor != $valorFatura){
                return 3;
            }
        }


        // Verificação do lado do servidor sobre a data do envio par o diario !

        // $diarioTemp = DiarioData::orderBy('diarioDataID')->where('diarioDataID', '=', $request->diarioDataID)->first();

        // $diaDiarioDate = new DateTime($diarioTemp->diarioData);
        // $verificaDiaUtil = false;
        // $diaUtil = date('Y-m-d', strtotime($diaDiarioDate->format('Y-m-d')));

        // do{
        //     $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaUtil)));
        //     $finalDeSemana = date('N', strtotime($diaUtil));
        //     if(!($finalDeSemana == '7' || $finalDeSemana == '6')){
        //         if( !(DB::table('diasnaouteis')->where('diaNaoUtilData', '=', $diaUtil)->count()) ) {
        //             $verificaDiaUtil = true;
        //         }else{
        //         }
        //     }

        // }while($verificaDiaUtil == false);

        // if($diaUtil <= date('Y-m-d')){
        //     if($diaUtil == date('Y-m-d')){
        //         if(Auth::user()->horaEnvio >= date('H:i:s')){

        //         }else{
        //             return 4;
        //         }
        //     }else{
        //         return 4;
        //     }
        // }

        // fim da verificação do lado do servidor


        if(isset($request->arquivoOriginal)){
            $this->fileOriginal = str_replace('_temp','',$request->arquivoOriginal);
            if(file_exists(storage_path("app/".$this->fileOriginal))){
                return 5;
            }

            if(file_exists(storage_path("app/public/temp".$request->arquivoOriginal))){
                return 6;
            }
        }

        return null;

    }

    function validaCNPJ($cnpj = null) {

        // Verifica se um número foi informado
        if(empty($cnpj)) {
            return false;
        }

        // Elimina possivel mascara
        $cnpj = preg_replace("/[^0-9]/", "", $cnpj);
        $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 14

        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cnpj == '00000000000000' ||
            $cnpj == '11111111111111' ||
            $cnpj == '22222222222222' ||
            $cnpj == '33333333333333' ||
            $cnpj == '44444444444444' ||
            $cnpj == '55555555555555' ||
            $cnpj == '66666666666666' ||
            $cnpj == '77777777777777' ||
            $cnpj == '88888888888888' ||
            $cnpj == '99999999999999') {
            return false;

         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {

            $j = 5;
            $k = 6;
            $soma1 = "";
            $soma2 = "";

            for ($i = 0; $i < 13; $i++) {

                $j = $j == 1 ? 9 : $j;
                $k = $k == 1 ? 9 : $k;

                $soma2 += ($cnpj{$i} * $k);

                if ($i < 12) {
                    $soma1 += ($cnpj{$i} * $j);
                }

                $k--;
                $j--;

            }

            $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
            $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;

            return (($cnpj{12} == $digito1) and ($cnpj{13} == $digito2));

        }
    }

    function validaCPF($cpf = null) {

        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }

        // Elimina possivel mascara
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }


    public function caixaDeTexto(){

        // Métrica para conversão das medidas na caixa de texto
        // 28,35  = 1 cm;

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection(array('marginTop' => 0,
                                              'marginLeft' => 0,
                                              'marginRight' => 0,
                                              'marginBottom' => 0));
        $textbox = $section->addTextBox(
            array(
                'innerMarginTop' => 0,
                'innerMarginLeft' => 0,
                'innerMarginRight' => 0,
                'innerMarginBottom' => 0,
                'alignment'   => "center",
                'width'       => 439.42, // duas casas após a virgula | largura da caixa de texto
                'height'      => 123.32, // duas casas após a virgula | Comprimento da caixa de texto
                'borderSize'  => 'none',
                'borderColor' => 'white',
            )
        );

        // $textbox->getStyle()->setVTextAnchor('middle');


        $textbox->addText('meu titulo', array('alignment' => "center"));
        $textbox->addText('texto bolado do jurandir kkkk, jurandir é cara louco, não tem como lidar com ele. Muito bacana mesmo, noosssa tatata!', array('alignment' => "justify"));
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");

        $objWriter->save(storage_path('app/'.'dummy.docx'));
        dd($objWriter);
    }

    public function listar($cpfCnpj = null, $protocolo = null, $diario = null, $situacao = null, $empresa = null,  $subcategoria = null){

        if(Gate::allows('administrador', Auth::user()) || Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){

            if(Gate::allows('cadernoFatura', Auth::user())){

            $situacoes = Situacao::orderBy('situacaoNome')->get();
            $subcategorias = DB::table('subcategoria')->orderBy('subcategoriaNome')->get();

            $faturas = Fatura::orderBy('dataEnvioFatura', 'desc');
            $faturas->leftJoin('diariodata', 'diariodata.diariodataID', 'fatura.diariodataID');

            $faturas->leftJoin('subcategoria', 'subcategoria.subcategoriaID', 'fatura.subcategoriaID');
            $faturas->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');

            // Filtros

                if($empresa != null && $empresa != "tudo"){
                    $arrayPalavras = explode(' ', $empresa);
                    foreach ($arrayPalavras as $palavra) {
                        $faturas->where('empresa', 'like', '%' . $palavra . '%');
                    }
                }

                if($protocolo != null && $protocolo != "tudo"){
                    $faturas->where('protocoloCompleto', '=', $protocolo);
                }

                if($cpfCnpj != null && $cpfCnpj != "tudo"){
                    $faturas->where('cpfCnpj', '=', $cpfCnpj);
                }

                if($diario != null && $diario != "tudo"){
                    $faturas->where('diariodata.diarioData', '=', $diario);
                }



                if(Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){
                    $faturas->where('situacao.situacaoNome', '!=', "Apagada");

                    if($situacao != null && $situacao != "tudo"){
                        if($situacao != "Apagada"){
                            $faturas->where('situacao.situacaoNome', '=', $situacao);
                        }
                    }

                }else{
                    if($situacao != null && $situacao != "tudo"){
                        $faturas->where('situacao.situacaoNome', '=', $situacao);
                    }
                }

                if($subcategoria != null && $subcategoria != "tudo"){

                    if($subcategoria == "NaoPossui"){
                        $faturas->where('fatura.subcategoriaID', '=', null);
                    }else{
                        $faturas->where('fatura.subcategoriaID', '=', $subcategoria);
                    }

                }

            // Fim Filtros

            $faturas->select('fatura.*', 'diariodata.numeroDiario', 'diariodata.diarioData', 'situacao.situacaoNome', 'subcategoria.subcategoriaNome');
            $faturas = $faturas->paginate($this->paginacao);


            // Verifica se a fatura foi Aceita e carrega as datas
            $faturasAceita = Fatura::orderBy('dataEnvioFatura', 'desc');
            $faturasAceita->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');

            if($faturasAceita->where('situacao.situacaoNome', '=', 'Aceita')->count() && ($situacao == "tudo" || $situacao == "Aceita" || $situacao == null)){

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
                return view('fatura.listar', [ "diarioDatas" => json_encode($diariosDatasLimites), 'faturas' => $faturas, 'subcategorias' => $subcategorias, 'situacoes' => $situacoes]);
            }

            // fim da verificação

            return view('fatura.listar', ["diarioDatas" => json_encode("vazio"), 'faturas' => $faturas, 'subcategorias' => $subcategorias, 'situacoes' => $situacoes]);

            }else{
                return redirect('home');
            }

        }else{

            return redirect('home');

        }
    }


    public function listarFiltro(Request $request){

        if($request->cpfCnpj != null){
            $cpfCnpj = $request->cpfCnpj;
        }else{
            $cpfCnpj = "tudo";
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

        if($request->situacao == "tudo" ){
            $situacao = "tudo";
        }else{
            $situacao = $request->situacao;
        }

        if($request->empresa != null){
            $empresa = $request->empresa;
        }else{
            $empresa = "tudo";
        }

        if($request->subcategoria != null){
            $subcategoria = $request->subcategoria;
        }else{
            $subcategoria = "tudo";
        }

        if(($diario == "tudo") && ($cpfCnpj == "tudo") && ($protocolo == "tudo") && ($situacao == "tudo") && ($empresa == "tudo") && ($subcategoria == "tudo")){
            return redirect('fatura/listar');
        }else{
            return redirect()->route('listarFaturas', ['cpfCnpj' => $cpfCnpj, 'protocolo' => $protocolo, 'diario' => $diario, 'situacao' => $situacao, 'empresa' => $empresa, 'subcategoria' => $subcategoria]);
        }

    }

    public function ver($protocolo){


        if(Gate::allows('administrador', Auth::user()) || Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){

            if(Gate::allows('cadernoFatura', Auth::user())){

            $fatura = Fatura::orderBy('diariodata.diarioData', 'desc');
            $fatura->leftJoin('diariodata', 'diariodata.diariodataID', 'fatura.diariodataID');
            $fatura->leftJoin('users as usuariopub', 'usuariopub.id', 'fatura.usuarioIDPublicou');
            $fatura->leftJoin('users as usuariodel', 'usuariodel.id', 'fatura.usuarioIDApagou');
            $fatura->join('users as usuario', 'usuario.id', 'fatura.usuarioID');
            $fatura->join('orgaorequisitante', 'orgaorequisitante.orgaoID', 'usuario.orgaoID');
            $fatura->leftJoin('subcategoria', 'subcategoria.subcategoriaID', 'fatura.subcategoriaID');
            $fatura->join('tipodocumento', 'tipodocumento.tipoID', 'fatura.tipoID');
            $fatura->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');
            $fatura->where('protocoloCompleto', '=', $protocolo);
            $fatura->select('fatura.*', 'diariodata.numeroDiario', 'diariodata.diarioData', 'situacao.situacaoNome', 'subcategoria.subcategoriaNome', 'tipodocumento.tipoDocumento', 'usuario.name as usuarioNome', 'usuariopub.name as usuarioNomePublicou', 'usuariodel.name as usuarioNomeApagou', 'usuario.email as emailUsuarioEmitiu', 'usuario.telefoneSetor as telefoneSetorUsuarioEmitiu', 'usuario.telefoneCelular as telefoneCelularUsuarioEmitiu', 'orgaorequisitante.orgaoNome as orgaoUsuarioEmitiu');
            $fatura = $fatura->first();

            // pega a url voltar e salva
            if(url()->previous() != url()->current()){
                Session::put('urlVoltar', url()->previous());
            }

            if($fatura == null){
                return redirect('home')->with('erro', 'Fatura Não Encontrada!');
            }else{
                if(Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){
                    if($fatura->situacaoNome == "Apagada"){
                        return redirect('home')->with('erro', 'Fatura Apagada!');
                    }
                }

                // $orgaoUsuarioEmitiu = OrgaoRequisitante::orderBy('orgaoNome')->select('orgaoNome')->where('orgaoID', '=', $fatura->orgaoUsuarioEmitiu)->first();

            }

            $faturaConfig = DB::table('configuracaofatura')->get();

            // Verifica se a fatura foi Aceita e carrega as datas
            if($fatura->situacaoNome == "Aceita"){

                $horaEnvio = Auth::user()->horaEnvio;
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

                // @mudar
                $formatada =  \PhpOffice\PhpWord\IOFactory::load(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/". $fatura->arquivoFormatado));
                return view('fatura.ver', ['diarioDatas' => json_encode($diariosDatasLimites), 'fatura' => $fatura, 'formatada' => $formatada, 'faturaConfig' => $faturaConfig]);
            }

            if($fatura->situacaoNome != "Apagada"){

                //@mudar
                $formatada =  \PhpOffice\PhpWord\IOFactory::load(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/". $fatura->arquivoFormatado));
                return view('fatura.ver', ['diarioDatas' => json_encode("vazio"),'fatura' => $fatura, 'formatada' => $formatada, 'faturaConfig' => $faturaConfig]);

            }else{

                // Verifica se a fatura foi apagada e se o usuário é o adm
                if(!(Gate::allows('administrador', Auth::user()))){
                    return redirect('home')->with('erro', ' Você não tem permissão!');
                }

                return view('fatura.ver', ['diarioDatas' => json_encode("vazio"),'fatura' => $fatura, 'faturaConfig' => $faturaConfig]);
            }

        }else{
            return redirect('home');
        }

        }else{
            return redirect('home');

        }

    }

    public function Rejeitar(Request $request){

        $protocolo = $request->protocolo;
        $fatura = Fatura::orderBy('protocoloAno', 'desc');

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                $fatura->where('protocoloCompleto', '=', $protocolo);
            }else{
                return redirect()->back()->with(['erro' => 'Fatura não encontrada!']);
            }
        }else{
            return redirect()->back()->with(['erro' => 'Fatura não encontrada!']);
        }

        if(strlen($request->descricao) >= 255){
            return redirect()->back()->with(['erro' => 'Tamanho da descrição excedida!']);
        }

        try {
            DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Rejeitou uma Fatura de protocolo '.$request->protocolo]);
            $fatura->update(['situacaoID' => 5, 'descricaoCancelamento' => $request->descricao]);
            return redirect()->to(Session::get('urlVoltar'))->with('sucesso', 'Fatura Rejeitada!');
        } catch (\Throwable $th) {
            return redirect()->back()->with(['erro' => 'Fatura não encontrada!']);
        }

    }

    public function aceitar(Request $request){
        $protocolo = $request->protocolo;
        $faturaAceitar = Fatura::orderBy('protocoloAno', 'desc')->where('protocoloCompleto', '=', $protocolo)->first();

        $fatura = Fatura::orderBy('protocoloAno', 'desc');

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                $fatura->where('protocoloCompleto', '=', $protocolo);
            }else{
                return redirect()->back()->with(['erro' => 'Fatura não encontrada!']);
            }
        }else{
            return redirect()->back()->with(['erro' => 'Fatura não encontrada!']);
        }

        if(pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION) != "pdf"){
            return redirect()->back()->with(['erro' => 'Arquivo com extensão errada, somente PDF!']);
        }

        $tamanhoArquivo = (filesize($request->arquivo) / 1024)/1024;
        if($tamanhoArquivo >= 30){
            return redirect()->back()->with(['erro' => 'Arquivo com tamanho excedido!']);
        }

        try {
            //@mudar
            DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Aceitou uma Fatura de protocolo '.$protocolo]);

            $request->arquivo->storeAs($faturaAceitar->protocoloAno."/".$protocolo."/",$protocolo."_comprovantePago.pdf");
            $fatura->update(['situacaoID' => 3, 'comprovantePago' => $request->protocolo."_comprovantePago.pdf"]);
            return redirect()->to(Session::get('urlVoltar'))->with('sucesso', 'Fatura Aceita!');

        } catch (\Exception $e) {

            if(file_exists(storage_path("app/".$fatura->protocoloAno."/".$request->protocolo."/").$protocolo."_comprovantePago.pdf")){
                File::delete(storage_path("app/".$fatura->protocoloAno."/".$request->protocolo."/").$protocolo."_comprovantePago.pdf");
            }

            return redirect()->back()->with(['erro' => 'Ocorreu um erro! erro: '.$e->getMessage()]);

        }

    }

    public function publicar(Request $request){

        $protocolo = $request->protocolo;
        $fatura = Fatura::orderBy('protocoloAno', 'desc');

        if($protocolo != null){
            if(strlen($protocolo) > 7){
                $fatura->where('protocoloCompleto', '=', $protocolo);
            }else{
                return redirect()->back()->with('erro', 'Fatura não encontrada!');
            }
        }else{
            return redirect()->back()->with('erro', 'Fatura não encontrada!');
        }

        try {

            // verifica se veio da pagina de listar ou ver

            if(isset($request->voltar)){
                DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Publicou uma Fatura de protocolo '.$protocolo]);

                $fatura->update(['situacaoID' => 1, 'diarioDataID' => $request->diarioDataID, 'usuarioIDPublicou' => Auth::user()->id]);
                return redirect()->back()->with('sucesso', 'Fatura Publicada!');
            }else{
                DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Publicou uma Fatura de protocolo '.$protocolo]);

                $fatura->update(['situacaoID' => 1, 'diarioDataID' => $request->diarioDataID, 'usuarioIDPublicou' => Auth::user()->id]);
                return redirect()->to(Session::get('urlVoltar'))->with('sucesso', 'Fatura Publicada!');
            }

        } catch (\Exception $e) {

            return redirect()->back()->with(['erro' => 'Ocorreu um erro! erro: ']);

        }
    }


    public function downloadOriginal($protocolo){

        if(Gate::allows('administrador', Auth::user())|| Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){

            if(Gate::allows('cadernoFatura', Auth::user())){

            $fatura = Fatura::orderBy('protocoloAno', 'desc');
            $fatura->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');

            $fatura->where('protocoloCompleto', '=', $protocolo);

            $fatura = $fatura->first();

            if($fatura == null){
                return redirect()->back()->with('erro', 'Protocolo não encontrado!');
            }

            if($fatura->situacaoNome == "Apagada"){
                return redirect()->back()->with('erro', 'Arquivo não encontrado!');
            }

            if ($fatura != null) {
                if( ( Gate::allows('publicador', Auth::user()) || Gate::allows('fatura', Auth::user()) ) && $fatura->situacaoID == 2 ){
                    return redirect()->back()->with('erro', 'Você não tem permissão!');
                }
            }else{
                return redirect()->back()->with('erro', 'Arquivo não encontrado!');
            }

            $arquivoExtensao = explode('.', $fatura->arquivoOriginal);
            //@mudar
            if(file_exists(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->arquivoOriginal))){
                return Response::download(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->arquivoOriginal), ''.$protocolo.'-'.'Original'.'.'.$arquivoExtensao[1]);
            }else{
                return redirect()->back()->with('erro', 'Arquivo não Encontrado!');
            }
            // if(file_exists(storage_path("app/".$fatura->protocoloCompleto."/".$fatura->arquivoOriginal))){
            //     return Response::download(storage_path("app/".$fatura->protocoloCompleto."/".$fatura->arquivoOriginal), ''.$protocolo.'-'.'Original'.'.'.$arquivoExtensao[1]);
            // }else{
            //     return redirect()->back()->with('erro', 'Arquivo não Encontrado!');
            // }

        }else{
            return redirect('home');
        }

        }else{
            return redirect('home');
        }
    }

    public function downloadFormatado($protocolo){

        if(Gate::allows('administrador', Auth::user())|| Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){

            if(Gate::allows('cadernoFatura', Auth::user())){

            $fatura = Fatura::orderBy('protocoloAno', 'desc');
            $fatura->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');

            $fatura->where('protocoloCompleto', '=', $protocolo);

            $fatura = $fatura->first();

            if($fatura == null){
                return redirect()->back()->with('erro', 'Protocolo não encontrado!');
            }

            if($fatura->situacaoNome == "Apagada"){
                return redirect()->back()->with('erro', 'Arquivo não encontrado!');
            }

            if ($fatura != null) {
                if( ( Gate::allows('publicador', Auth::user()) || Gate::allows('fatura', Auth::user()) ) && $fatura->situacaoID == 2 ){
                    return redirect()->back()->with('erro', 'Você não tem permissão!');
                }
            }else{
                return redirect()->back()->with('erro', 'Arquivo não encontrado!');
            }

            $arquivoExtensao = explode('.', $fatura->arquivoFormatado);

            //@mudar
            if(file_exists(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->arquivoFormatado))){
                return Response::download(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->arquivoFormatado), ''.$protocolo.'-'.'Formatado'.'.'.$arquivoExtensao[1]);
            }else{
                return redirect()->back()->with('erro', 'Arquivo não Encontrado!');
            }

            // if(file_exists(storage_path("app/".$fatura->protocoloCompleto."/".$fatura->arquivoFormatado))){
            //     return Response::download(storage_path("app/".$fatura->protocoloCompleto."/".$fatura->arquivoFormatado), ''.$protocolo.'-'.'Formatado'.'.'.$arquivoExtensao[1]);
            // }else{
            //     return redirect()->back()->with('erro', 'Arquivo não Encontrado!');
            // }

        }else{
            return redirect('home');
        }
        }else{
            return redirect('home');
        }
    }

    public function downloadComprovantePago($protocolo){

        if(Gate::allows('administrador', Auth::user())|| Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){

            if(Gate::allows('cadernoFatura', Auth::user())){

            $fatura = Fatura::orderBy('protocoloAno', 'desc');
            $fatura->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');

            $fatura->where('protocoloCompleto', '=', $protocolo);

            $fatura = $fatura->first();

            if($fatura == null){
                return redirect()->back()->with('erro', 'Protocolo não encontrado!');
            }

            if($fatura->situacaoNome == "Apagada"){
                return redirect()->back()->with('erro', 'Arquivo não encontrado!');
            }

            if ($fatura != null) {
                if( ( Gate::allows('publicador', Auth::user()) || Gate::allows('fatura', Auth::user()) ) && $fatura->situacaoID == 2 ){
                    return redirect()->back()->with('erro', 'Você não tem permissão!');
                }
            }else{
                return redirect()->back()->with('erro', 'Arquivo não encontrado!');
            }

            $arquivoExtensao = explode('.', $fatura->comprovantePago);

            //@mudar
            if(file_exists(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->comprovantePago))){
                return Response::download(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->comprovantePago), ''.$protocolo.'-'.'ComprovantePago'.'.'.$arquivoExtensao[1]);
            }else{
                return redirect()->back()->with('erro', 'Arquivo não Encontrado!');
            }

            // if(file_exists(storage_path("app/".$fatura->protocoloCompleto."/".$fatura->comprovantePago))){
            //     return Response::download(storage_path("app/".$fatura->protocoloCompleto."/".$fatura->comprovantePago), ''.$protocolo.'-'.'ComprovantePago'.'.'.$arquivoExtensao[1]);
            // }else{
            //     return redirect()->back()->with('erro', 'Arquivo não Encontrado!');
            // }

        }else{
            return redirect('home');
        }
        }else{
            return redirect('home');
        }
    }

    public function apagar(Request $request){

        $protocolo = $request->protocolo;

        $fatura = Fatura::orderBy('protocoloAno', 'desc');
        $fatura->where('protocoloCompleto', '=', $protocolo);
        $fatura = $fatura->first();

        $faturaApagar = Fatura::orderBy('protocoloAno', 'desc');

        if($protocolo != null){
            if(strlen($protocolo) > 7 && $fatura != null){
                $faturaApagar->where('protocoloCompleto', '=', $protocolo);
            }else{
                return redirect()->back()->with('erro', 'Fatura não encontrada!');
            }
        }else{
            return redirect()->back()->with('erro', 'Fatura não encontrada!');
        }


        try {
            //@mudar
            DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Apagou uma Fatura de protocolo '.$protocolo]);

            if(file_exists(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->arquivoOriginal))){
                Storage::delete([storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->arquivoOriginal)]);
            }
            if(file_exists(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->arquivoFormatado))){
                Storage::delete([storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->arquivoFormatado)]);
            }
            if(file_exists(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->comprovantePago))){
                Storage::delete([storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto."/".$fatura->comprovantePago)]);
            }

            File::deleteDirectory(storage_path("app/".$fatura->protocoloAno."/".$fatura->protocoloCompleto));

            // if(file_exists(storage_path("app/".$fatura->protocoloCompleto."/".$fatura->arquivoOriginal))){
            //     Storage::delete([storage_path("app/".$fatura->protocoloCompleto."/".$fatura->arquivoOriginal)]);
            // }
            // if(file_exists(storage_path("app/".$fatura->protocoloCompleto."/".$fatura->arquivoFormatado))){
            //     Storage::delete([storage_path("app/".$fatura->protocoloCompleto."/".$fatura->arquivoFormatado)]);
            // }
            // if(file_exists(storage_path("app/".$fatura->protocoloCompleto."/".$fatura->comprovantePago))){
            //     Storage::delete([storage_path("app/".$fatura->protocoloCompleto."/".$fatura->comprovantePago)]);
            // }
            // File::deleteDirectory(storage_path("app/".$fatura->protocoloCompleto));
            $faturaApagar->update(['situacaoID' => 2, 'usuarioIDApagou' => Auth::user()->id]);
            return redirect()->back()->with('sucesso', 'Fatura Apagada!');

        } catch (\Exception $e) {
            return redirect()->back()->with('erro', 'Ocorreu um erro durante o processo! Erro: '.$e->getMessage());
        }
        // verifica se existe o arquivo e o deleta;
    }

    public function carregarRelatorio($dataInicio = null, $dataFinal = null, $situacao = null){
        if(Gate::allows('administrador', Auth::user())){

            if(Gate::allows('cadernoFatura', Auth::user())){

            $situacoes = Situacao::orderBy('situacaoNome')->get();

            $faturas = Fatura::orderBy('protocolo');
            $faturas->leftJoin('situacao', 'situacao.situacaoID', 'fatura.situacaoID');
            $faturas->whereBetween('dataEnvioFatura',  [$dataInicio . ' 00:00:01', $dataFinal . ' 23:59:59']);

            $subcategorias = SubCategoria::orderBy('subcategoriaNome');
            $subcategorias->rightJoin('fatura', 'fatura.subcategoriaID', 'subcategoria.subcategoriaID');
            $subcategorias->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');
            $subcategorias->selectRaw('SUM(fatura.valor) as total');
            $subcategorias->selectRaw('COUNT(*) as quantidade, subcategoria.subcategoriaNome');
            $subcategorias->whereBetween('fatura.dataEnvioFatura',  [$dataInicio . ' 00:00:01', $dataFinal . ' 23:59:59']);
            $subcategorias->groupBy('subcategoria.subcategoriaNome');

            $valorTotal = DB::table('fatura');
            $valorTotal->selectRaw('SUM(valor) as total');
            $valorTotal->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');
            $valorTotal->whereBetween('dataEnvioFatura',  [$dataInicio . ' 00:00:01', $dataFinal . ' 23:59:59']);

            if($situacao != null && $situacao != "tudo"){

                if(preg_match('/-/', $situacao)){
                    $situacao = explode('-', $situacao);
                    $i = 0;

                    foreach($situacao as $sit){
                        if($i == 0){
                            $faturas->where('situacao.situacaoNome', '=', $sit);
                            $subcategorias->where('situacao.situacaoNome', '=', $sit);
                            $valorTotal->where('situacao.situacaoNome', '=', $sit);
                        }else{
                            $faturas->orWhere('situacao.situacaoNome', '=', $sit);
                            $subcategorias->orWhere('situacao.situacaoNome', '=', $sit);
                            $valorTotal->orwhere('situacao.situacaoNome', '=', $sit);
                        }
                        $i++;
                    }

                }else{
                    $faturas->where('situacao.situacaoNome', '=', $situacao);
                    $subcategorias->where('situacao.situacaoNome', '=', $situacao);
                    $valorTotal->where('situacao.situacaoNome', '=', $situacao);
                }

            }

            $faturas = $faturas->count();
            $subcategorias = $subcategorias->get();
            $valorTotal = $valorTotal->first();


            if($dataInicio != null && $dataFinal != null && $situacao != null){
                return view('fatura.relatorio',  ['faturas' => $faturas, 'subcategorias' => $subcategorias, 'valorTotal' => $valorTotal, 'situacoes' => $situacoes]);
            }else{
                return view('fatura.relatorio', ['situacoes' => $situacoes]);
            }

        }else{
            return redirect('home');
        }
        }else{
            return redirect('home');
        }
    }

    public function carregarRelatorioFiltro(Request $request){

        if($request->situacao != null && $request->situacao != "tudo" ){
            $situacao = $request->situacao;
        }else{
            $situacao = "tudo";
        }

        return redirect()->route('carregarRelatorio', ['dataInicio' => $request->dataInicio, 'dataFinal' => $request->dataFinal, 'situacao' => $situacao]);
    }

    public function centimetragem($file, $documento){

        // pegar o arquivo Atual para calcular centimetragem
        $formatada =  \PhpOffice\PhpWord\IOFactory::load(storage_path("app/public/temp/".$file));
        $texto = "";
        $contaTexto = 0;

        // Gerando uma string unica para a centimetragem
        foreach ($formatada->getSections()[0]->getElements() as $txtRunOuTxt) {




                if(get_class($txtRunOuTxt) == "PhpOffice\PhpWord\Element\TextRun" ){
                     if($contaTexto == 0){
                     }else{
                        //Aqui Adiciona o Texto
                         foreach ($txtRunOuTxt->getElements() as $txt) {
                            $texto = $texto.$txt->getText();
                         }
                         $texto =$texto."\n";
                     }
                }else{
                    if($txtRunOuTxt->getText() == "" || $txtRunOuTxt->getText() == " "){
                         // texto vazio de vez em quando
                    }else{
                     if($contaTexto == 0){
                         }else{
                            //Aqui Adiciona o Texto
                            $texto = $texto.$txtRunOuTxt->getText();
                            $texto = $texto."\n";

                         }
                    }
                }
                $contaTexto += 1;
            }

        // fim da geração da string

        //carrega informações de configuração da fatura
        $faturaConfig = DB::table('configuracaofatura')->get();

        // gera o pdf para calcular centimetrgem
        $pdf = new TPDF;
        $pdf::SetTitle('Centimetragem Diario Oficial');

        // todas as margens zeradas
        $pdf::SetMargins(0,0,0,false);
        $pdf::SetAutoPageBreak(FALSE, 0); // remove a margem do final e e retira a quebra de paginas

        // cria a pagina
        $pdf::AddPage();

        // cofiguração da fonte
        $pdf::SetFont('times', '', 10,'', 'false');

        //espaçamento do texto;
        $pdf::setCellHeightRatio(1.15);

        // Escreve o conteudo na celula para o calcula, primeiro parametro, largura (em milimetros), segundo, altura (em 0 ele gera dinamicamente).
        $width = ($faturaConfig[0]->largura*10);
        $pdf::Multicell($width, 0, $texto, 0, 'J', 0, 1, '', '', true);

        // função para retornar a centimetragem, como o retorno é em milimetros, apenas uma divisão por 10, resolve o problema.
        $centimetragem = $pdf::getY()/10;
        Session::put('centimetragem', $centimetragem);

        // dd($centimetragem);

        //gera o pdf na tela para visualição
        $fileName = $documento.'-'.date('Y-m-d-H-i-s').'_visualizacao_temp.pdf';
        TPDF::Output(storage_path('app/public/temp/'.$fileName), 'F');

        //retorna a centimetragem
        return array('centimetragem' => $centimetragem, 'file' => $fileName);
    }

    public function downloadVisualizacaoTemp($arquivoVisualizacao){
        if(Gate::allows('administrador', Auth::user())|| Gate::allows('faturas', Auth::user())){
            if(Gate::allows('cadernoFatura', Auth::user())){
                try {
                    return Response::download(storage_path('app/public/temp/'.$arquivoVisualizacao));

                } catch (\Exception $e) {

                    return redirect()->back()->with(['erro' => 'Ocorreu um erro! erro: '.$e->getMessage()]);

                }
            }else{
                return redirect('home');
            }
        }else{
            return redirect('home');
        }
    }

    public function downloadVisualizacao($arquivoVisualizacao, $protocolo){
        if(Gate::allows('administrador', Auth::user())|| Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){
            if(Gate::allows('cadernoFatura', Auth::user())){
                try {
                    //@mudar
                    $fatura = Fatura::orderBy('dataEnvioFatura')->where('protocoloCompleto', '=', $protocolo)->first();
                    if($fatura != null ){
                        return Response::download(storage_path("app/".$fatura->protocoloAno."/".$protocolo."/".$arquivoVisualizacao));
                    }else{
                        return redirect()->back()->with(['erro' => 'Protocolo de fatura nao existente!']);
                    }

                    // return Response::download(storage_path("app/".$protocolo."/".$arquivoVisualizacao));

                } catch (\Exception $e) {

                    return redirect()->back()->with(['erro' => 'Ocorreu um erro! erro: '.$e->getMessage()]);

                }
            }else{
                return redirect('home');
            }
        }else{
            return redirect('home');
        }
    }


    public function relatorioDetalhado($cpfCnpj = null, $protocolo = null, $dataInicial = null, $dataFinal = null, $situacao = null, $empresa = null,  $subcategoria = null){

        if(Gate::allows('administrador', Auth::user())){

            if(Gate::allows('cadernoFatura', Auth::user())){

            $situacoes = Situacao::orderBy('situacaoNome')->get();
            $subcategorias = DB::table('subcategoria')->orderBy('subcategoriaNome')->get();

            $faturas = Fatura::orderBy('dataEnvioFatura', 'desc');
            $faturas->leftJoin('diariodata', 'diariodata.diariodataID', 'fatura.diariodataID');

            $faturas->leftJoin('subcategoria', 'subcategoria.subcategoriaID', 'fatura.subcategoriaID');
            $faturas->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');

            // Filtros

                if($empresa != null && $empresa != "tudo"){
                    $arrayPalavras = explode(' ', $empresa);
                    foreach ($arrayPalavras as $palavra) {
                        $faturas->where('empresa', 'like', '%' . $palavra . '%');
                    }
                }

                if($protocolo != null && $protocolo != "tudo"){
                    $faturas->where('protocoloCompleto', '=', $protocolo);
                }

                if($cpfCnpj != null && $cpfCnpj != "tudo"){
                    $faturas->where('cpfCnpj', '=', $cpfCnpj);
                }

                // if( ($dataInicial != null && $dataInicial != "tudo") && ($dataFinal != null && $dataFinal != "tudo") ){
                //     $faturas->whereBetween('diariodata.diarioData',  [$dataInicial . ' 00:00:01', $dataFinal . ' 23:59:59']);
                // }

                if( ($dataInicial != null && $dataInicial != "tudo") && ($dataFinal != null && $dataFinal != "tudo") ){
                    $faturas->whereBetween('fatura.dataEnvioFatura',  [$dataInicial . ' 00:00:01', $dataFinal . ' 23:59:59']);
                }

                if($situacao != null && $situacao != "tudo"){
                    $faturas->where('situacao.situacaoNome', '=', $situacao);
                }

                if($subcategoria != null && $subcategoria != "tudo"){
                    if($subcategoria == "NaoPossui"){
                        $faturas->where('fatura.subcategoriaID', '=', null);
                    }else{
                        $faturas->where('fatura.subcategoriaID', '=', $subcategoria);
                    }
                }

            // Fim Filtros


            $faturas->select('fatura.*', 'diariodata.numeroDiario', 'diariodata.diarioData', 'situacao.situacaoNome', 'subcategoria.subcategoriaNome');
            $faturas = $faturas->paginate($this->paginacao);


            // Verifica se a fatura foi Aceita e carrega as datas
            $faturasAceita = Fatura::orderBy('dataEnvioFatura', 'desc');
            $faturasAceita->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');

            if($faturasAceita->where('situacao.situacaoNome', '=', 'Aceita')->count() && ($situacao == "tudo" || $situacao == "Aceita" || $situacao == null)){

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
                return view('fatura.relatoriodetalhado', [ "diarioDatas" => json_encode($diariosDatasLimites), 'faturas' => $faturas, 'subcategorias' => $subcategorias, 'situacoes' => $situacoes]);
            }

            // fim da verificação

            return view('fatura.relatoriodetalhado', ["diarioDatas" => json_encode("vazio"),'faturas' => $faturas, 'subcategorias' => $subcategorias, 'situacoes' => $situacoes]);

            }else{
                return redirect('home');
            }
        }else{
            return redirect('home');
        }
    }


    public function  relatorioDetalhadoFiltro(Request $request){

        if($request->cpfCnpj != null){
            $cpfCnpj = $request->cpfCnpj;
        }else{
            $cpfCnpj = "tudo";
        }

        if($request->protocolo != null){
            $protocolo = $request->protocolo;
        }else{
            $protocolo = "tudo";
        }

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

        if($request->situacao == "tudo" ){
            $situacao = "tudo";
        }else{
            $situacao = $request->situacao;
        }

        if($request->empresa != null){
            $empresa = $request->empresa;
        }else{
            $empresa = "tudo";
        }

        if($request->subcategoria != null){
            $subcategoria = $request->subcategoria;
        }else{
            $subcategoria = "tudo";
        }

        if(($dataInicial == "tudo") && ($dataFinal == "tudo") && ($cpfCnpj == "tudo") && ($protocolo == "tudo") && ($situacao == "tudo") && ($empresa == "tudo") && ($subcategoria == "tudo")){
            return redirect('fatura/relatorioDetalhado');
        }else{
            return redirect()->route('relatorioDetalhado', ['cpfCnpj' => $cpfCnpj, 'protocolo' => $protocolo, 'dataInicial' => $dataInicial ,'dataFinal' => $dataFinal, 'situacao' => $situacao, 'empresa' => $empresa, 'subcategoria' => $subcategoria]);
        }
    }

    public function chamarCadastradas(){
        return redirect()->route('listarFaturas', ['cpfCnpj' => "tudo", 'protocolo' => "tudo", 'diario' => "tudo", 'situacao' => "Enviada", 'empresa' => "tudo", 'subcategoria' => "tudo"]);
    }



    public function gerarComprovante($protocolo){

        if(Gate::allows('administrador', Auth::user())|| Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){

            $fatura = Fatura::orderBy('diariodata.diarioData', 'desc');
            $fatura->leftJoin('diariodata', 'diariodata.diariodataID', 'fatura.diariodataID');
            $fatura->leftJoin('users as usuariopub', 'usuariopub.id', 'fatura.usuarioIDPublicou');
            $fatura->leftJoin('users as usuariodel', 'usuariodel.id', 'fatura.usuarioIDApagou');
            $fatura->join('users as usuario', 'usuario.id', 'fatura.usuarioID');
            $fatura->leftJoin('subcategoria', 'subcategoria.subcategoriaID', 'fatura.subcategoriaID');
            $fatura->join('tipodocumento', 'tipodocumento.tipoID', 'fatura.tipoID');
            $fatura->join('situacao', 'situacao.situacaoID', 'fatura.situacaoID');
            $fatura->where('protocoloCompleto', '=', $protocolo);
            $fatura->select('fatura.*', 'diariodata.numeroDiario', 'diariodata.diarioData', 'situacao.situacaoNome', 'subcategoria.subcategoriaNome', 'tipodocumento.tipoDocumento', 'usuario.name as usuarioNome', 'usuariopub.name as usuarioNomePublicou', 'usuariodel.name as usuarioNomeApagou');
            $fatura = $fatura->first();

            if($fatura != null){

                // Verifica se a fatura foi apagada e se o usuário é o adm
                if($fatura->situacaoID == 2){
                    if(!(Gate::allows('administrador', Auth::user()))){
                        return redirect('home')->with('erro', ' Você não tem permissão!');
                    }
                }

                // foto do cabeçalho do comprovante
                $path = storage_path("app/"."top.jpg");

                $pdf = BPDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

                $pdf->setPaper('a4', 'portrait')->loadView('fatura.comprovante', ['fatura' => $fatura, 'path' => $path]);

                return $pdf->stream('comprovante.pdf');

            }else{
                return redirect('home')->with('erro', 'Protocolo de fatura não existente');
            }

        }else{

            return redirect('home')->with('erro', 'Você não tem permissão para isso!');

        }

    }

    public function editarAnotacao(Request $request){

        $fatura = Fatura::orderBy('dataEnvioFatura')->where('protocoloCompleto', '=', $request->protocolo)->first();

        if(strlen($request->anotacao) > 255){
            return redirect()->back()->with('erro', 'Observação com tamanho excedido!');
        }

        if($fatura != null){
            if($fatura->situacaoID != 4){
                return redirect()->back()->with('erro', 'Somente faturas Cadastradas podem realizar essa ação!');
            }
        }else{
            return redirect()->back()->with('erro', 'Protocolo Não Existente!');
        }

        $faturaEdit = Fatura::orderBy('dataEnvioFatura')->where('protocoloCompleto', '=', $request->protocolo)->update(['observacao' => $request->observacao]);
        return redirect()->back()->with('sucesso', 'Observação Adicionada!');
    }


    public function anexarDAM(Request $request){


        $fatura = Fatura::orderBy('dataEnvioFatura')->where('protocoloCompleto', '=', $request->protocolo)->first();

        if(((filesize($request->arquivo) / 1024)/1024) > 30){
            return redirect()->back()->with('erro', 'Tamanho do arquivo excedido!');
        }

        if($fatura != null){
            if($fatura->situacaoID != 4){
                return redirect()->back()->with('erro', 'Somente faturas Cadastradas podem realizar essa ação!');
            }
        }else{
            return redirect()->back()->with('erro', 'Protocolo Não Existente!');
        }

        try {
            DB::table('log')->orderBy('logData')->insert(['logData' => date('Y-m-d H:i:s'), 'usuarioID' =>  Auth::user()->id , 'logDescricao' => 'Usuario: '.Auth::user()->name.'(id:'.Auth::user()->id.')  Anexou o DAM da Fatura de protocolo '.$request->protocolo]);

            $fileName = "DAM-".$request->protocolo.'.'.pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION);
            //@mudar
            $request->arquivo->storeAs($fatura->protocoloAno."/".$request->protocolo."/", $fileName);

            // $request->arquivo->storeAs($request->protocolo."/", $fileName);

            $faturaEdit = Fatura::orderBy('dataEnvioFatura')->where('protocoloCompleto', '=', $request->protocolo)->update(['dam' => $fileName]);


            return redirect()->back()->with('DAM', 'Dam Anexado!');

        } catch (\Exception $e) {

            // @Mudar
            if(file_exists(storage_path("app/".$fatura->protocoloAno."/".$request->protocolo."/")."DAM-".$request->protocolo.'.'.pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION))){
                File::delete(storage_path("app/".$fatura->protocoloAno."/".$request->protocolo."/")."DAM-".$request->protocolo.'.'.pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION));
            }
            // if(file_exists(storage_path("app/".$request->protocolo."/")."DAM-".$request->protocolo.'.'.pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION))){
            //     File::delete(storage_path("app/".$request->protocolo."/")."DAM-".$request->protocolo.'.'.pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION));
            // }
            return redirect()->back()->with('erro', 'Erro ao Anexar o DAM!'.$e->getMessage());

        }

    }


    public function downloadDAM($protocolo){
        if(Gate::allows('administrador', Auth::user()) || Gate::allows('faturas', Auth::user()) || Gate::allows('publicador', Auth::user())){
                $fatura = Fatura::orderBy('dataEnvioFatura')->where('protocoloCompleto', '=', $protocolo)->first();
                try {

                    //@mudar
                    return Response::download(storage_path("app/".$fatura->protocoloAno."/".$protocolo."/".$fatura->dam));
                    // return Response::download(storage_path("app/".$protocolo."/".$fatura->dam));

                } catch (\Exception $e) {

                    return redirect()->back()->with(['erro' => 'Ocorreu um erro! erro: '.$e->getMessage()]);

                }
        }else{
            return redirect('home');
        }
    }

    public function searchResponseEmpresa(Request $request){
        $query = $request->get('term','');

        $empresas=\DB::table('fatura')->orderBy('empresa');
        $empresas->select('empresa', 'cpfCnpj', 'telefoneCelular', 'email', 'telefoneFixo');
        if($request->type=='empresa'){
            $empresas->where('empresa','LIKE','%'.$query.'%');
        }

        $empresas->groupBy('empresa');

        $empresas=$empresas->get();

        $data=array();
        foreach ($empresas as $empresa) {
                $data[]=array('empresa'=>$empresa->empresa,'cpfCnpj'=>$empresa->cpfCnpj,'telefoneCelular'=>$empresa->telefoneCelular, 'telefoneFixo' => $empresa->telefoneFixo, 'email'=>$empresa->email);
        }
        if(count($data))
             return $data;
        else
            return ['empresa'=>'','cpfCnpj'=>'', 'telefoneCelular'=>'', 'email'=>'', 'telefoneFixo'=>''];
    }





    // Teste Zone

    private function read_doc($filePath) {
        $fileHandle = fopen($filePath, "r");
        $line = @fread($fileHandle, filesize($filePath));
        $lines = explode(chr(0x0D),$line);
        $outtext = "";

        foreach($lines as $thisline)
          {

            $pos = strpos($thisline, chr(0x00));

            if (($pos !== FALSE)||(strlen($thisline)==0))
              {

              } else {

                $outtext .= $thisline." ";
              }

          }

         $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);

         return $outtext;
    }

    public function convertToText() {

        $filePath = "C:/xampp/htdocs/controlediariooficial/public/testedeimpressao.doc";
        // $arquivo = \PhpOffice\PhpWord\IOFactory::load($filePath, 'Word2007');

        // $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($arquivo, "Word2007");
        // $objectWriter->save("C:/xampp/htdocs/controlediariooficial/public/teste.docx");

        if(isset($filePath) && !file_exists($filePath)) {
            return "File Not exists";
        }

        $fileArray = pathinfo($filePath);
        $file_ext  = $fileArray['extension'];
        if($file_ext == "doc")
        {
            if($file_ext == "doc") {
                return $this->read_doc($filePath);
            }
        } else {
            return "Invalid File Type";
        }
    }























}





















