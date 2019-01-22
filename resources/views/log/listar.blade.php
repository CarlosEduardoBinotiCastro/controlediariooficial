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


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pesquisar Logs</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <form action="{{url("log/chamarListar")}}" method="POST">
                            @csrf
                            <div style="float: left;" class="col-md-4">
                                    <label><strong>Descrição</strong></label>
                            </div>
                            <div style="float: left;" class="col-md-5">
                                <input class="form-control" type="text" name="descricao">
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
            <div class="row"> <h4> <strong> Logs do Sistema </strong> <a style="color:red;" href="" data-toggle="modal" data-target="#modalLegenda" ><i class="fas fa-question-circle"></i></a> </h4> <br> <br>
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

    <div class='modal fade' id="modalLegenda" role='dialog'>
        <div class='modal-dialog row justify-content-center'>
            <div class="modal-content">
                    <div class="modal-header">
                        <Strong class=" offset-md-5" > Logs </Strong>
                    </div>
                    <div class="modal-body">


                        <p style="font-size: 15px;"> Logs são registros de movimentações no sistema realizados pelos usuários. <br> O tempo de vida dos Logs no sistema são de 15 dias. Para alterar esse valor, entre em contato com o adiministrador do Banco de Dados.</p>


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


@endguest



@endsection
