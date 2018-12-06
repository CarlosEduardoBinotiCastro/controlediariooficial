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
            @endif
            </div>
</div>


<br/><br/>
<div class="container">
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Lista de Feriados / Pontos Facultativos </strong> </h4>  <a style="margin-left:auto" href='/diasnaouteis/cadastrar' class="btn btn-success">Cadastrar</a> </div> <br>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>

                       <th>Data</th>
                       <th>Descrição</th>
                       <th>Editar</th>
                       <th>Apagar</th>
                       </thead>
        <tbody>

         @foreach ($diasNaoUteis as $dia)

            @php
                $data = new DateTime($dia->diaNaoUtilData);
                $data = $data->format('d/m/Y');

                if($dia->diaNaoUtilData < date('Y-m-d')){
                    $dataPassada = true;
                }else{
                    $dataPassada = false;
                }

            @endphp

         <tr>

            <td>{{$data}}</td>
            <td>{{$dia->diaDescricao}}</td>
            <td> @if(!$dataPassada) <a href='/diasnaouteis/editar/{{$dia->diaID}}' class="btn btn-primary">Editar</a> @endif</td>
            <td> @if(!$dataPassada) <a href='/diasnaouteis/deletar/{{$dia->diaID}}' class="btn btn-danger">Deletar</a> @endif</td>
        </tr>

         @endforeach



        </tbody>

    </table>

    {{$diasNaoUteis->links()}}

                </div>

            </div>
        </div>
    </div>



@endguest



@endsection
