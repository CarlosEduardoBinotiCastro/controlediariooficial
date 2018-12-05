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
                <div class="card-header"> {{ __('Cadastrar Diário Oficial') }}</div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/diariodata/salvar") }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="form-group row">
                            <label for="numero" class="col-md-4 col-form-label text-md-right">{{ __('Número') }}</label>

                            <div class="col-md-6">
                                <input id="numero" type="text" class="form-control{{ $errors->has('numero') ? ' is-invalid' : '' }}" name="numeroDiario" value="{{ old('numeroDiario') }}" placeholder="número do diário" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Data Publicação') }}</label>
                            <div class="col-md-6">
                                <input id="diarioData" type="date" class="form-control" name="diarioData" value="{{ old('telefone') }}" min="" required>
                            </div>
                        </div>
                        <br>


                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Cadastrar Diario') }}
                                    </button>
                                </div>
                            </div>
                            <div style="float: left; margin-left:2%;">
                                <a style="color: white;" class="btn btn-primary" id="btnVoltar">
                                    Voltar
                                </a>
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
        document.getElementsByName("diarioData")[0].setAttribute('min', today);

    });
</script>

@endsection
