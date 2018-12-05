@extends('layouts.app')

@section('content')

@guest

@else

<div id="Sucesso" class="container">
        <div class="col-md-8 offset-md-2">
            @if(session()->has('sucesso'))
                <br>
                <div class="form-group row mb-0 alert alert-success" style="font-size:20px">
                    {{ session()->get('sucesso') }}
                </div>
            @endif
            </div>
        </div>
</div>

<div id="Erro" class="container">
        <div class="col-md-8 offset-md-2">
            @if(session()->has('erro'))
                <br>
                <div class="form-group row mb-0 alert alert-success" style="font-size:20px">
                    {{ session()->get('erro') }}
                </div>
            @endif
            </div>
        </div>
</div>

<br/><br/>


<div class="container">

        <br>
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Lista de Publicações </strong> </h4> </div> <br>


            <form id="formFiltro" action="{{url("publicacao/chamarListar")}}" method="POST">
            @csrf

            @if (Gate::allows('administrador', Auth::user()))

            <div class="table-responsive">
                    <table class="table table-bordred table-striped" style="background-color:#DEDDDD; border-radius: 20px;">

                            <tbody>
                                <tr style="background-color:transparent;">
                                    <td style="border-color:transparent;"><input style="resize:none; width: 200px;" type="text" class="form-control" name="nomeUsuario" placeholder="Nome do usário"></td>
                                    <td><input style="resize:none; width: 200px;" type="text" class="form-control" name="protocolo" placeholder="Protocolo"></td>
                                    <td>
                                        <select style="resize:none; width: 200px;" class="custom-select" name="orgao" placeholder="Orgão Requisitante">
                                            <option slected value="tudo">Órgãos</option>
                                            @foreach ($orgaos as $orgao)
                                                <option value=" {{$orgao->orgaoID}}"> {{$orgao->orgaoNome}} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input style="resize:none; width: 200px;" placeholder="Data Diário" class="form-control" type="text" onfocus="checarData()" onfocusout="checarData()" id="date">
                                        <input type="hidden" name="diario" value="tudo" id="diario">
                                    </td>
                                    <td>
                                        <select style="resize:none; width: 100px;" class="custom-select" name="situacao" >
                                                <option slected value="tudo">Situação</option>
                                            @foreach ($situacoes as $situacao)
                                                <option value=" {{$situacao->situacaoNome}} "> {{$situacao->situacaoNome}} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="border-color:transparent;"><button class="btn btn-primary" id="filtrar">Filtrar</button></td>
                                </tr>
                            </tbody>
                    </table>
            </div>

            @else

            <div class="table-responsive">
                    <table class="table table-bordred table-striped" style="background-color:#DEDDDD; border-radius: 20px;">

                            <tbody>
                                <tr style="background-color:transparent;">
                                    <td><input style="resize:none; width: 200px;" type="text" class="form-control" name="protocolo" placeholder="Protocolo"></td>
                                    <td>
                                        <input style="resize:none; width: 200px;" placeholder="Data Diário" class="form-control" type="text" onfocus="checarData()" onfocusout="checarData()" id="date">
                                        <input type="hidden" name="diario" value="tudo" id="diario">
                                    </td>
                                    <td>
                                        <select style="resize:none; width: 100px;" class="custom-select" name="situacao" >
                                                <option slected value="tudo">Situação</option>
                                            @foreach ($situacoes as $situacao)
                                                <option value=" {{$situacao->situacaoNome}} "> {{$situacao->situacaoNome}} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    {{-- compensar tamanho do filtro --}}
                                    <td style="resize:none; width: 200px;"></td>
                                    {{-- compensar tamanho do filtro --}}
                                    <td style="resize:none; width: 200px;"></td>

                                    <td style="border-color:transparent;"><button class="btn btn-primary" id="filtrar">Filtrar</button></td>
                                </tr>
                            </tbody>
                    </table>
            </div>

            @endif

            </form>

                <br><br>

            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>
                       <th>Protocolo</th>
                       <th>Diário</th>
                       <th>Orgão Requisitante</th>
                       <th>Data Envio</th>
                       <th>Usuário</th>
                       <th style="white-space:nowrap;">Situação  <a style="color:red;" href="" data-toggle="modal" data-target="#modalLegenda" ><i class="fas fa-question-circle"></i></a> </th>
                       <th style="text-align:center;">Ações</th>
                       {{-- @if (Gate::allows('administrador', Auth::user())) <th>Editar</th> @else  <th>Editar / Ver</th>  @endif --}}
                       {{-- <th>Apagar</th> --}}
                       {{-- verifica se ele é administrador --}}
                       {{-- @if (Gate::allows('administrador', Auth::user())) <th> Publicar </th>   @endif --}}
                       </thead>
        <tbody>

         @foreach ($publicacoes as $publicacao)

         @php
            $modalAceitar = false;
            $modalPublicar = false;
            $modalApagar = false;

            $dataDiario = new DateTime($publicacao->diarioData);
            $dataDiario = $dataDiario->format('d/m/Y');

            $dataEnviado = new DateTime($publicacao->dataEnvio);
            $dataEnviado = $dataEnviado->format('d/m/Y à\s\ H:i')
         @endphp

         <tr>

            <td>{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}</td>
            <td>N° {{$publicacao->numeroDiario}}<br>{{$dataDiario}}</td>
            <td> {{$publicacao->orgaoNome}}</td>
            <td> {{$dataEnviado}} </td>
            <td style="text-transform:capitalize;"> {{$publicacao->nomeUsuario}} </td>
            <td> {{$publicacao->situacaoNome}} </td>

            <td style="white-space:nowrap;">
                <a href='/publicacao/ver/{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}' class="btn btn-dark" style="width:75px">Ver</a>

            {{-- Verifica se o usuario é administrador e se não for, verifica se a situação permita ele editar --}}

            @if (Gate::allows('administrador', Auth::user()))
                @if ($publicacao->situacaoNome == "Apagada" || date('Y-m-d') >= $publicacao->diarioData)
                @else
                     <a href='/publicacao/editar/{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}' class="btn btn-primary" style="width:75px">Editar</a>
                @endif
            @else
                @if ($publicacao->situacaoNome == "Publicada" || $publicacao->situacaoNome == "Aceita" || $publicacao->situacaoNome == "Apagada" ||  date('Y-m-d') >= $publicacao->diarioData)
                @else
                     <a href='/publicacao/editar/{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}' class="btn btn-primary" style="width:75px">Editar</a>
                @endif
            @endif

            {{-- Verifica se pode apagar a publicacao --}}

            @if ($publicacao->situacaoNome != "Apagada" && (($publicacao->situacaoNome != "Publicada" && $publicacao->situacaoNome != "Aceita") || Gate::allows('administrador', Auth::user())))
                @php
                    $modalApagar = true;
                @endphp
                <button class="btn btn-danger" data-toggle="modal" data-target="#modalApagar{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}" style="width:75px">Apagar</button>
            @endif

            {{-- Verifica se é administrador e se pode publicar o arquivo --}}

            @if ($publicacao->diarioData <= date('Y-m-d') && Gate::allows('administrador', Auth::user()) && $publicacao->situacaoNome != "Publicada" && $publicacao->situacaoNome != "Apagada" && $publicacao->situacaoNome == "Aceita")
                    @php
                        $modalPublicar = true;
                    @endphp
                    <button class="btn btn-success" data-toggle="modal" data-target="#modalPublicar{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}" style="width:75px">Publicar</button>

            @endif

        </td>
        </tr>


        @if ($modalApagar)
        <form action="/publicacao/apagar" method="POST">
            @csrf
            <input type="hidden" name="protocolo" value="{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}">
            {{-- situacao Apagada --}}
            <input type="hidden" name="situacaoID" value="2">
            <input type="hidden" name="arquivo" value="{{$publicacao->arquivo}}">
            <div class='modal fade' id="modalApagar{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}" role='dialog'>
                    <div class='modal-dialog row justify-content-center'>
                        <div class="modal-content">
                                <div class="modal-header">
                                    <Strong class=" offset-md-5" > ATENÇÃO </Strong>
                                </div>
                                <div class="modal-body">

                                    <p> <b>Ao apagar esta publicação, o arquivo será removido do servidor e não será maos possível editar ou publicar! </b> </p>

                                    <br><br>

                                    <p><strong>Deseja realmente Apagar?</strong></p>

                                    <div>
                                            <div style="float: left;" class="offset-md-3">
                                                <div>
                                                    <input type="submit" class="btn btn-danger" name="publicar" value="Confirmar Apagar">
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

        @if ($modalPublicar)
        <form action="/publicacao/publicar" method="POST">
            @csrf
            <input type="hidden" name="protocolo" value="{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}">
            {{-- situacao publicada --}}
            <input type="hidden" name="situacaoID" value="1">
            <div class='modal fade' id="modalPublicar{{$publicacao->protocolo}}{{$publicacao->protocoloAno}}" role='dialog'>
                    <div class='modal-dialog row justify-content-center'>
                        <div class="modal-content">
                                <div class="modal-header">
                                    <Strong class=" offset-md-4" > Confirmar Publicar </Strong>
                                </div>
                                <div class="modal-body">

                                    <p> Segue protocolo de publicação:</p>
                                    <p> <b> {{$publicacao->protocolo}}{{$publicacao->protocoloAno}} </b> </p>

                                    <p> Ao realizar esta ação o usuário que enviou a publicação não poderá mais edita-la!</p>

                                    <p><strong>Deseja realmente Publicar?</strong></p>

                                    <div>
                                            <div style="float: left;" class="offset-md-3">
                                                <div>
                                                        <input type="submit" class="btn btn-success" name="publicar" value="Confirmar Publicar">


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

         @endforeach

        </tbody>

    </table>

    {{$publicacoes->links()}}

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

                                <p> <strong> Enviada: </strong> </p>
                                <p> Solicitação enviada para o administrador, aguardando por uma resposta.</p>

                                <p> <strong> Aceita: </strong> </p>
                                <p> Solicitação aceita pelo administrador, aguardando ser publicada.</p>

                                <p> <strong> Rejeitada: </strong> </p>
                                <p> Solicitação Rejeitada pelo administrador, devido a algum motivo descrito pelo mesmo.</p>

                                <p> <strong> Publicada: </strong> </p>
                                <p> Solicitação publicada pelo administrador, presente no diário referente.</p>

                                <p> <strong> Apagada: </strong> </p>
                                <p> Solicitação apagada pelo administrador. Não podendo mais ser publicada ou aceita</p>

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

    </div>


    <script type="text/javascript">

        $(document).ready(function($) {

            var data = "tudo";

            checarData = function(){
                if($('#date').attr('type') == 'text'){
                    $('#date').attr('type', 'date');
                }else{
                    data = $('#date').val();
                    var datas =  $('#date').val().split('-');
                    var datanormal = datas[2]+'/'+datas[1]+'/'+datas[0];
                    $('#date').attr('type', 'text');
                    if($('#date').val() != ""){
                        $('#date').val(datanormal);
                    }else{
                        data = "tudo";
                        $('#date').val("");
                    }

                }
            }


            $('#filtrar').click(function(){
                $('#diario').val(data);
                $('#formFiltro').submit();
            });

        });

    </script>

@endguest


@endsection
