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
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header"> {{ __('Fatura') }}</div>

                <div class="card-body">

                            <div class="form-group row">
                                <div class="col-md-10">
                                    <p>Protocolo: <strong>{{$fatura->protocoloCompleto}} </strong></p>
                                </div>
                            </div>



                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Empresa: <strong>{{$fatura->empresa}} </strong></p>
                                    </div>
                            </div>



                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Requisitante: <strong>{{$fatura->requisitante}} </strong></p>
                                    </div>
                            </div>


                            @php
                                    // Calculo da mascara do cpf ou cnpj

                                    if(strlen($fatura->cpfCnpj) > 11){
                                        $mask = '##.###.###/####-##';
                                    }else{
                                        $mask ='###.###.###-##';
                                    }

                                    $val = $fatura->cpfCnpj;
                                    $maskared = '';
                                    $k = 0;
                                    for($i = 0; $i<=strlen($mask)-1; $i++)
                                    {
                                        if($mask[$i] == '#')
                                        {
                                            if(isset($val[$k]))
                                                $maskared .= $val[$k++];
                                        }
                                        else
                                        {
                                            if(isset($mask[$i]))
                                                $maskared .= $mask[$i];
                                        }
                                    }

                                @endphp

                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>CPF / CNPJ: <strong>{{$maskared}}</strong> </p>
                                    </div>
                            </div>

                            @php
                                $dataEnvio = new DateTime($fatura->dataEnvioFatura);
                                $dataEnvio = $dataEnvio->format('d/m/Y');
                            @endphp
                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Data Envio: <strong>{{$dataEnvio}}</strong> </p>
                                    </div>
                            </div>

                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Usuário Que Emitiu: <strong style="text-transform:capitalize;">{{$fatura->usuarioNome}}</strong> </p>
                                    </div>
                            </div>


                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <span>Observações: </span>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea disabled cols="60" rows="4" class="form-control"> {{$fatura->observacao}} </textarea>
                                    </div>
                            </div>



                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Matéria: <strong>{{$fatura->tipoDocumento}}</strong> </p>
                                    </div>
                            </div>


                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Subcategoria: <strong> @if($fatura->subcategoriaNome != null) {{$fatura->subcategoriaNome}} @else Não Possui @endif </strong> </p>
                                    </div>
                            </div>



                            @if($fatura->diarioData != null)
                                @php
                                    $data = new DateTime($fatura->diarioData);
                                    $data = $data->format('d/m/Y');
                                @endphp

                                <div class="form-group row">
                                        <div class="col-md-10">
                                            <p>Diário: <strong> N° {{$fatura->numeroDiario}}  Data: {{$data}}</strong> </p>
                                        </div>
                                </div>
                            @else

                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Diário: <strong> Não Possui </strong> </p>
                                    </div>
                                </div>

                            @endif


                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Largura Coluna: <strong>{{$fatura->largura}} cm </strong> </p>
                                    </div>
                            </div>



                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Valor Coluna: <strong>R$ {{$fatura->valorColuna}} </strong> </p>
                                    </div>
                            </div>



                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Centimetragem: <strong>{{$fatura->centimetragem}} cm </strong> </p>
                                    </div>
                            </div>


                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Valor da Fatura: <strong>R$ {{$fatura->valor}} </strong> </p>
                                    </div>
                            </div>

                            <div class="form-group row">
                                    <div class="col-md-10">
                                        <p>Situação: <strong>{{$fatura->situacaoNome}} </strong> </p>
                                    </div>
                            </div>

                            @if ($fatura->situacaoNome == "Rejeitada")

                                <div class="form-group row">
                                        <div class="col-md-10">
                                            <span>Descrição: </span>
                                        </div>
                                        <div class="col-md-10">
                                            <textarea disabled cols="60" rows="4" class="form-control"> {{$fatura->descricaoCancelamento}} </textarea>
                                        </div>
                                </div>

                            @endif


                            <div class="form-group row">

                                    @php
                                        $modalPublicar = false;
                                        $modalAceitar = false;
                                        $modalRejeitar = false;
                                    @endphp

                                    @if ($fatura->situacaoNome == "Enviada" || $fatura->situacaoNome == "Rejeitada")
                                        @php
                                            $modalAceitar = true;
                                        @endphp
                                        <div class="col-md-2" style="min-width:100px;">
                                            <a class="btn btn-success" style="width:100px; color:azure; " data-toggle="modal" data-target="#modalAceitar">Aceitar</a>
                                        </div>
                                    @endif

                                    @if ($fatura->situacaoNome == "Enviada" || $fatura->situacaoNome =="Aceita")
                                        @php
                                            $modalRejeitar = true;
                                        @endphp
                                        <div class="col-md-2" style="min-width:100px;">
                                            <a class="btn btn-danger" style="width:100px; color:azure; " data-toggle="modal" data-target="#modalRejeitar">Rejeitar</a>
                                        </div>
                                    @endif

                                    @if ($fatura->situacaoNome == "Aceita" && Gate::allows('administrador', Auth::user()))
                                        @php
                                            $modalPublicar = true;
                                        @endphp
                                        <div class="col-md-2" style="min-width:100px;">
                                            <a class="btn btn-success" style="width:100px; color:azure; " data-toggle="modal" data-target="#modalPublicar">Publicar</a>
                                        </div>
                                    @endif

                            </div>

                            <br>

                                @if ( isset($formatada) )

                                    <div class="form-group row">
                                            <div class="col-md-6 offset-md-4">
                                                <a href="/fatura/downloadFormatado/{{$fatura->protocoloCompleto}}" class="btn btn-primary" style="width:150px; float:right;">Arquivo Formatado</a>
                                            </div>
                                    </div>

                                    <div class="form-group row">
                                            <div class="col-md-6 offset-md-4">
                                                <a href="/fatura/downloadOriginal/{{$fatura->protocoloCompleto}}" class="btn btn-primary" style="width:150px; float:right;">Arquivo Original</a>
                                            </div>
                                    </div>


                                    @if ($fatura->situacaoNome == "Aceita" || $fatura->situacaoNome == "Publicada" )
                                        <div class="form-group row">
                                                <div class="col-md-6 offset-md-4">
                                                    <a href="/fatura/downloadComprovantePago/{{$fatura->protocoloCompleto}}" style="width:150px; float:right;" class="btn btn-primary">Comprovante Pago</a>
                                                </div>
                                        </div>
                                    @endif

                                @endif

                                <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <a href="/fatura/gerarComprovante/{{$fatura->protocoloCompleto}}"  target="_blank" class="btn btn-primary" style="width:150px; float:right;">Comprovante Envio</a>
                                        </div>
                                </div>

                                <br>

                                <div style="float:right; margin-bottom:1%;">
                                        <button type="button" class="btn btn-primary" id="btnVoltar">
                                            Voltar
                                        </button>
                                </div>

                </div>

            </div>
        </div>
        @if (isset($formatada))
            <div class="col-md-5">
                    <div class="card" style="text-align:center;"> {{-- Coluna da visualização  --}}
                        <div class="card-header"> {{ __('Visualização') }}</div>
                    <div class="card-body table-responsive">

                        {{-- RESPONSAVEL POR GERAR UM VISUALIZAÇÃO DO ARQUIVO FORMATADO --}}
                    @php
                    $size = 10;              // | Muda de acordo com a necessidade
                    $family = "Times";      // | Muda de acordo com a necessidade
                    @endphp

                    <div id="fatura" style="width:{{$faturaConfig[0]->largura}}cm; font-family:{{$family}}; line-height:{{$size+1}}pt; ">  {{-- Propriedade line height alterada 1 pt a mais que o pt da letra --}}
                            @php
                                $contaTexto = 0;
                                $paragrafo = false;
                               foreach ($formatada->getSections()[0]->getElements() as $txtRunOuTxt) {

                                   if(get_class($txtRunOuTxt) == "PhpOffice\PhpWord\Element\TextRun" ){

                                    if($paragrafo){

                                        echo '<p id="'.$contaTexto.'" style="text-align:justify; font-size:'.$size.'pt; margin:1pt;">';

                                        foreach ($txtRunOuTxt->getElements() as $txt) {

                                            if($txt->fontStyle->bold){
                                                echo '<b>'.$txt->getText().'</b>';
                                            }else{
                                                echo $txt->getText();
                                            }

                                        }

                                        echo "</p>";

                                    }else{

                                        if($contaTexto == 0){

                                            echo '<div id="titulo">';
                                            echo '<p style="text-align:center; font-size:'.$size.'pt; margin:1pt;">';

                                            foreach ($txtRunOuTxt->getElements() as $txt) {

                                                if($txt->fontStyle->bold){
                                                    echo '<b>'.$txt->getText().'</b>';
                                                }else{
                                                    echo $txt->getText();
                                                }

                                            }

                                            echo "</p>";
                                            echo "</div>";

                                        }else{

                                            echo '<p id="'.$contaTexto.'" style="text-align:justify; font-size:'.$size.'pt; margin:1pt;">';

                                            foreach ($txtRunOuTxt->getElements() as $txt) {

                                                if($txt->fontStyle->bold){
                                                    echo '<b>'.$txt->getText().'</b>';
                                                }else{
                                                    echo $txt->getText();
                                                }

                                            }

                                            echo "</p>";

                                            $paragrafo = true;

                                        }

                                    }

                                   }else{
                                       if($txtRunOuTxt->getText() == "" || $txtRunOuTxt->getText() == " "){
                                            // texto vazio de vez em quando
                                       }else{
                                        if($contaTexto == 0){

                                            echo '<div id="titulo">';
                                                echo '<p style="text-align:center; font-size:'.$size.'pt; font-weight:bold;">'.$txtRunOuTxt->getText()."</p>";
                                            echo '</div>';

                                            }else{
                                                if($txtRunOuTxt->fontStyle->bold){
                                                    if($paragrafo){
                                                        echo '<p id="'.$contaTexto.'" style="text-align:justify; font-size:'.$size.'pt; margin:1pt; font-weight:bold;">'.$txtRunOuTxt->getText()."</p>";
                                                    }else{
                                                        echo '<p id="'.$contaTexto.'" style="text-align:justify; font-size:'.$size.'pt; font-weight:bold;">'.$txtRunOuTxt->getText()."</p>";
                                                        $paragrafo = true;
                                                    }
                                                }else{

                                                    if($paragrafo){
                                                        echo '<p id="'.$contaTexto.'" style="text-align:justify; font-size:'.$size.'pt; margin:1pt;">'.$txtRunOuTxt->getText()."</p>";
                                                    }else{
                                                        echo '<p id="'.$contaTexto.'" style="text-align:justify; font-size:'.$size.'pt;">'.$txtRunOuTxt->getText()."</p>";
                                                        $paragrafo = true;
                                                    }
                                                }
                                            }
                                       }

                                   }

                                   $contaTexto += 1;
                               }
                            @endphp
                    </div>

                    {{-- FIM DA VISUALIZAÇÃO DO ARQUIVO FORMATADO --}}
                    <br>

                    <div>
                    <a href="/fatura/visualizacao/{{$fatura->arquivoVisualizacao}}/{{$fatura->protocoloCompleto}}" type="button" class="btn btn-primary col-md-12" data-dismiss="modal">
                            Arquivo Base Centimetragem
                        </a>
                    </div>

                    </div>
                    </div>
                </div>
        @endif
    </div>


