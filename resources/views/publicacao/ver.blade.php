@extends('layouts.app')
@section('content')

@auth

@php
    $modalAceitar = false;
    $modalRejeitar = false;
@endphp


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


<div id="sucesso" class="container">
        <div class="col-md-8 offset-md-2">
            @if(session()->has('sucesso'))
                <br>
                <div class="form-group row mb-0 alert alert-success" style="font-size:20px">
                    {{ session()->get('sucesso') }}
                </div>
            @endif
            </div>
</div>
<br>
{{-- Começo do layout do ver publicação --}}

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header"> <strong> {{ __('Publicação') }} </strong> </div>

                <div class="card-body">

                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div>
                                        <p>Protocolo: <strong>{{$publicacao->protocoloCompleto}} </strong></p>
                                </div>

                                <div>
                                        <p>Situação: <strong>{{$publicacao->situacaoNome}}</strong></p>
                                </div>

                                @if ($publicacao->rejeitadaDescricao != null)
                                    <div>
                                        <p style="text-align:justify;">  Descrição da Rejeição:  <strong> {{$publicacao->rejeitadaDescricao}} </strong></p>
                                    </div>
                                @endif

                                @if ($publicacao->usuarioIDApagou != null)
                                    @php
                                        $dataApagado = new DateTime($publicacao->dataApagada);
                                        $dataApagado = $dataApagado->format('d/m/Y à\s\ H:i');
                                    @endphp
                                    <div>
                                    <p>Removido por: <strong style="text-transform:capitalize;">{{$publicacao->nomeUsuarioApagado}}</strong> em <strong>{{$dataApagado}}</strong></p>
                                    </div>
                                @endif

                                <br>

                                <div>
                                        @php
                                            $dataEnvio = new DateTime($publicacao->dataEnvio);
                                            $dataEnvio = $dataEnvio->format('d/m/Y à\s\ H:i');
                                        @endphp
                                    <p style="float:left;">Emitido por: <strong style="text-transform:capitalize;">{{$publicacao->nomeUsuarioCriado}}</strong> em <strong>{{$dataEnvio}}</strong></p>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalContatos" style="margin-left:2%;">Contato</button>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                    <p>Órgão Requisitante: <strong style="text-transform:capitalize;">{{$publicacao->orgaoNome}}</strong></p>
                            </div>

                            <div class="col-md-12">
                                    <div>
                                        <p>Caderno: <strong style="text-transform:capitalize;">{{$publicacao->cadernoNome}}</strong></p>
                                    </div>
                                    <div>
                                        <p>Matéria: <strong style="text-transform:capitalize;">{{$publicacao->tipoDocumento}}</strong></p>
                                    </div>
                            </div>

                            <br>

                            @php
                                $dataDiario = new DateTime($publicacao->diarioData);
                                $dataDiario = $dataDiario->format('d/m/Y');
                            @endphp
                            <div class="col-md-12">
                                <p>Diário Correspondente: <strong> N° {{$publicacao->numeroDiario}} Data: {{$dataDiario}} </strong></p>
                            </div>

                            <div class="col-md-12">
                                    <p>Título: <strong>{{$publicacao->titulo}}</strong></p>
                            </div>
                            <div class="col-md-9">
                                <p style="text-align:justify;">  Descrição:  <strong> {{$publicacao->descricao}} </strong></p>
                            </div>


                            @if ($publicacao->usuarioIDApagou == null)
                            <br>
                                <div class="col-md-12">
                                <a style="width:160px;" href="{{ url("/publicacao/downloadPublicacao") }}/{{$publicacao->protocoloCompleto}}" class="btn btn-success" >Download Arquivos</a>
                                </div>
                            @endif


                            <br>
                            <div class="col-md-12">
                                    <a style="width:160px;" href="{{ url("/publicacao/gerarComprovante") }}/{{$publicacao->protocoloCompleto}}" target="_blank" class="btn btn-success">Comprovante</a>
                            </div>

                            @if ($publicacao->situacaoID == 1 && $publicacao->diarioPublicado != null)
                                <br>
                                <div class="col-md-12">
                                    <a style="width:160px;" href='{{ url("/diariodata/downloadDiario") }}/{{$publicacao->diarioDataID}}' class="btn btn-success">Diáro Publicado</a>
                                </div>
                            @endif



                            <br><br>


                                @if ((Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())) && $publicacao->situacaoNome != "Publicada" && $publicacao->situacaoNome != "Aceita" && $publicacao->situacaoNome != "Apagada")
                                    @php
                                        $modalAceitar = true;
                                    @endphp
                                    <button style="width:75px; background-color:teal;" class="btn btn-success" data-toggle="modal" data-target="#modalAceitar{{$publicacao->protocoloCompleto}}">Aceitar</button>
                                @endif


                                @if ((Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user())) && $publicacao->situacaoNome != "Publicada" && $publicacao->situacaoNome != "Apagada" && $publicacao->situacaoNome != "Rejeitada")
                                    @php
                                        $modalRejeitar = true;
                                    @endphp
                                    <button style="width:75px;" class="btn btn-danger" data-toggle="modal" data-target="#modalRejeitar{{$publicacao->protocoloCompleto}}" >Rejeitar</button>
                                @endif



                            <div style="float:right; margin-bottom:1%;">
                                    <button type="button" class="btn btn-primary" id="btnVoltar">
                                        Voltar
                                    </button>
                            </div>

                        </div>
                    <br>

                </div>

            </div>
        </div>
    </div>
