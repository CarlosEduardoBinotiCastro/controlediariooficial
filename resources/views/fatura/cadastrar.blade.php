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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header"> {{ __('Cadastrar Fatura') }}</div>

                <div class="card-body">

                        <div id="divLimite" style="display:none;">
                                <br>
                                <h4 id="textoLimite" style="text-align:center; color:red;">Texto</h4>
                                <br>
                        </div>

                    <form id='form' method="POST" action="{{ url("/fatura/salvar") }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="form-group row">
                            <label for="requisitante" class="col-md-4 col-form-label text-md-right">{{ __('Requisitante') }}</label>

                            <div class="col-md-6">
                                <input id="requisitante" type="text" class="form-control{{ $errors->has('requisitante') ? ' is-invalid' : '' }}" name="requisitante" value="{{ old('requisitante') }}" placeholder="nome do requisitante" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="empresa" class="col-md-4 col-form-label text-md-right">{{ __('Empresa') }}</label>

                            <div class="col-md-6">
                                <input id="empresa" type="text" class="form-control{{ $errors->has('empresa') ? ' is-invalid' : '' }}" name="empresa" value="{{ old('empresa') }}" placeholder="nome da empresa" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Documento') }}</label>

                            <div class="col-md-2">
                                <select class="custom-select mr-sm-2" name="tipoDoc" id="tipoDoc">
                                <option slected value="CPF">CPF</option>
                                <option value="RG">CNPJ</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <input id="numeroDoc" type="text" class="form-control" name="cpfcnpj" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Observação') }}</label>
                            <div class="col-md-6">
                                <textarea name="descricao" cols="60" rows="4" class="form-control" placeholder="Entre com as Observações!" style="resize: none;" value="{{old('observacao')}}" required></textarea>
                            </div>
                        </div>
                        <br>

                        <div class="form-group row">
                            <label for="largura" class="col-md-4 col-form-label text-md-right">{{ __('Largura da Coluna (cm)') }}</label>

                            <div class="col-md-6">
                                <input id="largura" type="text" class="form-control{{ $errors->has('largura') ? ' is-invalid' : '' }}" name="largura" value="{{$config->largura}}"  disabled autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="valorColuna" class="col-md-4 col-form-label text-md-right">{{ __('Valor da Coluna (reais)') }}</label>

                            <div class="col-md-6">
                                <input id="valorColuna" type="text" class="form-control{{ $errors->has('valorColuna') ? ' is-invalid' : '' }}" name="valorColuna" value="{{$config->valorColuna}}"  disabled autofocus>
                            </div>
                        </div>



                        {{-- Escolher o Diário --}}
                        <div class="form-group row">
                            @php
                                $diariosDatas = json_decode($diarioDatas);
                            @endphp
                                <label for="diario" class="col-md-4 col-form-label text-md-right">{{ __('Diário') }}</label>
                                <select id="diario" class="custom-select col-md-6" name="diarioDataID" required onchange="dataLimite()">
                                        <option slected value=""> Escolha o Diário </option>
                                        @foreach ($diariosDatas as $item)
                                            @php
                                                $data = new DateTime($item->diarioData);
                                                $data = $data->format('d/m/Y');
                                            @endphp
                                            <option  value="{{$item->diarioDataID}} "> N°{{$item->numeroDiario}} Data: {{$data}} </option>
                                        @endforeach
                                </select>
                            </div>

                            <br>
                                <div class="form-group row offset-md-6">
                                    <a href="/fatura/gerarTemplate" class="btn btn-primary">Template</a><a style="color:red;" href="" data-toggle="modal" data-target="#modalLegenda" ><i class="fas fa-question-circle fa-2x"></i></a>
                                </div>
                            <br>

                            <div class=" row col-md-8 offset-md-2">

                                {{-- Relecionado com o lado do input --}}

                                    <div class="col-md-8">
                                    <br>
                                    <input type="file" class="form-control-file" name="arquivo" required>
                                    <strong><sub style="font-size:90%;">Somente arquivos nas extensão 'DOCX'. <br>
                                    Tamanho máximo: 30 MB</sub></strong>
                                </div>

                                {{-- Relecionado com o lado do botão --}}


                                    <div class="col-md-4" id="divBotao" style="display:none;">
                                        <br>
                                        <input id="btnEnviar" type="submit" class="btn btn-success" value="Formatar Fatura">
                                    </div>
                                    <div class="col-md-4" id="divLabel">
                                        <br>
                                        <Strong><span style="color:red; white-space:nowrap;" id="labelText">Escolha um Diário!</span></Strong>

                                    </div>

                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class='modal fade' id="modalLegenda" role='dialog'>
        <div class='modal-dialog row justify-content-center'>
            <div class="modal-content">
                    <div class="modal-header">
                        <Strong class=" offset-md-5" > Legenda </Strong>
                    </div>
                    <div class="modal-body">

                        <p> <strong> Template de Arquivo: </strong> </p>
                        <p>Para formatar a fatura, porfavor faça o download do template, passe o conteúdo para o arquivo do tamplate.
                            Dessa forma, a possibilidade de algum erro é minimizado.
                        </p>

                        <div>
                                <div >
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        Voltar
                                    </button>
                                </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

@endauth


<script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>

<script type="text/javascript">

    $(document).ready(function($) {

        $('#form').validate({
            errorClass: "my-error-class"
        });

        $("#numeroDoc").attr('maxlength',11);
        $("#numeroDoc").mask('000.000.000-00', {reverse: true});

        var diariosDiasLimites = <?php  echo $diarioDatas; ?>;

        $(document).on('change','#tipoDoc',function(){
        if($("#tipoDoc").val() == 'CPF'){
            $("#numeroDoc").val(null);
            $("#numeroDoc").attr('maxlength',11);
            $("#numeroDoc").mask('000.000.000-00', {reverse: true});
        }else{
            $("#numeroDoc").val(null);
            $("#numeroDoc").attr('maxlength',15);
            $("#numeroDoc").mask('00.000.000/0000-00', {reverse: true});

        }
        });

        $("#form" ).submit(function( event ) {
            if($("#form").valid()){
                $("#numeroDoc").unmask();
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
