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
                <div class="form-group row mb-0 alert alert-danger" style="font-size:20px">
                    {{ session()->get('erro') }}
                </div>
                @if (session()->has('publicacoes'))
                    <div style="display: block;" class="form-group row mb-0 alert alert-danger" style="font-size:20px">


                                <p> Segue protocolos dos pedidos de publicações para este documento: </p>

                                @foreach (session()->get('publicacoes') as $item)
                                    <span> <i>{{$item->protocolo}}{{$item->protocoloAno}}</i> <strong> / </strong> </span>
                                @endforeach



                    </div>
                @endif
            @endif
            </div>
</div>


<br/><br/>


<div class="container col-md-10">

        <br>
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Lista de Publicações Apagadas </strong> </h4> </div> <br>

            <form id="formFiltro" action="{{url("publicacao/chamarApagadas")}}" method="POST">
                @csrf

                @if (Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()))

                <div class="table-responsive">
                        <table class="table table-bordred table-striped" style="background-color:#DEDDDD; border-radius: 20px;">

                                <tbody>
                                    <tr style="background-color:transparent;">
                                        <td style="border-color:transparent;"><input style="resize:none; width: 175px;" type="text" class="form-control" name="nomeUsuario" placeholder="Nome do usário"></td>
                                    <td><input style="resize:none; width: 175px;" type="text" class="form-control" name="protocolo" placeholder="Protocolo"></td>
                                    <td><input style="resize:none; width: 175px;" type="text" class="form-control" name="titulo" placeholder="Título"></td>
                                    <td>
                                        <select style="resize:none; width: 175px;" class="custom-select" name="orgao" placeholder="Órgão Requisitante">
                                            <option slected value="tudo">Órgãos</option>
                                            @foreach ($orgaos as $orgao)
                                                <option value=" {{$orgao->orgaoID}}"> {{$orgao->orgaoNome}} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input style="resize:none; width: 175px;" placeholder="Data Diário" class="form-control" type="text" onfocus="checarData()" onfocusout="checarData()" id="date">
                                        <input type="hidden" name="diario" value="tudo" id="diario">
                                    </td>
                                        {{-- compensar tamanho do filtro --}}
                                        <td style="resize:none; width: 100px;"></td>

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
                                        <td><input style="resize:none; width: 200px;" type="text" class="form-control" name="titulo" placeholder="Título"></td>
                                        <td>
                                            <input style="resize:none; width: 200px;" placeholder="Data Diário" class="form-control" type="text" onfocus="checarData()" onfocusout="checarData()" id="date">
                                            <input type="hidden" name="diario" value="tudo" id="diario">
                                        </td>
                                        {{-- compensar tamanho do filtro --}}
                                        <td style="resize:none; width: 250px;"></td>
                                        {{-- compensar tamanho do filtro --}}
                                        <td style="resize:none; width: 250px;"></td>

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
                       <th>Título</th>
                       <th>Órgão</th>
                       <th>Enviado Por</th>
                       <th>Apagado Por</th>
                       <th>Diário</th>
                       <th>Ver</th>
                       </thead>
        <tbody>

         @foreach ($publicacoes as $publicacao)

         @php
            $dataDiario = new DateTime($publicacao->diarioData);
            $dataDiario = $dataDiario->format('d/m/Y');

            $dataEnviado = new DateTime($publicacao->dataEnviado);
            $dataEnviado = $dataEnviado->format('d/m/Y à\s\ H:i');

            $dataApagado = new DateTime($publicacao->dataApagado);
            $dataApagado = $dataApagado->format('d/m/Y à\s\ H:i');
         @endphp

         <tr>

            <td>{{$publicacao->protocoloCompleto}}</td>
            <td> {{$publicacao->titulo}} </td>
            <td> {{$publicacao->orgaoNome}} </td>
            <td style="text-transform:capitalize;"> {{$publicacao->nomeUsuarioCriou}} em {{$dataEnviado}} </td>
            <td style="text-transform:capitalize;"> {{$publicacao->nomeUsuarioApagou}} em {{$dataApagado}} </td>
            <td>N° {{$publicacao->numeroDiario}} <br> {{$dataDiario}}</td>
            <td>  <a href='/publicacao/ver/{{$publicacao->protocoloCompleto}}' class="btn btn-dark" style="width:75px">Ver</a></td>
        </tr>


         @endforeach



        </tbody>

    </table>

    {{$publicacoes->links()}}

                </div>

            </div>
        </div>
    </div>


@endguest



@endsection