{{-- verifica se possui modal aceitar --}}

@if ($modalAceitar)
        <form id="formAceitar" action="/fatura/aceitar" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="protocolo" value="{{$fatura->protocoloCompleto}}">
            {{-- situacao Aceita --}}
            <div class='modal fade' id="modalAceitar" role='dialog'>
                    <div class='modal-dialog row justify-content-center'>
                        <div class="modal-content">
                                <div class="modal-header">
                                    <Strong class=" offset-md-4" > Confirmar Aceitar </Strong>
                                </div>
                                <div class="modal-body">

                                    <p> Segue protocolo de fatura:</p>
                                    <b> {{$fatura->protocoloCompleto}} </b>

                                    <br><br>

                                    <strong style="font-size:12pt;">Insira o comprovante de pagamento:</strong>
                                    <input type="file" class="form-control-file" name="arquivo" id="file" required>
                                    <strong><sub style="font-size:90%;">Somente arquivos nas extensão 'PDF'.
                                    Tamanho máximo: 30 MB</sub></strong>

                                    <br> <br> <br>

                                    <p> Ao realizar esta ação, você confirma que ouve pagamento dessa fatura!</p>
                                    <p><strong>Deseja realmente Aceitar?</strong></p>

                                    <div>
                                            <div style="float: left;" class="offset-md-3">
                                                <div>
                                                    <button id="btnAceitar" class="btn btn-success" name="publicar">Confirmar Aceitar</button>
                                                </div>
                                            </div>
                                            <div style="float: left; margin-left:2%;">
                                                <button id="btnDismiss"type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Voltar
                                                </button>
                                            </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif


