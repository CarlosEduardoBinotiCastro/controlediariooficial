@extends('layouts.app')
@section('content')

@auth

<div id="Erro" class="container">
        <div class="col-md-8 offset-md-2">
            @if(session()->has('erro'))
                <br>
                <div class="form-group row mb-0 alert alert-danger" style="font-size:20px">
                    {{ session()->get('erro') }}
                </div>
            @endif
            </div>
</div>

<br>

{{-- Começo do layout de edição de Publicação --}}

<div class="container" id="pagina">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"> <strong> {{ __('EDITAR Publicação') }} </strong> </div>
                    <form id="form" action="{{ url("/publicacao/salvar") }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="protocolo" value="{{$publicacao->protocoloCompleto}}">
                    <div class="card-body">
                            <div id="divLimite" style="display:none;">

                                    <h4 id="textoLimite" style="text-align:center; color:red;">Texto</h4>
                                    <br>
                                </div>
                            <p>INFORMAÇÕES DA PUBLICAÇÃO</p>
                            <div class=" row col-md-12">
                                <div class="col-md-6">
                                    <div>
                                            <p>Protocolo: <strong>{{$publicacao->protocoloCompleto}} </strong></p>
                                    </div>

                                    <div>
                                            @php
                                                $dataEnvio = new DateTime($publicacao->dataEnvio);
                                                $dataEnvio = $dataEnvio->format('d/m/Y à\s\ H:i');
                                            @endphp
                                        <p>Emitido por: <strong style="text-transform:capitalize;">{{$publicacao->nomeUsuarioCriado}}</strong> em <strong>{{$dataEnvio}}</strong></p>
                                    </div>

                                    @if ($publicacao->rejeitadaDescricao != null)
                                        <div>
                                            <p style="text-align:justify;">  Descrição da Rejeição:  <strong> {{$publicacao->rejeitadaDescricao}}</strong></p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <!-- Mudar -->
                        <br>

                            @if($publicacao->situacaoID == 4)

                                <div class="col-md-8 offset-md-2">
                                        <p style="color: red;"><strong>Ao editar, o usuário que emitiu será alterado pelo usuário que editou! </strong></p>
                                </div>

                            @else
                                <div class="col-md-8 offset-md-2">
                                        <p style="color: red;"><strong>Ao editar, a data de envio será alterada para data de edição, o arquivo será substituído no servidor e o usuário que emitiu será alterado pelo usuário que editou! </strong></p>
                                </div>
                            @endif

                        <br>
                            <!-- Corpo da publicação -->
                        <div class="row col-md-12">
                            <div class="col-md-6">
                                    {{-- Escolher Caderno --}}
                                    <div class="col-md-12">

                                        <!-- Mudar -->

                                        Caderno: <span style="color:red;">*</span>

                                        @if($publicacao->situacaoID == 4)

                                            <select disabled class="custom-select" name="cadernoID" onchange="carregarDocumentos()" id="cadernoSelect" required>
                                                    <option value=""> Escolha o Caderno </option>
                                                    @foreach ($usuarioCaderno as $item)
                                                        <option  @if($item->cadernoID == $publicacao->cadernoID) selected @endif  value=" {{$item->cadernoID}} "> {{$item->cadernoNome}} </option>
                                                    @endforeach
                                            </select>

                                        @else

                                            <select class="custom-select" name="cadernoID" onchange="carregarDocumentos()" id="cadernoSelect" required>
                                                    <option value=""> Escolha o Caderno </option>
                                                    @foreach ($usuarioCaderno as $item)
                                                        <option  @if($item->cadernoID == $publicacao->cadernoID) selected @endif  value=" {{$item->cadernoID}} "> {{$item->cadernoNome}} </option>
                                                    @endforeach
                                            </select>

                                        @endif


                                    </div>

                                    {{-- Escolher Documento --}}
                                    <div class="col-md-12">

                                        <!-- Mudar -->

                                        Matéria: <span style="color:red;">*</span>

                                        @if($publicacao->situacaoID == 4)


                                            <select disabled class="custom-select" name="tipoID" id="documentoSelect" required>
                                                    <option slected value=""> Escolha a Matéria </option>
                                            </select>

                                        @else

                                            <select class="custom-select" name="tipoID" id="documentoSelect" required>
                                                    <option slected value=""> Escolha a Matéria </option>
                                            </select>

                                        @endif
                                    </div>


                                    {{-- Escolher o Diário --}}


                                    <!-- Mudar -->
                                    <div class="col-md-12">

                                        @if($publicacao->situacaoID == 4)

                                            @php
                                                $data = new DateTime($publicacao->diarioData);
                                                $data = $data->format('d/m/Y');
                                            @endphp

                                            Diario: <span style="color:red;">*</span>
                                            <input type="text" disabled class="form-control" value=" N°{{$publicacao->numeroDiario}} Data: {{$data}} " >

                                        @else

                                            @php
                                                $diariosDatas = json_decode($diarioDatas);
                                            @endphp
                                             Diario: <span style="color:red;">*</span>
                                             <select class="custom-select" name="diarioDataID" required id="diario" onchange="dataLimite()">
                                                     <option value=""> Escolha o Diario </option>
                                                     @foreach ($diariosDatas as $item)
                                                         @php
                                                             $data = new DateTime($item->diarioData);
                                                             $data = $data->format('d/m/Y');
                                                         @endphp

                                                         <option @if($item->diarioDataID == $publicacao->diarioDataID) selected @endif  value=" {{$item->diarioDataID}} "> N°{{$item->numeroDiario}} Data: {{$data}} </option>
                                                     @endforeach
                                             </select>

                                         @if ($publicacao->diarioData <= date('Y-m-d'))
                                             <div class="col-md-12"><span style="color:red; white-space:nowrap;"><Strong>Esta publicação é de um diário passado, escolha outro!</Strong></span></div>
                                         @endif

                                        @endif

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="col-md-12">
                                        Título <span style="color:red;">*</span>
                                        <input id="titulo" type="text" name="titulo" class="form-control" placeholder="Título do Arquivo" minlength="4"  value="{{ $publicacao->titulo }}" required>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        Descrição <span style="color:red;">*</span>
                                        <textarea name="descricao" cols="60" rows="4" class="form-control" placeholder="Entre com a descrição do arquivo!" style="resize: none;" value="{{$publicacao->descricao}}" required>{{$publicacao->descricao}}</textarea>
                                    </div>
                                </div>

                            </div>

                            <br><br>

                            <div class="col-md-6 offset-md-2">

                                    <div class="col-md-12" style="text-align:justify;">
                                            <input id="termo" type="checkbox" name="termos" value="concordo" > <strong> Aceito e me responsabilizo pelos termos contidos na <a href="http://ioes.dio.es.gov.br/js/tinymce/plugins/responsivefilemanager/source/Instru%C3%A7%C3%A3o%20Normativa%20001-2016.pdf" style="color:blue;">Instrução Normativa DIO/ES nº 001/2019</a> </strong>
                                        </div>

                            </div>

                            <br><br>


                            <!-- Mudar Arquivo -->

                            @if($publicacao->situacaoID == 4)

                                <input id="manterArquivo" type="hidden" name="manterArquivo" value="sim">

                            @else

                             <div class="form-group row">
                                     <label class="col-md-4 col-form-label text-md-right"> <strong> {{ __('Manter Arquivos ?') }} </strong> </label>

                                     <div class="col-md-1">
                                         <input id="manterArquivo" type="checkbox" name="manterArquivo" value="sim"> <span>SIM</span>
                                     </div>

                                     <div class="col-md-4">
                                         <a href="{{ url("/publicacao/downloadPublicacao") }}/{{$publicacao->protocoloCompleto}}" class="btn btn-success" >Download Arquivos</a>
                                     </div>
                             </div>



                             <div class="row col-md-6 offset-md-2"  id="divAlterarArquivo">

                                 {{-- Relecionado com o lado do input --}}

                                     <div class="col-md-12" id="divInputArquivo" style="display:none;">
                                         <a id="btnArquivo" class="btn btn-success" style="color: white;"> Adicionar Arquivo </a>
                                         <br><br>

                                         <div id="arquivosUpload">
                                             <div class="row">
                                                 <input  type="file" class="form-control-file" id="file0" name="arquivo0"  required>
                                             </div>
                                         </div>

                                         <strong style="margin-top:2%;"><sub style="font-size:90%;">Somente arquivos nas extensões 'pdf', 'docx', 'odt', 'rtf', 'doc', 'xlsx' e 'xls'. <br>
                                         Tamanho máximo dos arquivos somados: 30 MB</sub></strong>

                                     </div>

                                 </div>

                                <!-- Fim Mudar Arquivo -->

                                @endif

                                {{-- Relecionado com o lado do botão --}}
                                <div class="form-group row mb-0 justify-content-center">
                                    <div class="col-md-4" id="divBotao">
                                        <br>
                                        <input id="btnEnviar" type="submit" class="btn btn-success" value="Editar Publicação">
                                    </div>

                                    <div class="col-md-4" id="divLabel" style="display:none;">
                                        <br>
                                        <Strong><span id="label" style="color:red; white-space:nowrap;"></span></Strong>
                                    </div>
                                </div>

                            </div>

                            <div style="float:right; margin-bottom:1%; margin-right:1%;">
                                    <button type="button" class="btn btn-primary" id="btnVoltar">
                                        Voltar
                                    </button>
                            </div>

                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="carregando" style="display:none;">
        <br><br>
        <h2 class="offset-md-4"> Carregando Solicitação </h2>
        <br>
        <div class="loader offset-md-5"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    <!-- Mudar Script -->


    <!-- Fim Mudar Script -->


    <script type="text/javascript">

        $(document).ready(function($) {



            var enviar = true;
            var diaLimite;
            var diariosDiasLimites = <?php  echo $diarioDatas; ?>;
            var publicacao = <?php  echo $publicacao ?>;
            var numeroArquivos = 0;

            if(publicacao.situacaoID == 4){

            }else{
                $("#documentoSelect").select2();
            }


            $('#form').validate({
                errorClass: "my-error-class"
            });

            $("#btnVoltar").click(function(){
                window.history.back();
            });

            // verifica se o diario atual carregado pode editar pela data

            if(!$("#diario").val() == ""){
                diariosDiasLimites.forEach(element => {

                    if(element.diarioDataID == $("#diario").val()){

                        var podeEnviar = false;

                        var horaEnvio =  "<?php  echo $horaEnvio; ?>";
                        horaEnvio = horaEnvio.split(':');
                        var horaAtual = "<?php echo date('H:i:s') ?>"

                        horaAtual = horaAtual.split(':');

                        var dataAtual = ("<?php echo date('Y-m-d') ?>").split('-');
                        var dataLimite = element.diaLimite.split('-');

                        dataAtual = new Date(dataAtual[0], dataAtual[1]-1, dataAtual[2]);
                        dataLimite = new Date(dataLimite[0], dataLimite[1]-1, dataLimite[2]);

                        if(dataLimite.getTime() > dataAtual.getTime()){
                            podeEnviar = true;
                        }else{
                            if(dataLimite.getTime() == dataAtual.getTime()){

                                if(horaAtual[0] > horaEnvio[0]){
                                    podeEnviar = false;
                                }else if(horaAtual[0] == horaEnvio[0]){
                                    if(horaAtual[1] > horaEnvio[1]){
                                        podeEnviar = false;
                                    }else{
                                        podeEnviar = true;
                                    }
                                }else{
                                    podeEnviar = true;
                                }
                            }
                        }
                        $("#divLimite").css('display', 'block');
                        dataLimite = element.diaLimite.split('-');


                        if(podeEnviar){
                            enviar = true;
                            $('#diaLimite').val(element.diaLimite);
                            $('#divLabel').css('display', 'none');
                            $('#divBotao').css('display', 'block');
                            $("#textoLimite").text('Para esse diário, você pode enviar até o dia: '+dataLimite[2]+'/'+dataLimite[1]+'/'+dataLimite[0]+' ás: '+ horaEnvio[0]+':'+ horaEnvio[1] + ' Horas');
                        }else{
                            enviar = false;
                            $('#divLabel').css('display', 'block');
                            $("#textoLimite").text('Para esse diário, você poderia enviar até o dia: '+dataLimite[2]+'/'+dataLimite[1]+'/'+dataLimite[0]+' ás: '+ horaEnvio[0]+':'+ horaEnvio[1] + ' Horas');
                            $("#label").css('display', 'block');
                            $('#divBotao').css('display', 'none');
                            $("#label").text('Horário de envio ultrapassado!');
                        }

                    }
                });
            }

            // fim da verificação

            $("#termo").prop('checked', false);
            $("#btnEnviar").prop('disabled', true);
            $("#manterArquivo").prop('checked', true);


            $("#manterArquivo").click(function () {

                if($("#divInputArquivo").css('display') == 'none'){
                    $('#divInputArquivo').prop('disabled', false);
                    $('#divInputArquivo').css('display', 'block');
                    if(!enviar){
                        $('#divLabel').css('display', 'block');
                        $('#divBotao').css('display', 'none');
                    }else{
                        $('#divLabel').css('display', 'none');
                        $('#divBotao').css('display', 'block');
                    }
                }else{

                    $('#divInputArquivo').css('display', 'none');
                    $('#divInputArquivo').prop('disabled', true);
                    $('#divInputArquivo-error').text('');
                }
            });


            var documentos = <?php  echo $documentos ?>;



            $("#documentoSelect").empty();
            $("#documentoSelect").append('<option selected value="">Escolha a Matéria</option>');
            documentos.forEach(element => {
                if(element.cadernoID ==  $("#cadernoSelect").val()){
                    if(publicacao.tipoID == element.tipoID){

                        $("#documentoSelect").append('<option selected value="'+element.tipoID+'">'+element.tipoDocumento+'</option>');

                    }else{
                        $("#documentoSelect").append('<option  value="'+element.tipoID+'">'+element.tipoDocumento+'</option>');
                    }
                }
            });


            carregarDocumentos = function(){
                var selected = $("#cadernoSelect").val();

                if(selected != ""){
                    $("#documentoSelect").empty();
                    $("#documentoSelect").append('<option selected value="">Escolha a Matéria</option>');

                    documentos.forEach(element => {
                        if(element.cadernoID == selected){
                            $("#documentoSelect").append('<option value="'+element.tipoID+'">'+element.tipoDocumento+'</option>');
                        }
                    });
                }else{
                    $("#documentoSelect").empty();
                    $("#documentoSelect").append('<option selected value="">Escolha a Matéria</option>');
                }
            }


            $("#termo").click(function () {
                if($("#btnEnviar").is(":disabled")){
                    $("#btnEnviar").attr('disabled', false);
                }else{
                    $("#btnEnviar").attr('disabled', true);
                }
            });

             $('#form').submit( function(e){
                if($("#form").valid()){

                    if($("#manterArquivo").prop('checked')){

                        $("#carregando").css('display', 'block');
                        $("#pagina").css('display', 'none');
                        $('#Erro').css('display', 'none');

                    }else{

                        var filesSize = 0;
                        var i;
                        for( i = 0; i <= numeroArquivos; i++){
                            var fileNumber = "file"+i;
                            filesSize += ($("#"+fileNumber+""))[0].files[0].size;
                        }
                        filesSize = (filesSize/1024/1024);

                        if(filesSize < 30){
                            $("#carregando").css('display', 'block');
                            $("#pagina").css('display', 'none');
                            $('#Erro').css('display', 'none');
                        }else{
                            e.preventDefault();
                            alert("O tamanho dos arquivos não podem passar de 30MB!");
                        }

                    }
                }
             });


             dataLimite = function(){
                if(!$("#diario").val() == ""){
                   diariosDiasLimites.forEach(element => {

                       if(element.diarioDataID == $("#diario").val()){

                           var podeEnviar = false;

                           var horaEnvio =  "<?php  echo $horaEnvio; ?>";
                           horaEnvio = horaEnvio.split(':');
                           var horaAtual = "<?php echo date('H:i:s') ?>"

                           horaAtual = horaAtual.split(':');

                           var dataAtual = ("<?php echo date('Y-m-d') ?>").split('-');
                           var dataLimite = element.diaLimite.split('-');

                           dataAtual = new Date(dataAtual[0], dataAtual[1]-1, dataAtual[2]);
                           dataLimite = new Date(dataLimite[0], dataLimite[1]-1, dataLimite[2]);

                           if(dataLimite.getTime() > dataAtual.getTime()){
                               podeEnviar = true;
                           }else{
                               if(dataLimite.getTime() == dataAtual.getTime()){

                                   if(horaAtual[0] > horaEnvio[0]){
                                       podeEnviar = false;
                                   }else if(horaAtual[0] == horaEnvio[0]){
                                       if(horaAtual[1] > horaEnvio[1]){
                                           podeEnviar = false;
                                       }else{
                                           podeEnviar = true;
                                       }
                                   }else{
                                       podeEnviar = true;
                                   }
                               }
                           }
                           $("#divLimite").css('display', 'block');
                           dataLimite = element.diaLimite.split('-');


                           if(podeEnviar){
                                enviar = true;
                               $('#diaLimite').val(element.diaLimite);
                               $('#divLabel').css('display', 'none');
                               $('#divBotao').css('display', 'block');
                               $("#textoLimite").text('Para esse diário, você pode enviar até o dia: '+dataLimite[2]+'/'+dataLimite[1]+'/'+dataLimite[0]+' ás: '+ horaEnvio[0]+':'+ horaEnvio[1] + ' Horas');
                           }else{
                                enviar = false;
                               $('#divLabel').css('display', 'block');
                               $('#label').css('display', 'block');
                               $('#divBotao').css('display', 'none');
                               $("#textoLimite").text('Para esse diário, você poderia enviar até o dia: '+dataLimite[2]+'/'+dataLimite[1]+'/'+dataLimite[0]+' ás: '+ horaEnvio[0]+':'+ horaEnvio[1] + ' Horas');
                               $('#label').text('Horário de envio ultrapassado!');
                           }

                       }
                   });
                }else{
                    enviar = false;
                   $("#divLimite").css('display', 'none');
                    $('#divLabel').css('display', 'block');
                    $('#divBotao').css('display', 'none');
                    $('#label').text('Nenhum Diario Selecionado!');

                }
            }

            $("#btnArquivo").click(function (){
                numeroArquivos++;
                var div = $("#arquivosUpload");
                div.append(' <div id="div'+numeroArquivos+'" class = "row" style="margin-top: 2%;"> <div> <input type="file" class="form-control-file" name="arquivo'+numeroArquivos+'" id="file'+numeroArquivos+'" style="margin-top:2%;" required> </div>  <div> <a style="margin-left: 10px; color:white;" class="btn btn-danger" onclick="deletarArquivo('+numeroArquivos+')">X</a> </div> </div>');
            });

            deletarArquivo = function(id){
               document.getElementById("div"+id).remove();
            }

        });


    </script>

  {{-- Fim do layout do editar publicação --}}

@endauth

@endsection
