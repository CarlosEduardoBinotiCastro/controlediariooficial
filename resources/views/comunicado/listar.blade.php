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
                @if (session()->has('usuarios'))
                    <div style="display: block;" class="form-group row mb-0 alert alert-danger" style="font-size:20px">

                        <p> Segue usuários cadastrados neste órgão: </p>

                        @foreach (session()->get('usuarios') as $item)
                            <span> <i>{{$item->name}}</i> <strong> / </strong> </span>
                        @endforeach

                    </div>
                @endif
            @endif
            </div>
</div>

<br>

<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Pesquisar Comunicado</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status')}}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <form action="{{url("comunicado/chamarListar")}}" method="POST">
                                @csrf
                                <div style="float: left;" class="col-md-2">
                                        <label><strong>Título</strong></label>
                                </div>
                                <div style="float: left;" class="col-md-7">
                                    <input class="form-control" type="text" name="tituloMensagem">
                                </div>
                                <div style="float: left;" class="col-md-3">
                                    <input class="btn btn-primary" type="submit" value="Pesquisar" name="enviar">
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



<br/><br/>
<div class="container">
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Lista de Comunicados </strong> </h4>  <a style="margin-left:auto" href='/comunicado/cadastrar' class="btn btn-success">Enviar Comunicado</a> </div> <br>
            <div class="table-responsive">

                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>
                       <th>Título</th>
                       <th>Enviado Por</th>
                       <th>Data de Envio</th>
                       <th>Editar</th>
                       <th>Apagar</th>
                       </thead>
        <tbody>

         @foreach ($comunicados as $comunicado)


         <tr>

            <td>{{$comunicado->tituloMensagem}}</td>
            <td style="text-transform:capitalize;">{{$comunicado->nomeUsuario}}</td>
            @php
                $data = new DateTime($comunicado->dataComunicado);
                $data = $data->format('d/m/Y H:i:s');
            @endphp
            <td> {{$data}} </td>
            <td> <a href='/comunicado/editar/{{$comunicado->comunicadoID}}' class="btn btn-primary">Editar</a></td>
            <td> <a href='/comunicado/deletar/{{$comunicado->comunicadoID}}' class="btn btn-danger">Deletar</a> </td>
        </tr>

         @endforeach



        </tbody>

    </table>

    {{$comunicados->links()}}

                </div>

            </div>
        </div>
    </div>


@endguest



@endsection