</div>


{{-- verifica se possui modal aceitar --}}

@if ($modalAceitar)
        <form id="formAceitar" action="{{ url("/publicacao/aceitar") }}" method="POST">
            @csrf
            <input type="hidden" name="protocolo" value="{{$publicacao->protocoloCompleto}}">
            {{-- situacao Aceita --}}
            <div class='modal fade' id="modalAceitar{{$publicacao->protocoloCompleto}}" role='dialog'>
                    <div class='modal-dialog row justify-content-center'>
                        <div class="modal-content">
                                <div class="modal-header">
                                    <Strong class=" offset-md-4" > Confirmar Aceitar </Strong>
                                </div>
                                <div class="modal-body">

                                    <p> Segue protocolo de publicação:</p>
                                    <p> <b> {{$publicacao->protocoloCompleto}} </b> </p>

                                    <p> Ao realizar esta ação o usuário que enviou a publicação não poderá mais edita-la!</p>
                                    <p><strong>Deseja realmente Aceitar?</strong></p>

                                    <div>
                                            <div style="float: left;" class="offset-md-3">
                                                <div>
                                                    <button id="btnAceitar" class="btn btn-success" name="publicar">Confirmar Aceitar</button>
                                                </div>
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


{{-- verifica se possui modal Rejeitar --}}

@if ($modalRejeitar)
        <form id="formRejeitar" action="{{ url("/publicacao/rejeitar") }}" method="POST">
            @csrf
            <input type="hidden" name="protocolo" value="{{$publicacao->protocoloCompleto}}">
            {{-- situacao Aceita --}}
            <div class='modal fade' id="modalRejeitar{{$publicacao->protocoloCompleto}}" role='dialog'>
                    <div class='modal-dialog row justify-content-center'>
                        <div class="modal-content">
                                <div class="modal-header">
                                    <Strong class=" offset-md-4" > Confirmar Rejeitar </Strong>
                                </div>
                                <div class="modal-body">

                                    <p> Segue protocolo de publicação:</p>
                                    <p> <b> {{$publicacao->protocoloCompleto}} </b> </p>

                                    <p> <strong> Descreva o Motivo: </strong> </p>
                                    <textarea name="descricao" cols="60" rows="4" class="form-control" placeholder="Entre com a descrição!" style="resize: none;" value="{{old('descricao')}}" required></textarea>

                                    <br>
                                    <p> Ao realizar esta ação o usuário que enviou a publicação não poderá mais edita-la!</p>
                                    <p><strong>Deseja realmente Rejeitar?</strong></p>

                                    <div>
                                            <div style="float: left;" class="offset-md-3">
                                                <div>
                                                    <button id="btnRejeitar"  class="btn btn-danger" name="publicar">Confirmar Rejeitar</button>
                                                </div>
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


{{-- Modal Contato --}}
<div class='modal fade' id="modalContatos" role='dialog'>
    <div class='modal-dialog row justify-content-center'>
        <div class="modal-content">
                <div class="modal-header">
                    <Strong class=" offset-md-5" > Contatos </Strong>
                </div>
                <div class="modal-body">

                    <span>  Nome: </span>
                    <span> <b> {{$publicacao->nomeUsuarioCriado}} </b> </span>

                    <br><br>

                    <span style="float:left;">  Tefelone Celular: </span>
                    <input id="celular" type="text" readonly value="{{$publicacao->telefoneCelularUsuarioEmitiu}}" style="background-color:transparent; border-color:transparent; margin-left:1%; font-weight:bold;">

                    <br><br>

                    <span>  Email: </span>
                    <span> <b> {{$publicacao->emailUsuarioEmitiu}} </b> </span>

                    <br><br>

                    <span> Orgão Requisitante:</span>
                    <span> <b> {{$publicacao->orgaoNomeUsuario}} </b> </span>

                    <br><br>

                    <span>  Tefelone Setor: </span>
                    </b><input id="fixo" type="text" readonly value="{{$publicacao->telefoneSetorUsuarioEmitiu}}" style="background-color:transparent; border-color:transparent; margin-left:1%; font-weight:bold;">

                    <br><br>

                    <div>
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




{{-- fim do layout do ver publicação --}}

    <script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function($) {


            var url = "<?php  echo Session::get('urlVoltar');  ?>";

            $("#fixo").mask('(99)9999-9999');
            $("#celular").mask('(99)99999-9999');

            $('#formRejeitar').validate({
                errorClass: "my-error-class"
            });

            $("#btnVoltar").click(function(){
                location.replace(url);
            })

            if(document.getElementById('formAceitar')){
                $('#btnAceitar').click(function(){
                    $('#formAceitar').submit();
                });
            }

            if(document.getElementById('formRejeitar')){
                $('#btnRejeitar').click(function(){
                    $('#formRejeitar').submit();
                });
            }

        });
    </script>



@endauth

@endsection
