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
        <div class="col-md-7rrrr">
            <div class="card">
                <div class="card-header"> {{ __('Fatura Formatada') }}</div>

                <div class="card-body">

                    <form id="form" action="{{ url('/fatura/salvar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="arquivoOriginal" value=" {{$fatura['arquivoOriginal']}} ">
                        <input type="hidden" name="arquivoFormatado" value=" {{$fatura['arquivoFormatado']}} ">
                        <input type="hidden" name="arquivoVisualizado" value=" {{$arquivoVisualizacao}} ">
                        <input type="hidden" name="subcategoriaID" value=" {{$fatura['subcategoriaID']}} ">
                        <input type="hidden" name="tipoID" value=" {{$fatura['tipoID']}} ">

                        <div class="form-group row">
                                <label for="requisitante" class="col-md-4 col-form-label text-md-right">{{ __('Requisitante') }}</label>

                                <div class="col-md-6">
                                    <input  id="requisitante" type="text" class="form-control{{ $errors->has('requisitante') ? ' is-invalid' : '' }}" name="requisitante" value="{{$fatura['requisitante']}}" readonly autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="empresa" class="col-md-4 col-form-label text-md-right">{{ __('Empresa') }}</label>

                                <div class="col-md-6">
                                    <input  id="empresa" type="text" class="form-control{{ $errors->has('empresa') ? ' is-invalid' : '' }}" name="empresa" value="{{$fatura['empresa']}}" readonly autofocus>
                                </div>
                            </div>



                            @if ($fatura['email'] != null)
                                <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('email') }}</label>

                                        <div class="col-md-6">
                                            <input  id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{$fatura['email']}}" readonly autofocus>
                                        </div>
                                </div>
                            @endif

                            @if ($fatura['telefoneFixo'] != null)
                                <div class="form-group row">
                                        <label for="telefoneFixo" class="col-md-4 col-form-label text-md-right">{{ __('Telefone Fixo') }}</label>

                                        <div class="col-md-6">
                                            <input  id="telefoneFixo" type="text" class="form-control{{ $errors->has('telefoneFixo') ? ' is-invalid' : '' }}" name="telefoneFixo" value="{{$fatura['telefoneFixo']}}" readonly autofocus>
                                        </div>
                                </div>
                            @endif

                            @if ($fatura['telefoneCelular'] != null)
                                <div class="form-group row">
                                        <label for="telefoneCelular" class="col-md-4 col-form-label text-md-right">{{ __('Telefone Celular') }}</label>

                                        <div class="col-md-6">
                                            <input  id="telefoneCelular" type="text" class="form-control{{ $errors->has('telefoneCelular') ? ' is-invalid' : '' }}" name="telefoneCelular" value="{{$fatura['telefoneCelular']}}" readonly autofocus>
                                        </div>
                                </div>
                            @endif



                            <div class="form-group row">
                                <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Documento') }}</label>
                                <div class="col-md-6">
                                <input  id="numeroDoc" type="text" class="form-control" name="cpfCnpj" value="{{$fatura['cpfCnpj']}}" readonly required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tipoDoc" class="col-md-4 col-form-label text-md-right">{{ __('Observação') }}</label>
                                <div class="col-md-6">
                                    <textarea  name="observacao" cols="60" rows="4" class="form-control" placeholder="Entre com as Observações!" style="resize: none;" value="{{$fatura['observacao']}}" readonly>{{$fatura['observacao']}}</textarea>
                                </div>
                            </div>
                            <br>

                            <div class="form-group row">
                                <label for="largura" class="col-md-4 col-form-label text-md-right">{{ __('Largura da Coluna (cm)') }}</label>

                                <div class="col-md-6">
                                    <input id="largura" type="text" class="form-control{{ $errors->has('largura') ? ' is-invalid' : '' }}" name="largura" value="{{$faturaConfig[0]->largura}}"  readonly autofocus>
                                </div>
                            </div>

                        <div class="form-group row">
                            <label for="valorColuna" class="col-md-4 col-form-label text-md-right">{{ __('Valor da Coluna (reais)') }}</label>

                            <div class="col-md-6">
                                <input id="valorColuna" type="text" class="form-control{{ $errors->has('valorColuna') ? ' is-invalid' : '' }}" name="valorColuna" value="{{$faturaConfig[0]->valorColuna}}"  readonly autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="centimetragem" class="col-md-4 col-form-label text-md-right">{{ __('Centimetragem') }}</label>

                                <div class="col-md-6">
                                    <input id="centimetragem" type="text" class="form-control{{ $errors->has('centimetragem') ? ' is-invalid' : '' }}" name="centimetragem" value=""  readonly autofocus>
                                </div>
                            </div>

                        <div class="form-group row">
                                <label for="valor" class="col-md-4 col-form-label text-md-right">{{ __('Valor da Fatura (reais)') }}</label>

                                <div class="col-md-6">
                                    <input id="valor" type="text" class="form-control{{ $errors->has('valor') ? ' is-invalid' : '' }}" name="valor" value=""  readonly autofocus>
                                </div>
                            </div>

                        <div class="form-group row">
                                <label for="Documento" class="col-md-4 col-form-label text-md-right">{{ __('Matéria') }}</label>

                                <div class="col-md-6">
                                <input id="documento" type="text" class="form-control{{ $errors->has('documento') ? ' is-invalid' : '' }}" name="documento" value="{{$fatura['tipoDocumento']}}"  readonly autofocus>
                                </div>
                            </div>

                        <div class="form-group row">
                                <label for="subcategoria" class="col-md-4 col-form-label text-md-right">{{ __('Subcategoria') }}</label>

                                <div class="col-md-6">
                                <input id="subcategoria" type="text" class="form-control{{ $errors->has('subcategoria') ? ' is-invalid' : '' }}" name="subcategoria" value="{{$fatura['subcategoriaNome']}}"  readonly autofocus>
                                </div>
                            </div>

                                <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <a href="{{ url("/fatura/downloadTemp") }}/{{$fatura['arquivoFormatado']}}" class="btn btn-primary">Download Arquivo Formatado</a>
                                        </div>
                                    </div>

                                <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <input type="submit" name="confirmar" value="Confirmar Fatura" class="btn btn-success">
                                        </div>
                                    </div>
                                <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                                <a id="btnVoltar" name="voltar" class="btn btn-danger" style="color:white;">Cancelar Fatura</a>
                                        </div>
                                    </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-md-5">
                <div class="card" style="text-align:center;"> {{-- Coluna da visualização  --}}
                        <div class="card-header"> {{ __('Visualização') }}</div>
                    <div class="card-body table-responsive">

                        {{-- RESPONSAVEL POR GERAR UM VISUALIZAÇÃO DO ARQUIVO FORMATADO --}}
                    @php
                    $size = 10;              // | Muda de acordo com a necessidade
                    $family = "Times";      // | Muda de acordo com a necessidade
                    @endphp

                    <div id="fatura" style="width:{{$faturaConfig[0]->largura}}cm; font-family:{{$family}}; line-height:{{$size+1}}pt; ">  {{-- Propriedade line height alterada 1 pt a mais que o pt da letra --}}
                            @php
                                $contaTexto = 0;
                                $paragrafo = false;
                               foreach ($formatada->getSections()[0]->getElements() as $txtRunOuTxt) {

                                   if(get_class($txtRunOuTxt) == "PhpOffice\PhpWord\Element\TextRun" ){

                                    if($paragrafo){

                                        echo '<p style="text-align:justify; font-size:'.$size.'pt; margin-top:-10px;">';

                                        foreach ($txtRunOuTxt->getElements() as $txt) {

                                            if($txt->fontStyle->bold){
                                                echo '<b>'.$txt->getText().'</b>';
                                            }else{
                                                echo $txt->getText();
                                            }

                                        }

                                        echo "</p>";

                                    }else{

                                        if($contaTexto == 0){

                                            echo '<div id="titulo">';
                                            echo '<p style="text-align:center; font-size:'.$size.'pt;">';

                                            foreach ($txtRunOuTxt->getElements() as $txt) {

                                                if($txt->fontStyle->bold){
                                                    echo '<b>'.$txt->getText().'</b>';
                                                }else{
                                                    echo $txt->getText();
                                                }

                                            }

                                            echo "</p>";
                                            echo "</div>";

                                        }else{

                                            echo '<p style="text-align:justify; font-size:'.$size.'pt;">';

                                            foreach ($txtRunOuTxt->getElements() as $txt) {

                                                if($txt->fontStyle->bold){
                                                    echo '<b>'.$txt->getText().'</b>';
                                                }else{
                                                    echo $txt->getText();
                                                }

                                            }

                                            echo "</p>";

                                            $paragrafo = true;

                                        }

                                    }

                                   }else{
                                       if($txtRunOuTxt->getText() == "" || $txtRunOuTxt->getText() == " "){
                                            // texto vazio de vez em quando
                                       }else{
                                        if($contaTexto == 0){

                                            echo '<div id="titulo">';
                                                echo '<p style="text-align:center; font-size:'.$size.'pt; font-weight:bold;">'.$txtRunOuTxt->getText()."</p>";
                                            echo '</div>';

                                            }else{
                                                if($txtRunOuTxt->fontStyle->bold){
                                                    if($paragrafo){
                                                        echo '<p style="text-align:justify; font-size:'.$size.'pt; margin-top:-10px; font-weight:bold;">'.$txtRunOuTxt->getText()."</p>";
                                                    }else{
                                                        echo '<p style="text-align:justify; font-size:'.$size.'pt; font-weight:bold;">'.$txtRunOuTxt->getText()."</p>";
                                                        $paragrafo = true;
                                                    }
                                                }else{

                                                    if($paragrafo){
                                                        echo '<p style="text-align:justify; font-size:'.$size.'pt; margin-top:-10px;">'.$txtRunOuTxt->getText()."</p>";
                                                    }else{
                                                        echo '<p style="text-align:justify; font-size:'.$size.'pt;">'.$txtRunOuTxt->getText()."</p>";
                                                        $paragrafo = true;
                                                    }
                                                }
                                            }
                                       }

                                   }

                                   $contaTexto += 1;
                               }
                            @endphp
                    </div>

                    {{-- FIM DA VISUALIZAÇÃO DO ARQUIVO FORMATADO --}}

                    <div>
                        <a href="{{ url("/fatura/visualizacaoTemp") }}/{{$arquivoVisualizacao}}" type="button" class="btn btn-primary col-md-12" data-dismiss="modal">
                            Arquivo Base Centimetragem
                        </a>
                    </div>

                    </div>
                </div>
            </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>

<script>

    $(document).ready(function(){

            $("#btnVoltar").click(function(){
                window.history.back();
            });

            var config = <?php echo $faturaConfig ?>;
            var centimetragem = <?php echo $centimetragem ?>;

            $("#centimetragem").val(centimetragem.toFixed(2));

            $("#valor").val(($("#centimetragem").val() * config[0].valorColuna).toFixed(2));

            if( $("#numeroDoc").val().length > 11 ){
                $("#numeroDoc").mask('00.000.000/0000-00', {reverse: true});
            }else{
                $("#numeroDoc").mask('000.000.000-00', {reverse: true});
            }

            $('#form').submit(function (e){
                $("#numeroDoc").unmask();
            });

    });

</script>
@endauth

@endsection
