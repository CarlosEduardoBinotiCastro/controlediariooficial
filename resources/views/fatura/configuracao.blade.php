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
                <div class="card-header"> {{ __('Configurações Fatura') }}</div>

                <div class="card-body">
                    <form id='formEditar' method="POST" action="{{ url("/fatura/salvarConfiguracao") }}" enctype="multipart/form-data" >
                        @csrf

                        <input type="hidden" value="{{$config->configID}}" name="configID">

                        <div class="form-group row">
                            <label for="valorColuna" class="col-md-4 col-form-label text-md-right">{{ __('Valor Coluna (reais)') }}</label>

                            <div class="col-md-6">
                                <input id="valorColuna" type="text" class="form-control{{ $errors->has('valorColuna') ? ' is-invalid' : '' }}" name="valorColuna" value="{{ $config->valorColuna }}" placeholder="Valor Coluna" required autofocus>
                            </div>
                        </div>

                        <br>

                        <div class="form-group row">
                            <label for="largura" class="col-md-4 col-form-label text-md-right">{{ __('Largura Coluna (centimetros)') }}</label>

                            <div class="col-md-6">
                                <input id="largura" type="text" class="form-control{{ $errors->has('largura') ? ' is-invalid' : '' }}" name="largura" value="{{ $config->largura }}" placeholder="Largura Coluna" required autofocus>
                            </div>
                        </div>

                        <br>

                        <div class="form-group row">


                                <label for="cadernoID" class="col-md-4 col-form-label text-md-right">{{ __('Caderno Das Faturas') }}</label>
                                <select class="custom-select col-md-6" name="cadernoID" id="cadernoID" required>
                                        <option slected value=""> Escolha o Caderno </option>
                                        @foreach ($cadernos as $item)
                                            <option @if ($item->cadernoID == $config->cadernoID) selected @endif value=" {{$item->cadernoID}} "> {{$item->cadernoNome}} </option>
                                        @endforeach
                                </select>

                        </div>

                        <br>

                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>

                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Salvar Configuração') }}
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

        $( "#btnEditar" ).click(function() {
            $( "#formEditar" ).submit();
        });

    });
</script>

@endsection