{{-- verifica se possui modal Rejeitar --}}

@if ($modalRejeitar)
        <form id="formRejeitar" action="/fatura/rejeitar" method="POST" >
            @csrf
            <input type="hidden" name="protocolo" value="{{$fatura->protocoloCompleto}}">
            {{-- situacao Aceita --}}
            <div class='modal fade' id="modalRejeitar" role='dialog'>
                    <div class='modal-dialog row justify-content-center'>
                        <div class="modal-content">
                                <div class="modal-header">
                                    <Strong class=" offset-md-4" > Confirmar Rejeitar </Strong>
                                </div>
                                <div class="modal-body">

                                    <p> Segue protocolo de fatura:</p>
                                    <p> <b> {{$fatura->protocoloCompleto}} </b> </p>

                                    <p> <strong> Descreva o Motivo: </strong> </p>
                                    <textarea name="descricao" cols="60" rows="4" class="form-control" placeholder="Entre com a descrição!" style="resize: none;" value="{{old('descricao')}}" required></textarea>

                                    <br>
                                    <p> Ao realizar esta ação a fatura sera rejeitada, não podendo ser publicada!</p>
                                    <p><strong>Deseja realmente Rejeitar?</strong></p>

                                    <div>
                                            <div style="float: left;" class="offset-md-3">
                                                <div>
                                                    <button id="btnRejeitar"  class="btn btn-danger" name="publicar">Confirmar Rejeitar</button>
                                                </div>
                                            </div>
                                            <div style="float: left; margin-left:2%;">
                                                <button  type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Voltar
                                                </button>
                                            </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </form>
