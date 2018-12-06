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


                                <p> Segue protocolos dos pedidos de publicações para este diário: </p>

                                @foreach (session()->get('publicacoes') as $item)
                                    <span> <i>{{$item->protocoloCompleto}}</i> <strong> / </strong> </span>
                                @endforeach


                    </div>
                @endif
            @endif
            </div>
</div>


<br/><br/>
<div class="container">
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Lista de Diarios Oficiais </strong> </h4>  <a style="margin-left:auto" href='/diariodata/cadastrar' class="btn btn-success">Cadastrar</a> </div> <br>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>

                       <th>Data</th>
                       <th>Número</th>
                       <th>Editar</th>
                       <th>Apagar</th>
                       </thead>
        <tbody>

         @foreach ($diariosDatas as $diario)

            @php
                $data = new DateTime($diario->diarioData);
                $data = $data->format('d/m/Y');

                if($diario->diarioData < date('Y-m-d')){
                    $dataPassada = true;
                }else{
                    $dataPassada = false;
                }

            @endphp

         <tr>

            <td>{{$data}}</td>
            <td>{{$diario->numeroDiario}}</td>
            <td> @if(!$dataPassada) <a href='/diariodata/editar/{{$diario->diarioDataID}}' class="btn btn-primary">Editar</a> @endif</td>
            <td> @if(!$dataPassada) <a href='/diariodata/deletar/{{$diario->diarioDataID}}' class="btn btn-danger">Deletar</a> @endif</td>
        </tr>

         @endforeach



        </tbody>

    </table>

    {{$diariosDatas->links()}}

                </div>

            </div>
        </div>
    </div>



@endguest



@endsection
