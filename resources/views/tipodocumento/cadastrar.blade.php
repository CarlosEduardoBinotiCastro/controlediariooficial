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
                <div class="card-header"> {{ __('Cadastrar Matéria') }}</div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/tipodocumento/salvar") }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="form-group row">
                            <label for="tipoDocumento" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="tipoDocumento" type="text" class="form-control{{ $errors->has('tipoDocumento') ? ' is-invalid' : '' }}" name="tipoDocumento" value="{{ old('tipoDocumento') }}" placeholder="nome da matéria" required autofocus>
                            </div>
                        </div>

                        <br>


                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Cadastrar Matéria') }}
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

        $('#form').validate({
            errorClass: "my-error-class"
        });

        $("#btnVoltar").click(function(){
            window.history.back();
        });


    });
</script>

@endsection
