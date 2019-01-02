@extends('layouts.app')
@section('content')

@auth

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


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> {{ __('Editar Matéria') }}</div>

                <div class="card-body">
                    <form id='formEditar' method="POST" action="{{ url("/tipodocumento/salvar") }}" enctype="multipart/form-data" >
                        @csrf

                        <input type="hidden" value="{{$tipoDocumento->tipoID}}" name="tipoID">

                        <div class="form-group row">
                            <label for="numero" class="col-md-4 col-form-label text-md-right">{{ __('Tipo Documento') }}</label>

                            <div class="col-md-6">
                                <input id="tipoDocumento" type="text" class="form-control{{ $errors->has('tipoDocumento') ? ' is-invalid' : '' }}" name="tipoDocumento" value="{{ $tipoDocumento->tipoDocumento }}" placeholder="nome da matéria" required autofocus>
                            </div>
                        </div>

                        <br>


                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>

                                    <!-- Verifica se existe alguma publicação neste tipo documento -->
                                    @if (sizeof($publicacoes) > 0 || sizeof($cadernos) > 0)

                                        <a style="color: white;" class="btn btn-primary" data-toggle="modal" data-target="#myModal{{$tipoDocumento->tipoID}}">
                                            {{ __('Editar Matéria') }}
                                        </a>

                                    @else

                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Editar Matéria') }}
                                        </button>

                                    @endif
                                </div>
                            </div>
                            <div style="float: left; margin-left:2%;">
                                <a style="color: white;" class="btn btn-primary" id="btnVoltar">
                                    Voltar
                                </a>
                            </div>
                        </div>

                        <!-- Se Existir Alguma Publicacão para este tipo Documento -->
                        @if (sizeof($publicacoes) > 0 || sizeof($cadernos) > 0)
                            <div class='modal fade' id="myModal{{$tipoDocumento->tipoID}}" role='dialog'>
                                <div class='modal-dialog row justify-content-center'>
                                    <div class="modal-content">
                                            <div class="modal-header">
                                                <Strong class=" offset-md-5" > ATENÇÃO </Strong>
                                            </div>
                                            <div class="modal-body">

                                                @if (sizeof($publicacoes) > 0)
                                                    <p> <b> Existem pedidos de publicações para esta matéria. </b> </p>
                                                    <p> Segue protocolos dos pedidos de publicações para esta matéria: </p>

                                                    @foreach ($publicacoes as $item)
                                                        <span> <i>{{$item->protocoloCompleto}}</i> <strong> / </strong> </span>
                                                    @endforeach
                                                @endif

                                                <br><br>

                                                @if (sizeof($cadernos) > 0)
                                                    <p> <b> Existem cadernos com esta matéria. </b> </p>
                                                    <p> Segue nome dos cadernos vinculados a esta matéria: </p>

                                                    @foreach ($cadernos as $item)
                                                        <span> <i>{{$item->cadernoNome}}</i> <strong> / </strong> </span>
                                                    @endforeach
                                                @endif

                                                <br><br>

                                                <p><strong>Deseja realmente Editar?</strong></p>

                                                <div>
                                                        <div style="float: left;" class="offset-md-3">
                                                            <div>
                                                                <button   class="btn btn-danger"  id="btnEditar">
                                                                        {{ __('Confirmar Edição') }}
                                                                </button>
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
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endauth



<script type="text/javascript">

    $(document).ready(function($) {

        $("#btnVoltar").click(function(){
            window.history.back();
        });

        $('#form').validate({
            errorClass: "my-error-class"
        });

        $( "#btnEditar" ).click(function() {
            $( "#formEditar" ).submit();
        });

    });
</script>

@endsection
