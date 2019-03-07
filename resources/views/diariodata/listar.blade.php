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
                @if (session()->has('faturas'))
                    <div style="display: block;" class="form-group row mb-0 alert alert-danger" style="font-size:20px">

                                <p> Segue protocolos dos pedidos de faturas para este diário: </p>

                                @foreach (session()->get('faturas') as $item)
                                    <span> <i>{{$item->protocoloCompleto}}</i> <strong> / </strong> </span>
                                @endforeach

                    </div>
                @endif
            @endif
            </div>
</div>


<br/><br/>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pesquisar Diário</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <form action="{{url("diariodata/chamarListar")}}" method="POST">
                            @csrf

                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Diário</label>
                                <input class="form-control col-md-6" type="text" name="diario" placeholder="número do diário">
                            </div>

                            {{-- <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Inicial</label>
                                <input placeholder="Data Diário" class="form-control col-md-6" type="text" onfocus="checarDataBegin()" onfocusout="checarDataBegin()" id="dateBegin">
                            </div>

                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Final</label>
                                <input placeholder="Data Diário" class="form-control col-md-6" type="text" onfocus="checarDataEnd()" onfocusout="checarDataEnd()" id="dateEnd">
                            </div> --}}

                            <div class="col-md-3 offset-md-5">
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
            <div class="row"> <h4> <strong> Lista de Diários Oficiais </strong> </h4>  <a style="margin-left:auto" href='{{ url("/diariodata/cadastrar") }}' class="btn btn-success">Cadastrar</a> </div> <br>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>

                       <th>Data</th>
                       <th>Número</th>
                       <th>Editar</th>
                       <th>Apagar</th>
                       <th>Diário Publicado</th>
                       </thead>
        <tbody>

         @foreach ($diariosDatas as $diario)

            @php
                $modalAnexar = false;
                $modalDeletar = false;

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
            <td> @if(!$dataPassada) <a href='{{ url("/diariodata/editar") }}/{{$diario->diarioDataID}}' class="btn btn-primary">Editar</a> @endif</td>
            <td> @if(!$dataPassada) <a href='{{ url("/diariodata/deletar") }}/{{$diario->diarioDataID}}' class="btn btn-danger">Deletar</a> @endif</td>

            <td>
                @if ($diario->diarioPublicado != null)
                    <a href='{{ url("/diariodata/downloadDiario") }}/{{$diario->diarioDataID}}' class="btn btn-success">Download</a>

                    @php
                        $modalDeletar = true;
                    @endphp

                    <button data-toggle="modal" data-target="#modalRemover{{$diario->diarioDataID}}" class="btn btn-danger">X</button>
                @else

                    @if ($diario->diarioData <= date('Y-m-d'))

                    @php
                        $modalAnexar = true;
                    @endphp

                        <button data-toggle="modal" data-target="#modalAnexar{{$diario->diarioDataID}}" class="btn btn-primary">Anexar</button>
                    @else
                        <span> Não Publicado! </span>
                    @endif

                @endif
            </td>


        </tr>

        @if ($modalAnexar)
            <form action="{{ url("/diariodata/anexar") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="diarioDataID" value="{{$diario->diarioDataID}}">

                <div class='modal fade' id="modalAnexar{{$diario->diarioDataID}}" role='dialog'>
                        <div class='modal-dialog row justify-content-center'>
                            <div class="modal-content">
                                    <div class="modal-header">
                                        <Strong class=" offset-md-4" > Anexar Diário </Strong>
                                    </div>
                                    <div class="modal-body">

                                    <p> Selecione o arquivo referente a publicação do diário <strong style="text-transform:uppercase;">{{$diario->numeroDiario}} - {{$data}}</strong> </p>


                                        <input type="file" class="form-control-file" name="arquivo" id="file" required>
                                        <strong><sub style="font-size:90%;">Somente arquivos nas extensão 'PDF'.
                                        Tamanho máximo: 30 MB</sub></strong>

                                        <br><br>

                                        <div>
                                                <div style="float: left;" class="offset-md-3">
                                                    <div>
                                                        <input type="submit" class="btn btn-primary" name="publicar" value="Confirmar Anexar">
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


            @if ($modalDeletar)
            <form action="{{ url("/diariodata/remover") }}" method="POST">
                @csrf
                <input type="hidden" name="diarioDataID" value="{{$diario->diarioDataID}}">

                <div class='modal fade' id="modalRemover{{$diario->diarioDataID}}" role='dialog'>
                        <div class='modal-dialog row justify-content-center'>
                            <div class="modal-content">
                                    <div class="modal-header">
                                        <Strong class=" offset-md-3" > Remover Arquivo do Diário </Strong>
                                    </div>
                                    <div class="modal-body">

                                    <p> Deseja remover o arquivo referente a publicação do diário <strong style="text-transform:uppercase;">{{$diario->numeroDiario}} - {{$data}}</strong>? </p>


                                        <div>
                                                <div style="float: left;" class="offset-md-3">
                                                    <div>
                                                        <input type="submit" class="btn btn-danger" name="publicar" value="Remover">
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

    {{$diariosDatas->links()}}

                </div>

            </div>
        </div>
    </div>



@endguest

<script type="text/javascript">

    $(document).ready(function($) {

        var dataBegin = "tudo";
        var dataEnd = "tudo";

        checarDataBegin = function(){
            if($('#dateBegin').attr('type') == 'text'){
                $('#dateBegin').attr('type', 'date');
            }else{
                dataBegin = $('#dateBegin').val();
                var datas =  $('#dateBegin').val().split('-');
                var datanormal = datas[2]+'/'+datas[1]+'/'+datas[0];
                $('#dateBegin').attr('type', 'text');
                if($('#dateBegin').val() != ""){
                    $('#dateBegin').val(datanormal);
                }else{
                    dataBegin = "tudo";
                    $('#dateBegin').val("");
                }
            }
        }
        checarDataEnd = function(){
            if($('#dateEnd').attr('type') == 'text'){
                $('#dateEnd').attr('type', 'date');
            }else{
                dataEnd = $('#dateEnd').val();
                var datas =  $('#dateEnd').val().split('-');
                var datanormal = datas[2]+'/'+datas[1]+'/'+datas[0];
                $('#dateEnd').attr('type', 'text');
                if($('#dateEnd').val() != ""){
                    $('#dateEnd').val(datanormal);
                }else{
                    dataEnd = "tudo";
                    $('#dateEnd').val("");
                }
            }
        }

    });

</script>

@endsection


