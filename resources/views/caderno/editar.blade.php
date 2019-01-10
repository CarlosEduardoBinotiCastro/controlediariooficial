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
                <div class="card-header"> {{ __('Editar Caderno') }}</div>

                <div class="card-body">
                    <form id='form' method="POST" action="{{ url("/caderno/salvar") }}" enctype="multipart/form-data" >
                        @csrf

                        <input type="hidden" value="{{$caderno->cadernoID}}" name="cadernoID">
                        <input type="hidden" value="" name="tipoDocumentos" id="tipoDocumentos">

                        <div class="form-group row">
                            <label for="cadernoNome" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                            <input id="cadernoNome" type="text" class="form-control{{ $errors->has('cadernoNome') ? ' is-invalid' : '' }}" name="cadernoNome" value="{{ $caderno->cadernoNome }}" placeholder="nome do caderno" required autofocus>
                            </div>
                        </div>

                        <br>


                        <div>
                            <div style="float: left;" class="offset-md-4">
                                <div>
                                    <button type="submit" style="display:none;">
                                        {{ __('Editar') }}
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btnCadastrar">
                                            {{ __('Editar Caderno') }}
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
<br><br>
<div class="container">
    <div class="row">



        <div class="col-md-12">

        <div class="row"> <a id="btnAdicionar" style="margin-left: 3%; margin-bottom: 2%; color: white;" class="btn btn-primary">Adicionar Matéria</a>
            <div class="col-md-4" >
                    <select class="custom-select mr-sm-2" name="idDocumentos" id="idDocumentos">
                    @foreach ($documentos as $documento)
                    <option value="{{$documento->tipoID}}">{{$documento->tipoDocumento}}</option>
                    @endforeach
                    </select>
                </div>
        </div>
        <br>
        <div class="table-responsive">


              <table id="mytable" class="table table-bordred table-striped">

                   <thead>
                   <th>Matéria</th>
                   <th>Remover</th>
                   </thead>
    <tbody>






    </tbody>

</table>


            </div>

        </div>
    </div>
</div>



@endauth


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">

    $(document).ready(function($) {

        $("#btnVoltar").click(function(){
            window.history.back();
        });

        $('#form').validate({
            errorClass: "my-error-class"
        });

        var documentosCaderno = <?php echo $documentosCaderno ?>;
        var documentosList = [];

        // Carregar as informações na tabela

        documentosCaderno.forEach(element => {
            var documento = {tipoID: "", tipoDocumento: ""};
            documento.tipoID = element.tipoID;
            documento.tipoDocumento = element.tipoDocumento;

            documentosList.push(documento);
            $('#mytable > tbody:last-child').append('<tr id="row'+documento.tipoID+'"> <td>' + documento.tipoDocumento + '</td>  <td> <a style="color: white;" class="btn btn-danger" onClick="remover(\''+documento.tipoDocumento+'\','+documento.tipoID+')" >Remover</a> </td> </tr>');
        });

        //


        $("#btnAdicionar").click(function(){

            var documentoAdd = $('#idDocumentos').find(":selected").text();

            if(documentoAdd != ''){

                var documento = {tipoID: "", tipoDocumento: ""};
                documento.tipoID = $('#idDocumentos').find(":selected").val();
                documento.tipoDocumento = $('#idDocumentos').find(":selected").text();


                documentosList.push(documento);

                $("#idDocumentos option:selected").remove();
                $('#mytable > tbody:last-child').append('<tr id="row'+documento.tipoID+'"> <td>' + documentoAdd + '</td>  <td> <a style="color: white;" class="btn btn-danger" onClick="remover(\''+documento.tipoDocumento+'\','+documento.tipoID+')" >Remover</a> </td> </tr>');

            }

        });


        remover = function(tipoDocumento, tipoID){
            var index = 0;

            $("#row"+tipoID+"").remove();

            $('select').append($('<option>', {
                value: tipoID,
                text: tipoDocumento
            }));

            documentosList.forEach(element => {
                if(element.tipoID == tipoID){
                    documentosList.splice(index, 1);
                }
                index++;
            });

        }


        $("#btnCadastrar").click(function (){
            var validar = true;
            var json = JSON.stringify(documentosList);
            $("#tipoDocumentos").val(json);

            var nome = $("#cadernoNome").val();

            if(validar){
                $("#form").find('[type="submit"]').trigger('click');
            }

        });

        $('#idDocumentos').select2();

    });
</script>

@endsection
