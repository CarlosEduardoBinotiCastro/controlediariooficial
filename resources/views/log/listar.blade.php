@extends('layouts.app')

@section('content')

@guest



@else




{{-- <div id="Sucesso" class="container">
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
</div> --}}


<br/><br/>
<div class="container">
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Logs do Sistema </strong> </h4> <br> <br>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>
                       <th>Data</th>
                       <th>Descrição</th>
                       </thead>
        <tbody>

         @foreach ($logs as $log)

            @php
                $data = new DateTime($log->logData);
                $data = $data->format('d/m/Y H:i:s');
            @endphp

         <tr>

            <td style="width:20%">{{$data}}</td>
            <td style="width:80%"> {{$log->logDescricao}} </td>

        </tr>

         @endforeach



        </tbody>

    </table>

    {{$logs->links()}}

                </div>

            </div>
        </div>
    </div>


@endguest



@endsection
