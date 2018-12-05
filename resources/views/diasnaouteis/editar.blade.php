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

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> {{ __('Editar Dia Não Útil') }}</div>

                <div class="card-body">
                    <form id='formEditar' method="POST" action="{{ url("/diasnaouteis/salvar") }}" enctype="multipart/form-data" >
                        @csrf

                        <input type="hidden" value="{{$diaNaoUtil->diaID}}" name="diaID">

                        <div class="form-group row">
                            <label for="numero" class="col-md-4 col-form-label text-md-right">{{ __('Descrição') }}</label>

                            <div class="col-md-6">
                                <input id="descricao" type="text" class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" name="diaDescricao" value="{{ $diaNaoUtil->diaDescricao }}" placeholder="descrição do dia" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Data') }}</label>
                            <div class="col-md-6">
                                <input id="diaNaoUtilData" type="date" class="form-control" name="diaNaoUtilData" value="{{ $diaNaoUtil->diaNaoUtilData }}" min="" required>
                            </div>
                        </div>
                        <br>

                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>
                                    <input type="submit" value="Confirmar Edição" class="btn btn-primary"  id="btnEditar">
                                </div>
                            </div>
                            <div style="float: left; margin-left:2%;">
                                <button type="button" class="btn btn-primary" id="btnVoltar">
                                    Voltar
                                </button>
                            </div>
                        </div>

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
        document.getElementsByName("diaNaoUtilData")[0].setAttribute('min', today);

    });
</script>

@endsection
