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

<div id="check" class="container">
    <div class="col-md-8 offset-md-2">
        @if(session()->has('login'))
            <br>
            <div class="form-group row mb-0 alert alert-primary" style="font-size:20px">
                {{ session()->get('login') }}
            </div>
        @endif
        </div>
</div>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> <strong> {{ __('Cadastrar Usuário') }} </strong> </div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/usuario/salvar") }}" enctype="multipart/form-data" >
                        @csrf

                        <input type="hidden" name="cadernos" id="cadernos" value="">
                        <input type="hidden" name="usuarioID" id="usuarioID" value="{{$usuario->id}}">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }} <span style="color:red;">*</span></label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $usuario->name }}" minlength="6" placeholder="nome do usuário" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="login" class="col-md-4 col-form-label text-md-right">{{ __('Login') }} <span style="color:red;">*</span></label>

                                <div class="col-md-6">
                                    <input id="login" type="text" class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}" name="login" value="{{ $usuario->login }}" placeholder="login do usuário" minlength="4" maxlength="20" required autofocus>
                                </div>
                            </div>

                            <br>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right"> <strong> {{ __('Alterar Senha ?') }} </strong> </label>

                            <div class="col-md-4">
                                <input id="alterarSenha" type="checkbox" name="alterarSenha" value="sim"> SIM
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="senha" class="col-md-4 col-form-label text-md-right"><strong> {{ __('Nova Senha') }} </strong></label>

                                <div class="col-md-6">
                                    <input id="senha" type="password" class="form-control{{ $errors->has('senha') ? ' is-invalid' : '' }}" name="senha" value="" minlength="6" required autofocus>
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="confirmarSenha" class="col-md-4 col-form-label text-md-right"><strong> {{ __('Confirmar Nova Senha') }} </strong></label>

                                <div class="col-md-6">
                                    <input id="confirmarSenha" type="password" class="form-control{{ $errors->has('confirmarSenha') ? ' is-invalid' : '' }}" name="confirmarSenha" value="" minlength="6" required autofocus>
                                </div>
                        </div>

                        <br>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }} <span style="color:red;">*</span></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $usuario->email }}" minlength="7" placeholder="exemplo@gmail.com" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="cpf" class="col-md-4 col-form-label text-md-right">{{ __('CPF') }} <span style="color:red;">*</span></label>

                                <div class="col-md-6">
                                    <input id="cpf" type="text" class="form-control{{ $errors->has('cpf') ? ' is-invalid' : '' }}" name="cpf" value="{{ $usuario->cpf }}" minlength="11" placeholder="XXX.XXX.XXX-XX" required autofocus>
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="telefoneSetor" class="col-md-4 col-form-label text-md-right">{{ __('Telefone Setor') }} <span style="color:red;">*</span></label>

                                <div class="col-md-6">
                                    <input id="telefoneSetor" type="text" class="form-control{{ $errors->has('telefoneSetor') ? ' is-invalid' : '' }}" name="telefoneSetor" value="{{ $usuario->telefoneSetor}}" placeholder="(XX)XXXX-XXXX" minlength="10" required autofocus>
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="telefoneCelular" class="col-md-4 col-form-label text-md-right">{{ __('Telefone Celular') }} <span style="color:red;">*</span></label>
                                <div class="col-md-6">
                                    <input id="telefoneCelular" type="text" class="form-control{{ $errors->has('telefoneCelular') ? ' is-invalid' : '' }}" name="telefoneCelular" value="{{ $usuario->telefoneCelular }}" placeholder="(XX)XXXXX-XXXX" minlength="11" required autofocus>
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="orgaoID" class="col-md-4 col-form-label text-md-right">{{ __('Órgão Requisitante') }} <span style="color:red;">*</span></label>

                                <div class="col-md-6">
                                    <select class="custom-select mr-sm-2" @if (Gate::allows('administrador', Auth::user())) name="orgaoID" id="orgaoID" @else disabled @endif>

                                    @foreach ($orgaosRequisitantes as $orgao)
                                        <option  @if ($orgao->orgaoID == $usuario->orgaoID) selected @endif value="{{$orgao->orgaoID}}">{{$orgao->orgaoNome}}</option>
                                    @endforeach

                                    </select>
                                </div>

                        </div>

                        <div class="form-group row">
                                <label for="statusID" class="col-md-4 col-form-label text-md-right">{{ __('Status') }} <span style="color:red;">*</span></label>

                                <div class="col-md-6">
                                    <select class="custom-select mr-sm-2" @if (Gate::allows('administrador', Auth::user())) name="statusID" id="statusID" @else disabled @endif>
                                    <option @if ($usuario->statusID == 1) selected @endif value="1">Ativo</option>
                                    <option @if ($usuario->statusID == 2) selected @endif value="2">Inativo</option>
                                    </select>
                                </div>

                        </div>

                        <div class="form-group row">
                                <label for="grupoID" class="col-md-4 col-form-label text-md-right">{{ __('Grupo de Usuário') }} <span style="color:red;">*</span></label>

                                <div class="col-md-6">
                                    <select class="custom-select mr-sm-2"  @if (Gate::allows('administrador', Auth::user())) name="grupoID" id="grupoID" @else disabled @endif>
                                    <option @if ($usuario->grupoID == 1) selected @endif value="1">Administrador</option>
                                    <option @if ($usuario->grupoID == 2) selected @endif value="2">Usuário</option>
                                    <option @if ($usuario->grupoID == 3) selected @endif value="3">Fatura</option>
                                    <option @if ($usuario->grupoID == 4) selected @endif value="4">Publicador</option>
                                    </select>
                                </div>

                        </div>

                        <div class="form-group row">
                                <label for="horaEnvio" class="col-md-4 col-form-label text-md-right">{{ __('Horário de Envio') }} <span style="color:red;">*</span></label>

                                <div class="col-md-6">
                                <input id="horaEnvio" type="time" class="form-control{{ $errors->has('horaEnvio') ? ' is-invalid' : '' }}" value="{{ $usuario->horaEnvio }}" @if (Gate::allows('administrador', Auth::user())) name="horaEnvio"  @else disabled @endif required autofocus>
                                </div>
                        </div>

                        <br>

                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header"> <span> {{ __('Cadernos Deste Usuário') }} <span style="color:red;">*</span></span> </div>

                                            <div class="card-body">


                                                    <div class="row">



                                                            <div class="col-md-12">


                                                            @if (Gate::allows('administrador', Auth::user()))
                                                                <div class="row"> <a id="btnAdicionar" style="margin-left: 3%; margin-bottom: 2%; color: white;" class="btn btn-primary">Adicionar Caderno</a>
                                                                    <div class="col-md-4" >
                                                                            <select class="custom-select mr-sm-2" id="idCadernos">
                                                                            @foreach ($cadernos as $caderno)
                                                                            <option value="{{$caderno->cadernoID}}">{{$caderno->cadernoNome}}</option>
                                                                            @endforeach
                                                                            </select>
                                                                        </div>
                                                                </div>
                                                            @endif

                                                            <br>
                                                            <div class="table-responsive">


                                                                  <table id="mytable" class="table table-bordred table-striped">

                                                                       <thead>
                                                                       <th>Caderno</th>
                                                                       @if (Gate::allows('administrador', Auth::user())) <th>Remover</th> @endif
                                                                       </thead>
                                                        <tbody>



                                                        </tbody>

                                                    </table>


                                                                </div>

                                                            </div>
                                                        </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                        <br>

                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>
                                    <input type="submit" style="display:none;">

                                    <button type="button" class="btn btn-primary" id="btnCadastrar">
                                        {{ __('Editar Usuário') }}
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

