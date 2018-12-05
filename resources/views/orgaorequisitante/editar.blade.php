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
                <div class="card-header"> {{ __('Editar Órgão Requisitante') }}</div>

                <div class="card-body">
                    <form id='formEditar' method="POST" action="{{ url("/orgaorequisitante/salvar") }}" enctype="multipart/form-data" >
                        @csrf

                        <input type="hidden" value="{{$orgaoRequisitante->orgaoID}}" name="orgaoID">

                        <div class="form-group row">
                            <label for="numero" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="orgaoNome" type="text" class="form-control{{ $errors->has('orgaoNome') ? ' is-invalid' : '' }}" name="orgaoNome" value="{{ $orgaoRequisitante->orgaoNome }}" placeholder="nome do órgão" required autofocus>
                            </div>
                        </div>

                        <br>


                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>

                                    <!-- Verifica se existe alguma publicação neste orgao requisitante-->
                                    @if (sizeof($usuarios) > 0)

                                        <a style="color: white;" class="btn btn-primary" data-toggle="modal" data-target="#myModal{{$orgaoRequisitante->orgaoID}}">
                                            {{ __('Editar Órgão Requisitante') }}
                                        </a>

                                    @else

                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Editar Órgão Requisitante') }}
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

                        <!-- Se Existir Alguma Publicacão para este orgao requisitante -->
                        @if (sizeof($usuarios) > 0 )
                            <div class='modal fade' id="myModal{{$orgaoRequisitante->orgaoID}}" role='dialog'>
                                <div class='modal-dialog row justify-content-center'>
                                    <div class="modal-content">
                                            <div class="modal-header">
                                                <Strong class=" offset-md-5" > ATENÇÃO </Strong>
                                            </div>
                                            <div class="modal-body">

                                                @if (sizeof($usuarios) > 0)
                                                    <p> <b> Existem usuários ligados a este órgão requisitante </b> </p>
                                                    <p> Segue nomes dos usuários: </p>

                                                    @foreach ($usuarios as $item)
                                                        <span> <i>{{$item->name}}</i> <strong> / </strong> </span>
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
