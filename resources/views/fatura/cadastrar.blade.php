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
<br>

<div class="container" id="body">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header"> {{ __('Cadastrar Fatura') }}</div>

                <div class="card-body">

                        <div id="divLimite" style="display:none;">
                                <br>
                                <h4 id="textoLimite" style="text-align:center; color:red;">Texto</h4>
                                <br>
                        </div>

                    <form id='form' method="POST" action="{{ url("/fatura/formatar") }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="form-group row">
                            <label for="requisitante" class="col-md-4 col-form-label text-md-right">{{ __('Requisitante') }} <span style="color:red;">*</span> </label>

                            <div class="col-md-6">
                                <input  id="requisitante" type="text" class="form-control{{ $errors->has('requisitante') ? ' is-invalid' : '' }}" name="requisitante" value="{{ old('requisitante') }}" placeholder="Nome do requisitante" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="empresa" class="col-md-4 col-form-label text-md-right">{{ __('Empresa') }} <span style="color:red;">*</span></label>

                            <div class="col-md-6">
                                <input  id="empresa" type="text" class="form-control{{ $errors->has('empresa') ? ' is-invalid' : '' }}" name="empresa" value="{{ old('empresa') }}" placeholder="Nome da empresa" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('CPF / CNPJ') }} <span style="color:red;">*</span></label>

                            <div class="col-md-2">
                                <select  class="custom-select mr-sm-2" name="tipoDoc" id="tipoDoc">
                                <option slected value="CPF">CPF</option>
                                <option value="RG">CNPJ</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <input  id="numeroDoc" type="text" class="form-control" name="cpfCnpj" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="observacao" class="col-md-4 col-form-label text-md-right">{{ __('Observação') }}</label>
                            <div class="col-md-6">
                                <textarea  id="observacao" name="observacao" cols="60" rows="4" class="form-control" placeholder="Entre com as Observações!" style="resize: none;" value="{{old('observacao')}}">{{old('observacao')}}</textarea>
                            </div>
                        </div>
                        <br>

                        <div class="form-group row">
                            <label for="largura" class="col-md-4 col-form-label text-md-right">{{ __('Largura da Coluna (cm)') }}</label>

                            <div class="col-md-6">
                                <input id="largura" type="text" class="form-control{{ $errors->has('largura') ? ' is-invalid' : '' }}" name="largura" value="{{$config->largura}}"  disabled autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="valorColuna" class="col-md-4 col-form-label text-md-right">{{ __('Valor da Coluna (reais)') }}</label>

                            <div class="col-md-6">
                                <input id="valorColuna" type="text" class="form-control{{ $errors->has('valorColuna') ? ' is-invalid' : '' }}" name="valorColuna" value="{{$config->valorColuna}}"  disabled autofocus>
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="tipoID" class="col-md-4 col-form-label text-md-right">{{ __('Matéria') }} <span style="color:red;">*</span></label>

                            <div class="col-md-6">
                                <select  class="custom-select mr-sm-2" name="tipoID" id="tipoID" onchange="carregarSubcategorias()">
                                <option slected value="">Selecione a Matéria</option>
                                    @foreach ($documentos as $item)
                                        <option value="{{$item->tipoID}}"> {{$item->tipoDocumento}} </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="form-group row">

                            <label for="subcategoriaID" class="col-md-4 col-form-label text-md-right">{{ __('Subcategoria') }} <span style="color:red;">*</span></label>

                            <div class="col-md-6">
                                <select  class="custom-select mr-sm-2" name="subcategoriaID" id="subcategoriaID">
                                    <option slected value="">Selecione a Subcategoria</option>
                                    <option  value="NaoPossui">Não Possui</option>
                                </select>
                            </div>

                        </div>


                            <br>

                            <div class="col-md-6 offset-md-4">

                                    <div class="col-md-12" style="text-align:justify;">
                                            <input id="termo" type="checkbox" name="termos" value="concordo" > <strong> Aceito e me responsabilizo pelos termos contidos na <a href="http://ioes.dio.es.gov.br/js/tinymce/plugins/responsivefilemanager/source/Instru%C3%A7%C3%A3o%20Normativa%20001-2016.pdf" style="color:blue;">Instrução Normativa DIO/ES nº 001/2016</a> , publicada no D.O. do dia 02 de Maio de 2016. </strong>
                                        </div>

                            </div>


                            <br>
                                <div class="form-group row offset-md-6">
                                    <a href="/fatura/gerarTemplate" class="btn btn-primary">Template</a><a style="color:red; margin-left:2%" href="" data-toggle="modal" data-target="#modalLegenda" ><i class="fas fa-question-circle fa-2x"></i></a>
                                </div>
                                <div class="form-group row offset-md-3">
                                    <p><strong style="color:red; font-size:20px;">Lembre que você deve usar o template citado acima!!</strong> </p>
                                </div>

                            <div class=" row col-md-8 offset-md-2">

                                {{-- Relecionado com o lado do input --}}

                                    <div class="col-md-8">
                                    <br>
                                    <input type="file" class="form-control-file" name="arquivo" id="file" required>
                                    <strong><sub style="font-size:90%;">Somente arquivos nas extensão 'DOCX'. <br>
                                    Tamanho máximo: 30 MB</sub></strong>
                                </div>

                                {{-- Relecionado com o lado do botão --}}

                                    <div class="col-md-4" id="divBotao">
                                        <br>
                                        <input id="btnEnviar" type="submit" class="btn btn-success" value="Formatar Fatura">
                                    </div>

                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class='modal fade' id="modalLegenda" role='dialog'>
        <div class='modal-dialog row justify-content-center'>
            <div class="modal-content">

                    <div class="modal-body">

                        <p> <strong> Template de Arquivo: </strong> </p>
                        <p>Para formatar a fatura é necessário fazer o download do template e passar o conteúdo para o arquivo baixado. Dessa forma, a possibilidade de existir algum erro é reduzida.
                        </p>

                        <div>
                                <div >
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        Voltar
                                    </button>
                                </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <div class="container" id="carregando" style="display:none;">
        <br><br>
        <h2 class="offset-md-4"> Carregando Solicitação </h2>
        <br>
        <div class="loader offset-md-5"></div>
    </div>


