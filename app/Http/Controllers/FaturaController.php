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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


class FaturaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function carregarConfiguracao(){
        if(Gate::allows('administrador', Auth::user())){
            $confFatura = DB::table('configuracaofatura')->first();
            $cadernos = Caderno::orderBy('cadernoNome')->get();
            return view('fatura.configuracao', ['config' => $confFatura, 'cadernos' => $cadernos]);
        }else{
            return redirect('home');
        }
    }

    public function salvarConfiguracao(Request $request){
        $table = DB::table('configuracaofatura')->orderBy('configID');

        if($request->valorColuna != null && $request->largura != null){
            $table->where('configID', '=', $request->configID)->update(['largura' => $request->largura, 'valorColuna' => $request->valorColuna, 'cadernoID' => $request->cadernoID]);
            return redirect('home')->with('sucesso', 'Configurações Salvas');
        }else{
            return redirect()->back()->with('erro', 'Valores Em Brano');
        }
    }

    public function cadastrar(){

        if(Gate::allows('administrador', Auth::user())){
            $horaEnvio = Auth::user()->horaEnvio;

            $diariosDatas = DiarioData::orderBy('diarioData', 'desc')->where('diarioData', '>', date('Y-m-d'))->get();
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
            return view('fatura.cadastrar', [ 'diarioDatas' => json_encode($diariosDatasLimites), 'horaEnvio' => $horaEnvio, 'config' => $confFatura, 'documentos' => $documentos, 'subcategorias' => $subcategorias]);
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

        // carrega configurações da fatura
        $faturaConfig = DB::table('configuracaofatura')->get();

        $fontSize = 10;      // Muda o Tamnho da Letra       |   MUDAR CASO MUDE ALGUM DIA
        $fontFamily = "Times";  //   A familia da fonte.    |   MUDAR CASO MUDE ALGUM DIA

        // cria o nome dos arquivos temporarios da fatura
        $fileName = $request->requisitante.'-'.date('Y-m-d-H-i-s')."_temp".'.'.pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION);
        $fileNameFormat = $request->requisitante.'-'.date('Y-m-d-H-i-s')."_format"."_temp".'.'.pathinfo($request->arquivo->getClientOriginalName(), PATHINFO_EXTENSION);

        $request->arquivo->storeAs("public/temp/", $fileName);

        $arquivo = \PhpOffice\PhpWord\IOFactory::load(storage_path("app/public/temp/". $fileName));

        // "PhpOffice\PhpWord\Element\TextRun" textrun class
        // "PhpOffice\PhpWord\Element\Text" text class


        // CONFIGURAÇÕES DO TEXTO DO DOCUMENTO

        // ALTERA INFORMAÇÕES PERTINENTE AO TEXTO, TAIS COMO TAMANHO DE LETRA, ALINHAMENTO E TIPO DE FONTE
        $contaParagrafos = 0;
        $passouTitulo = false;
        // dd($arquivo);
        try {
            foreach($arquivo->getSections()[0]->getElements() as $txtRunOuTxt){

                // Verifica se é o titulo, 1 pragrafo geralmente é o titulo, e centraliza o titulo
                // $txtRunOuTxt->paragraphStyle->setLineHeight(1.0);
                // dd($txtRunOuTxt->paragraphStyle->getLineHeight());


                if($contaParagrafos == 0){
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
                }else{

                    if($txtRunOuTxt->getText() != null){
                        $txtRunOuTxt->fontStyle->name = $fontFamily;
                        $txtRunOuTxt->fontStyle->size = $fontSize;
                    }else{

                        // elimina os espaços em branco dos paragrafos, menos o espaço entre o titulo e o texto.
                        if(!$passouTitulo){
                            if($contaParagrafos >= 1 && $arquivo->getSections()[0]->getElements()[$contaParagrafos-1]->paragraphStyle->alignment != "center"){
                                $removeLinha = $arquivo->getSections()[0]->getElements();
                                unset($removeLinha[$contaParagrafos]);
                                $arquivo->getSections()[0]->elements = $removeLinha;
                                $passouTitulo = true;
                            }
                        }else{
                            $removeLinha = $arquivo->getSections()[0]->getElements();
                            unset($removeLinha[$contaParagrafos]);
                            $arquivo->getSections()[0]->elements = $removeLinha;
                        }
                    }
                }
                $contaParagrafos += 1;
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('erro', 'Falha na formatação do arquivo, verifique se o mesmo segue o padrão do template e tente novamente');
        }

        //ALTERA AS INFORMAÇÕES PERTINENTE A PAGINA, LARGURA DE MARGEM E TAMANHO DE PAPEL

        $arquivo->getSections()[0]->getStyle()->setMarginBottom("0");
        $arquivo->getSections()[0]->getStyle()->setMarginLeft("0");
        $arquivo->getSections()[0]->getStyle()->setMarginTop("0");
        $arquivo->getSections()[0]->getStyle()->setMarginRight("0");

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

        $subcategoria = SubCategoria::orderBy('subcategoriaNome');
        $subcategoria = $subcategoria->where('subcategoriaID', '=', intval($filtro['subcategoriaID']))->first();

        $diariosData = DiarioData::orderBy('diarioData', 'desc')->where('diarioData', '>', date('Y-m-d'))->where('diarioDataID', '=', $filtro['diarioDataID'])->first();
        $data = new DateTime($diariosData->diarioData);
        $data = $data->format('d/m/Y');

        $infoArray = array('subcategoriaNome' => $subcategoria->subcategoriaNome, 'tipoDocumento' => $documento->tipoDocumento, 'diario' => 'N° '.$diariosData->numeroDiario.'   Data: '.$data);
        $filtro += $infoArray;

        // fim das informações


        // dia limite

            $diaDiarioDate = new DateTime($diariosData->diarioData);
            $verificaDiaUtil = false;
            $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaDiarioDate->format('Y-m-d'))));
            do{
                $finalDeSemana = date('N', strtotime($diaUtil));
                if(!($finalDeSemana == '7' || $finalDeSemana == '6')){
                    if( !(DB::table('diasnaouteis')->where('diaNaoUtilData', '=', $diaUtil)->count()) ) {
                        $verificaDiaUtil = true;
                    }else{
                    }
                }
                if($verificaDiaUtil == false){
                    $diaUtil = date('Y-m-d', strtotime("-1 days",strtotime($diaUtil)));
                }
            }while($verificaDiaUtil == false);

        // fim do limite

        // necessario criar um json para se entendido pelo javascript
        return view('fatura.formatada', ['formatada' => $arquivo, 'faturaConfig' => $faturaConfig, 'fatura' => $filtro, 'dataLimite' => $diaUtil]);
    }


    public function downloadTemp($arquivoFormatadoTemp){
        if(file_exists(storage_path("app/public/temp/".$arquivoFormatadoTemp))){

            return Response::download(storage_path("app/public/temp/".$arquivoFormatadoTemp), 'visualizacao.docx');
        }else{
            return redirect('home')->with('erro', 'Arquivo não Encontrado!');
        }
    }

    public function salvar(Request $request){

        dd($request);

        switch ($this->validar($request)){

            case 1:

            break;

            case 2:

            break;

            default:



            break;

        }

    }


    public function validar($request){

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

        // Verifica se o numero de digitos informados é igual a 11
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

}
