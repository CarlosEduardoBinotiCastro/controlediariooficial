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
                <div class="card-header"> {{ __('Enviar Comunicado') }}</div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/comunicado/salvar") }}" enctype="multipart/form-data" >
                        @csrf
                        <input type="hidden" value="" name="grupos" id="grupos">
                        <input type="hidden" value=" {{$comunicado->comunicadoID}} " name="comunicadoID">
                        <div class="form-group row ">
                            <div class="col-md-10 offset-md-1">
                                <p style="color:red"> <b> Ao editar esse comunicado, ele será reenviado. O usuário que enviou será alterado pelo usuário que editou e a data de envio será alterada pela data de edição. </b> </p>
                            </div>
                        </div>

                        <div class="form-group row ">
                                <label for="tituloMensagem" class="col-md-2 col-form-label text-md-right">{{ __('Título') }} <span style="color:red;">*</span></label>

                                <div class="col-md-6">
                                        <input  id="tituloMensagem" type="text" class="form-control{{ $errors->has('tituloMensagem') ? ' is-invalid' : '' }}" name="tituloMensagem" value="{{ old('tituloMensagem') }} {{$comunicado->tituloMensagem}} " placeholder="Título" required autofocus>
                                </div>
                            </div>

                        <div class="form-group row ">
                            <label for="mensagem" class="col-md-2 col-form-label text-md-right">{{ __('Mensagem') }} <span style="color:red;">*</span></label>

                            <div class="col-md-6">
                                    <textarea name="mensagem" cols="60" rows="5" class="form-control" placeholder="Conteúdo da Mensagem" style="resize: none;" value="{{old('mensagem')}} {{$comunicado->mensagem}}" required>{{old('mensagem')}} {{$comunicado->mensagem}} </textarea>
                            </div>
                        </div>



                        <div class="container">
                                <div class="row">

                                    <div class="col-md-12">

                                    <div class="row"> <a id="btnAdicionar" style="margin-left: 3%; margin-bottom: 2%; color: white;" class="btn btn-primary">Adicionar Grupo Destinatário</a>
                                        <div class="col-md-4" >
                                                <select class="custom-select mr-sm-2" id="grupoID">
                                                @foreach ($gruposUsuario as $grupo)
                                                <option value="{{$grupo->grupoID}}">{{$grupo->grupoDescricao}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                    </div>
                                    <br>
                                    <div class="table-responsive">

                                          <table id="mytable" class="table table-bordred table-striped">

                                               <thead>
                                               <th>Grupo</th>
                                               <th>Remover</th>
                                               </thead>

                                            <tbody>
                                            </tbody>

                                        </table>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        <br>


                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>
                                    <button id="btnCadastrar" class="btn btn-primary">
                                        {{ __('Editar Comunicado') }}
                                    </button>
                                </div>
                            </div>
                            <div style="float: left; margin-left:2%;">
                                <a style="color: white;" class="btn btn-primary" id="btnVoltar">
                                    Voltar
                                </a>
                            </div>
                            <button type="submit" style="display:none;">
                                    {{ __('Cadastrar') }}
                                </button>
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

        var grupoUsuarioComunicado = <?php  echo $grupoUsuarioComunicado ?>;
        console.log('aaeda');
        console.log(grupoUsuarioComunicado);
        var grupoList = [];

        grupoUsuarioComunicado.forEach(element => {
            var grupo = {grupoID: "", grupoDescricao: ""};
            grupo.grupoID = element.grupoID;
            grupo.grupoDescricao = element.grupoDescricao;

            grupoList.push(grupo);
            $('#mytable > tbody:last-child').append('<tr id="row'+grupo.grupoID+'"> <td>' + grupo.grupoDescricao + '</td>  <td> <a style="color: white;" class="btn btn-danger" onClick="remover(\''+grupo.grupoDescricao+'\','+grupo.grupoID+')" >Remover</a> </td> </tr>');
        });

        $("#btnAdicionar").click(function(){

            var documentoAdd = $('#grupoID').find(":selected").text();

            if(documentoAdd != ''){

                var grupo = {grupoID: "", grupoDescricao: ""};
                grupo.grupoID = $('#grupoID').find(":selected").val();
                grupo.grupoDescricao = $('#grupoID').find(":selected").text();


                grupoList.push(grupo);

                $("#grupoID option:selected").remove();
                $('#mytable > tbody:last-child').append('<tr id="row'+grupo.grupoID+'"> <td>' + documentoAdd + '</td>  <td> <a style="color: white;" class="btn btn-danger" onClick="remover(\''+grupo.grupoDescricao+'\','+grupo.grupoID+')" >Remover</a> </td> </tr>');

            }

        });


        remover = function(grupoDescricao, grupoID){
            var index = 0;

            $("#row"+grupoID+"").remove();

            $('select').append($('<option>', {
                value: grupoID,
                text: grupoDescricao
            }));

            grupoList.forEach(element => {
                if(element.grupoID == grupoID){
                    grupoList.splice(index, 1);
                }
                index++;
            });

        }


        $("#btnCadastrar").click(function (){
            var json = JSON.stringify(grupoList);
            $("#grupos").val(json);
            $("#form").find('[type="submit"]').trigger('click');
        });


    });
</script>

@endsection