@endauth

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>

<script type="text/javascript">

    $(document).ready(function($) {

        $("#tipoID").val("");

        $('#tipoID').select2();

        $('#form').validate({
            errorClass: "my-error-class"
        });

        $('#file').val("");

        var canUpload = false;
        $('#file').bind('change', function() {
            if( ((this.files[0].size / 1024)/1024) > 30){
                canUpload = false;
            }else{
                canUpload = true;
            }
        });


        $("#numeroDoc").attr('maxlength',11);
        $("#numeroDoc").mask('000.000.000-00', {reverse: true});

        $("#termo").prop('checked', false);
        $("#btnEnviar").prop('disabled', true);
        // $("#diario").val("");

        $("#subcategoriaID").val("");

        // var diariosDiasLimites = <?php // echo $diarioDatas; ?>;
        var subcategorias = <?php  echo $subcategorias; ?>;

        $(document).on('change','#tipoDoc',function(){
        if($("#tipoDoc").val() == 'CPF'){
            $("#numeroDoc").val(null);
            $("#numeroDoc").attr('maxlength',11);
            $("#numeroDoc").mask('000.000.000-00', {reverse: true});
        }else{
            $("#numeroDoc").val(null);
            $("#numeroDoc").attr('maxlength',15);
            $("#numeroDoc").mask('00.000.000/0000-00', {reverse: true});

        }
        });

         carregarSubcategorias = function(){
            var selected = $("#tipoID").val();

            if(selected != ""){

                $("#subcategoriaID").empty();
                $("#subcategoriaID").append('<option selected value="">Escolha a Subcategoria</option>');
                $("#subcategoriaID").append('<option value="NaoPossui">Não Possui</option>');

                subcategorias.forEach(element => {

                    if(element.tipoID == selected){
                        $("#subcategoriaID").append('<option value="'+element.subcategoriaID+'">'+element.subcategoriaNome+'</option>');
                    }
                });
            }else{
                $("#documentoSelect").empty();
                $("#documentoSelect").append('<option selected value="">Escolha a Subcategoria</option>');
                $("#subcategoriaID").append('<option value="NaoPossui">Não Possui</option>');
            }
        }

        $("#form" ).submit(function( event ) {
            if($("#form").valid()){
                if(canUpload == true){
                    $("#numeroDoc").unmask();
                    $('html, body').animate({scrollTop: '0px'}, 300);
                    $("#carregando").css('display', 'block');
                    $("#body").css('display', 'none');
                    $('#Erro').css('display', 'none');
                }else{
                    event.preventDefault();
                    alert("Upload somente de arquivos até 30 MB!");
                }

            }
        });


         $("#termo").click(function () {
            if($("#btnEnviar").is(":disabled")){
                $("#btnEnviar").attr('disabled', false);
            }else{
                $("#btnEnviar").attr('disabled', true);
            }
        });

    });
</script>

@endsection
