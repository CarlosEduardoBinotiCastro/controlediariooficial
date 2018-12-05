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

<div class="container" id="pagina">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"> {{ __('Enviar Publicação') }}</div>

                <form id="form" action="/publicacao/salvar" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                        <div id="divLimite" style="display:none;">
                            <br>
                            <h4 id="textoLimite" style="text-align:center; color:red;">Texto</h4>
                            <br>
                        </div>
                        <!-- Corpo da publicação -->
                    <div class="row col-md-12">
                        <div class="col-md-6">
                                {{-- Escolher Caderno --}}
                                <div class="col-md-12">
                                    Caderno:
                                    <select class="custom-select" name="cadernoID" onchange="carregarDocumentos()" id="cadernoSelect" required>
                                            <option slected value=""> Escolha o Caderno </option>

                                            @foreach ($usuarioCaderno as $item)
                                                <option  value=" {{$item->cadernoID}} "> {{$item->cadernoNome}} </option>
                                            @endforeach
                                    </select>
                                </div>

                                {{-- Escolher Documento --}}
                                <div class="col-md-12">
                                    Documento:
                                    <select class="custom-select" name="tipoID" id="documentoSelect" required>
                                            <option slected value=""> Escolha o Documento </option>
                                    </select>
                                </div>


                                {{-- Escolher o Diário --}}
                                <div class="col-md-12">
                                    @php
                                        $diariosDatas = json_decode($diarioDatas);
                                    @endphp
                                        Diario:
                                        <select id="diario" class="custom-select" name="diarioDataID" required onchange="dataLimite()">
                                                <option slected value=""> Escolha o Diario </option>
                                                @foreach ($diariosDatas as $item)
                                                    @php
                                                        $data = new DateTime($item->diarioData);
                                                        $data = $data->format('d/m/Y');
                                                    @endphp
                                                    <option  value="{{$item->diarioDataID}} "> N°{{$item->numeroDiario}} Data: {{$data}} </option>
                                                @endforeach
                                        </select>
                                    </div>
                            </div>

                            <div class="col-md-6">

                                <div class="col-md-12">
                                    Título
                                    <input id="titulo" type="text" name="titulo" class="form-control" placeholder="Título do Arquivo" minlength="4"  value="{{ old('titulo') }}" required>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <textarea name="descricao" cols="60" rows="4" class="form-control" placeholder="Entre com a descrição do arquivo!" style="resize: none;" value="{{old('descricao')}}" required></textarea>
                                </div>
                            </div>

                        </div>

                        <br><br>

                        <div class="col-md-6 offset-md-2">

                                <div class="col-md-12" style="text-align:justify;">
                                        <input id="termo" type="checkbox" name="termos" value="concordo" > <strong> Aceito e me responsabilizo pelos termos contidos na <a href="http://ioes.dio.es.gov.br/js/tinymce/plugins/responsivefilemanager/source/Instru%C3%A7%C3%A3o%20Normativa%20001-2016.pdf" style="color:blue;">Instrução Normativa DIO/ES nº 001/2016</a> , publicada no D.O. do dia 02 de Maio de 2016. </strong>
                                    </div>

                        </div>

                        <br>

                        <div class=" row col-md-6 offset-md-2">

                            {{-- Relecionado com o lado do input --}}

                                <div class="col-md-8">
                                <br>
                                <input type="file" class="form-control-file" name="arquivo" required>
                                <strong><sub style="font-size:90%;">Somente arquivos nas extensões 'pdf', 'docx', 'odt', 'rtf', 'doc', 'xlsx' e 'xls'. <br>
                                Tamanho máximo: 30 MB</sub></strong>
                            </div>

                            {{-- Relecionado com o lado do botão --}}


                                <div class="col-md-4" id="divBotao" style="display:none;">
                                    <br>
                                    <input id="btnEnviar" type="submit" class="btn btn-success" value="Enviar Publicação">
                                </div>
                                <div class="col-md-4" id="divLabel">
                                    <br>
                                    <Strong><span style="color:red; white-space:nowrap;" id="labelText">Ecolha um Diário!</span></Strong>

                                </div>

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

@endauth



<script type="text/javascript">

    $(document).ready(function($) {

        $('#form').validate({
            errorClass: "my-error-class"
        });


        $("#cadernoSelect").val("");
        $("#documentoSelect").val("");
        $("#termo").prop('checked', false);
        $("#btnEnviar").prop('disabled', true);


        var diaLimite;
        var documentos = <?php  echo $documentos; ?>;
        var diariosDiasLimites = <?php  echo $diarioDatas; ?>;

        carregarDocumentos = function(){
            var selected = $("#cadernoSelect").val();

            if(selected != ""){
                $("#documentoSelect").empty();
                $("#documentoSelect").append('<option selected value="">Escolha o Documento</option>');

                documentos.forEach(element => {
                    if(element.cadernoID == selected){
                        $("#documentoSelect").append('<option value="'+element.tipoID+'">'+element.tipoDocumento+'</option>');
                    }
                });
            }else{
                $("#documentoSelect").empty();
                $("#documentoSelect").append('<option selected value="">Escolha o Documento</option>');
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
                $("#carregando").css('display', 'block');
                $("#pagina").css('display', 'none');
                $('#Erro').css('display', 'none');
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
                            $('#divLabel').css('display', 'none');
                            $('#divBotao').css('display', 'block');
                            $("#textoLimite").text('Para esse diário, você pode enviar até o dia: '+dataLimite[2]+'/'+dataLimite[1]+'/'+dataLimite[0]+' ás: '+ horaEnvio[0]+':'+ horaEnvio[1] + ' Horas');
                        }else{
                            $('#divBotao').css('display', 'none');
                            $('#divLabel').css('display', 'block');
                            $("#textoLimite").text('Para esse diário, você poderia enviar até o dia: '+dataLimite[2]+'/'+dataLimite[1]+'/'+dataLimite[0]+' ás: '+ horaEnvio[0]+':'+ horaEnvio[1] + ' Horas');
                            $('#labelText').text('Horário de envio ultrapassado!');
                        }

                    }
                });
             }else{
                $("#divLimite").css('display', 'none');
                $('#divBotao').css('display', 'none');
                $('#divLabel').css('display', 'block');
                $("#textoLimite").text('Escolha um Diário');
             }

         }


    });
</script>

@endsection
