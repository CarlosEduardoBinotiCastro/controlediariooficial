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
                <div class="card-header"> {{ __('Ver Comunicado') }}</div>

                <div class="card-body">


                        @php
                            $data = new DateTime($comunicado->dataComunicado);
                            $data = $data->format('d/m/Y H:i:s');
                        @endphp
                        <div class="form-group row ">
                            <label for="tituloMensagem" class="col-md-2 col-form-label text-md-right">{{ __('Enviado Em:') }}</label>
                            <div class="col-md-10">
                                <b class="form-control" style="border-color:transparent;"><span> {{$data}} </span></b>
                            </div>
                        </div>
                        <div class="form-group row ">
                                <label for="tituloMensagem" class="col-md-2 col-form-label text-md-right">{{ __('Enviado Por:') }}</label>

                                <div class="col-md-10">
                                    <b class="form-control" style="border-color:transparent;"><span> {{$comunicado->name}} </span></b>
                                </div>
                        </div>

                        <br>

                        <div class="form-group row ">
                                <label for="tituloMensagem" class="col-md-2 col-form-label text-md-right">{{ __('Título:') }}</label>

                                <div class="col-md-10" >
                                    <b class="form-control" style="border-color:transparent;"><span> {{$comunicado->tituloMensagem}} </span></b>
                                </div>
                        </div>

                        <div class="form-group row ">
                            <label for="mensagem" class="col-md-2 col-form-label text-md-right">{{ __('Mensagem:') }}</label>

                            <div class="col-md-10">
                                    <b class="form-control" style="border-color:transparent;"> <span> {{$comunicado->mensagem}} </span> </b>
                            </div>
                        </div>

                        <br>

                        <div>
                            <div style="float: left; margin-left:2%;">
                                <a style="color: white;" class="btn btn-primary" id="btnVoltar">
                                    Voltar
                                </a>
                            </div>
                        </div>
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

        $("#btnCadastrar").click(function (){
            var json = JSON.stringify(grupoList);
            $("#grupos").val(json);
            $("#form").find('[type="submit"]').trigger('click');
        });


    });
</script>

@endsection