@endif

@if ($modalPublicar)
    <form action="/fatura/publicar" method="POST">
        @csrf
        <input type="hidden" name="protocolo" value="{{$fatura->protocoloCompleto}}">
        {{-- situacao publicada --}}
        <div class='modal fade' id="modalPublicar" role='dialog'>
                <div class='modal-dialog row justify-content-center'>
                    <div class="modal-content">
                            <div class="modal-header">
                                <Strong class=" offset-md-4" > Confirmar Publicar </Strong>
                            </div>
                            <div class="modal-body">

                                    <div id="divLimite" style="display:none;">
                                            <br>
                                            <h4 id="textoLimite" style="text-align:center; color:red;">Texto</h4>
                                            <br>
                                    </div>


                                <p> Segue protocolo de fatura:</p>
                                <p> <b> {{$fatura->protocoloCompleto}} </b> </p>
                                <p> Ao realizar esta ação você confirma que a fatura será publicada no diário especificado.</p>


                                {{-- Escolher o Diário --}}
                                <div class="form-group row">
                                    @php
                                        $diariosDatas = json_decode($diarioDatas);
                                    @endphp
                                        <label for="diario" class="col-md-4 col-form-label text-md-right">{{ __('Diário') }} <span style="color:red;">*</span></label>
                                        <div class="col-md-6">
                                            <select id="diario" class="custom-select  mr-sm-2" name="diarioDataID" required onchange="dataLimite()">
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
                                    </div>

                                <p><strong>Deseja realmente Publicar?</strong></p>
                                <div>
                                        <div style="float: left; display:none;" class="offset-md-3" id="divBotao">
                                            <div>
                                                <input type="submit" class="btn btn-success" name="publicar" value="Confirmar Publicar">
                                            </div>
                                        </div>
                                        <div style="float: left;" class="offset-md-3" id="divLabel">
                                            <Strong><span style="color:red; white-space:nowrap;" id="labelText">Escolha um Diário!</span></Strong>
                                        </div>

                                        <div style="float: left; margin-left:2%;">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Voltar
                                            </button>
                                        </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
    </form>
@endif

<div class="container" id="carregando" style="display:none;">
    <br><br>
    <h2 class="offset-md-4"> Carregando Solicitação </h2>
    <br>
    <div class="loader offset-md-5"></div>
</div>



<script>

    $(document).ready(function (){


        var diariosDiasLimites = <?php echo $diarioDatas; ?>;
        var url = "<?php  echo Session::get('urlVoltar');  ?>";

        $('#formAceitar').validate({
            errorClass: "my-error-class"
        });

        $('#file').val("");
        var canUpload = false;
        $('#file').bind('change', function() {
            if( ((this.files[0].size / 1024)/1024) > 30){
                canUpload = false;
            }else{
                canUpload = true;
            }
        });

        $('#formAceitar').submit( function(e){
             if($("#formAceitar").valid()){
                 if(canUpload == true){
                    $("#btnDismiss").trigger("click");
                    $("#carregando").css('display', 'block');
                    $("#pagina").css('display', 'none');
                    $('#modalRejeitar').css('display', 'none');
                    $('#modalAceitar').css('display', 'none');
                    $('#Erro').css('display', 'none');
                    $('html, body').animate({scrollTop: '0px'}, 300);
                 }else{
                    event.preventDefault();
                    alert("Upload somente de arquivos até 30 MB!");
                 }

             }
         });

         $("#btnVoltar").click(function(){
                location.replace(url);
        })

        dataLimite = function(){
             if(!$("#diario").val() == ""){
                diariosDiasLimites.forEach(element => {

                    if(element.diarioDataID == $("#diario").val()){

                        var podeEnviar = false;

                        var horaEnvio =  "<?php echo Auth::user()->horaEnvio; ?>";
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

@endauth

@endsection