<script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>

<script type="text/javascript">

    $(document).ready(function($) {

        var url = "<?php echo Session::get('urlVoltar') ?>";

        $("#btnVoltar").click(function(){
                location.replace(url);
        });

        var adm =  <?php  echo Auth::user()->grupoID ?> ;
        var usuarioCaderno = <?php  echo $usuarioCaderno ?> ;

        $("#alterarSenha").prop('checked', false);
        $("#senha").prop('disabled', true);
        $("#confirmarSenha").prop('disabled', true);


        $("#cpf").attr('maxlength',11);
        $("#cpf").mask('000.000.000-00', {reverse: true});

        $("#telefoneSetor").attr('maxlength',10);
        $("#telefoneSetor").mask('(00)0000-0000');

        $("#telefoneCelular").attr('maxlength',11);
        $("#telefoneCelular").mask('(00)00000-0000');


        $('#form').validate({
            errorClass: "my-error-class"
        });

        var cadernosList = [];

        // Carregar as informações na tabela

        usuarioCaderno.forEach(element => {
            var caderno = {cadernoID: "", cadernoNome: ""};
            caderno.cadernoID = element.cadernoID;
            caderno.cadernoNome = element.cadernoNome;
            cadernosList.push(caderno);
            $('#mytable > tbody:last-child').append('<tr id="row'+caderno.cadernoID+'"> <td>' + caderno.cadernoNome + '</td>  @if (Gate::allows("administrador", Auth::user())) <td> <a style="color: white;" class="btn btn-danger" onClick="remover(\''+caderno.cadernoNome+'\','+caderno.cadernoID+')" >Remover</a> </td> @endif </tr>');
        });

        //




        $("#btnAdicionar").click(function(){
            if(adm == 1){

                var cadernoAdd = $('#idCadernos').find(":selected").text();



                if(cadernoAdd != ''){


                    var caderno = {cadernoID: "", cadernoNome: ""};
                    caderno.cadernoID = $('#idCadernos').find(":selected").val();
                    caderno.cadernoNome = $('#idCadernos').find(":selected").text();


                    cadernosList.push(caderno);

                    $("#idCadernos option:selected").remove();
                    $('#mytable > tbody:last-child').append('<tr id="row'+caderno.cadernoID+'"> <td>' + cadernoAdd + '</td>  <td> <a style="color: white;" class="btn btn-danger" onClick="remover(\''+caderno.cadernoNome+'\','+caderno.cadernoID+')" >Remover</a> </td> </tr>');

                }
            }
        });


        remover = function(caderno, cadernoID){
            if(adm == 1){

                var index = 0;

                $("#row"+cadernoID+"").remove();

                $("#idCadernos").append($('<option>', {
                    value: cadernoID,
                    text: caderno
                }));

                cadernosList.forEach(element => {
                    if(element.cadernoID == cadernoID){
                        cadernosList.splice(index, 1);
                    }
                    index++;
                });

            }

        }


        $("#btnCadastrar").click(function (){

            var json = JSON.stringify(cadernosList);
            $("#cadernos").val(json);

            if($("#form").valid()){
                $("#cpf").unmask();
                $("#telefoneSetor").unmask();
                $("#telefoneCelular").unmask();
                $("#form").find('[type="submit"]').trigger('click');
            }


        });

        $(document).on('click','#alterarSenha',function(){
            if($("#senha").is(":disabled")){
                $("#senha").prop('disabled', false);
                $("#confirmarSenha").prop('disabled', false);
            }else{
                $("#senha").val(null);
                $("#confirmarSenha").val(null);
                $("#senha").prop('disabled', true);
                $("#confirmarSenha").prop('disabled', true);
            }
        });

        $('#login').keypress( function(e){
            if(e.keyCode == 32){
                return false;
            }
        });

        $('#senha').keypress( function(e){
            if(e.keyCode == 32){
                return false;
            }
        });

        $('#confirmarSenha').keypress( function(e){
            if(e.keyCode == 32){
                return false;
            }
        });

    });
</script>

@endsection
