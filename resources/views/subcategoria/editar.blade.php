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
                <div class="card-header"> {{ __('Editar Subcategoria') }}</div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/subcategoria/salvar") }}" enctype="multipart/form-data" >
                        @csrf
                        <input type="hidden" name="subcategoriaID" value="{{$subcategoria->subcategoriaID}}">
                        <div class="form-group row">
                            <label for="tipoDocumento" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="subcategoriaNome" type="text" class="form-control{{ $errors->has('tipoDocumento') ? ' is-invalid' : '' }}" name="subcategoriaNome" value="{{ $subcategoria->subcategoriaNome }}" placeholder="nome da subcategoria" required autofocus>
                            </div>
                        </div>

                        <br>

                        <div class="form-group row">
                        <label for="users" class="col-md-4 col-form-label text-md-right">{{ __('Matéria') }}</label>
                        <div class="col-md-6">
                                <select name="tipoID" id="tipoID" class="form-control">
                                        @foreach($documentos as $documento)
                                        <option @if ($subcategoria->tipoID == $documento->tipoID) selected @endif value="{{ $documento->tipoID }}">{{ $documento->tipoDocumento }}</option>
                                        @endforeach
                                </select>
                        </div>

                        </div>

                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Editar Subcategoria') }}
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">

    $(document).ready(function($) {

        $('#form').validate({
            errorClass: "my-error-class"
        });

        $("#btnVoltar").click(function(){
            window.history.back();
        });

        $('#tipoID').select2();
    });
</script>

@endsection
