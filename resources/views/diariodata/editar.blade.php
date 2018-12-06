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
                <div class="card-header"> {{ __('Editar Diário Oficial') }}</div>

                <div class="card-body">
                    <form id='formEditar' method="POST" action="{{ url("/diariodata/salvar") }}" enctype="multipart/form-data" >
                        @csrf

                        <input type="hidden" value="{{$diarioData->diarioDataID}}" name="diarioDataID">

                        <div class="form-group row">
                            <label for="numero" class="col-md-4 col-form-label text-md-right">{{ __('Número') }}</label>

                            <div class="col-md-6">
                                <input id="numero" type="text" class="form-control{{ $errors->has('numero') ? ' is-invalid' : '' }}" name="numeroDiario" value="{{ $diarioData->numeroDiario }}" placeholder="número do diário" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Data Publicação') }}</label>
                            <div class="col-md-6">
                                <input id="diarioData" type="date" class="form-control" name="diarioData" value="{{ $diarioData->diarioData }}" min="" required>
                            </div>
                        </div>
                        <br>


                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>

                                    <!-- Verifica se existe alguma publicação nesta data -->
                                    @if (sizeof($publicacoes) > 0)

                                        <a style="color: white;" class="btn btn-primary" data-toggle="modal" data-target="#myModal{{$diarioData->diarioDataID}}">
                                            {{ __('Editar Diario') }}
                                        </a>

                                    @else

                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Editar Diario') }}
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

                        <!-- Se Existir Alguma Publicacão para esta data -->
                        @if (sizeof($publicacoes) > 0)
                            <div class='modal fade' id="myModal{{$diarioData->diarioDataID}}" role='dialog'>
                                <div class='modal-dialog row justify-content-center'>
                                    <div class="modal-content">
                                            <div class="modal-header">
                                                <Strong class=" offset-md-5" > ATENÇÃO </Strong>
                                            </div>
                                            <div class="modal-body">

                                                <p> Existem pedidos de publicações para este diário. </p>
                                                <p> Segue protocolos dos pedidos de publicações para este diário: </p>

                                                @foreach ($publicacoes as $item)
                                                    <span> <i>{{$item->protocoloCompleto}}</i> <strong> / </strong> </span>
                                                @endforeach

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

        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("diarioData")[0].setAttribute('min', today);


        $( "#btnEditar" ).click(function() {
            $( "#formEditar" ).submit();
        });

    });
</script>

@endsection
