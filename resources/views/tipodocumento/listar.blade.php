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
<div class="container">
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Lista de Tipos Documentos </strong> </h4>  <a style="margin-left:auto" href='/tipodocumento/cadastrar' class="btn btn-success">Cadastrar</a> </div> <br>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>
                       <th>Tipo Documento</th>
                       <th>Editar</th>
                       <th>Apagar</th>
                       </thead>
        <tbody>

         @foreach ($tiposDocumentos as $documento)


         <tr>

            <td>{{$documento->tipoDocumento}}</td>
            <td> <a href='/tipodocumento/editar/{{$documento->tipoID}}' class="btn btn-primary">Editar</a></td>
            <td>  <button class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$documento->tipoID}}">Deletar</button> </td>
        </tr>




        <!-- Se Existir Alguma Publicacão para este tipo Documento -->

        <div class='modal fade' id="myModal{{$documento->tipoID}}" role='dialog'>
            <div class='modal-dialog row justify-content-center'>
                <div class="modal-content">
                        <div class="modal-header">
                            <Strong class=" offset-md-5" > ATENÇÃO </Strong>
                        </div>
                        <div class="modal-body">

                            <p> <b> Se existirem cadernos com este tipo documento, o tipo documento será removido do caderno </b> </p>

                            <br><br>

                            <p><strong>Deseja realmente Deletar?</strong></p>

                            <div>
                                    <div style="float: left;" class="offset-md-3">
                                        <div>
                                            <a  style="color:white;" class="btn btn-danger"  href='/tipodocumento/deletar/{{$documento->tipoID}}'>
                                                    {{ __('Confirmar Deletar') }}
                                            </a>
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




         @endforeach



        </tbody>

    </table>

    {{$tiposDocumentos->links()}}

                </div>

            </div>
        </div>
    </div>


@endguest



@endsection
