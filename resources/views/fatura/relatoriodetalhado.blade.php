@extends('layouts.app')

@section('content')

<div id="Sucesso" class="container">
        <div class="col-md-8 offset-md-2">
            @if(session()->has('sucesso'))
                <br>
                <div class="form-group row mb-0 alert alert-success" style="font-size:20px">
                    {{ session()->get('sucesso') }}
                </div>
            @endif
            </div>
        </div>
</div>

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
</div>

<br/><br/>


<div class="container col-md-10">

        <br>
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Relatório de faturas detalhado </strong> </h4> </div> <br>


            <form id="formFiltro" action="{{url("fatura/relatorioDetalhadoFiltro")}}" method="POST">
            @csrf

            <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card" style="background-color:#DEDDDD; border-radius: 20px;">
                                <div class="card-header" style="text-align:center;"><strong>Período dos Envios</strong></div>

                                <div class="card-body">
                                    <div>
                                            <div class="form-group row offset-md-2">
                                                <label class="col-md-3 text-md-right">Data Inicial</label>

                                            {{-- Logica para pegar o primeiro dia do mês --}}
                                            <input style="resize:none; width: 150px;" placeholder="Data Diário" class="form-control" type="text" onfocus="checarDataBegin()" onfocusout="checarDataBegin()" id="dateBegin">
                                            <input type="hidden" name="dataInicial" value="tudo" id="dataInicial">

                                            </div>

                                            <div class="form-group row offset-md-2">
                                                    <label class="col-md-3 text-md-right">Data Final</label>
                                                    <input style="resize:none; width: 150px;" placeholder="Data Diário" class="form-control" type="text" onfocus="checarDataEnd()" onfocusout="checarDataEnd()" id="dateEnd">
                                                    <input type="hidden" name="dataFinal" value="tudo" id="dataFinal">
                                            </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            <div class="table-responsive">
                    <table class="table table-bordred table-striped" style="background-color:#DEDDDD; border-radius: 20px;">

                            <tbody>
                                <tr style="background-color:transparent;">
                                    <td style="border-color:transparent;"><input style="resize:none; width: 225px;" type="text" class="form-control" name="cpfCnpj" placeholder="N° CPF / CNPJ"></td>
                                    <td><input style="resize:none; width: 225px;" type="text" class="form-control" name="empresa" placeholder="Empresa"></td>
                                    <td><input style="resize:none; width: 225px;" type="text" class="form-control" name="protocolo" placeholder="Protocolo"></td>

                                    <td>
                                            <select style="resize:none; width: 200px;" class="custom-select" name="subcategoria" id="subcategoriaID" >
                                                    <option slected value="tudo">Subcategoria</option>
                                                    <option  value="NaoPossui">Não Possui</option>
                                                @foreach ($subcategorias as $subcategoria)
                                                    <option value=" {{$subcategoria->subcategoriaID}} "> {{$subcategoria->subcategoriaNome}} </option>
                                                @endforeach
                                            </select>
                                    </td>



                                    <td>
                                        <select style="resize:none; width: 100px;" class="custom-select" name="situacao" >
                                                <option slected value="tudo">Situação</option>
                                            @foreach ($situacoes as $situacao)
                                                @if ( ($situacao->situacaoNome == "Apagada") && (Gate::allows('publicador', Auth::user()) || Gate::allows('faturas', Auth::user())))

                                                @else
                                                    <option value=" {{$situacao->situacaoNome}} "> @if ($situacao->situacaoNome == "Aceita")
                                                        Paga
                                                        @else
                                                            @if ($situacao->situacaoNome == "Enviada")
                                                                Cadastrada
                                                            @else
                                                                {{$situacao->situacaoNome}}
                                                            @endif
                                                        @endif
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="border-color:transparent;"><button class="btn btn-primary" id="filtrar">Filtrar</button></td>
                                </tr>
                            </tbody>
                    </table>
            </div>

            </form>

                <br><br>



                <div class="table-responsive">


                    <table id="mytable" class="table table-bordred table-striped">

                        <thead>

                            <th>Protocolo</th>
                            <th>Data Envio</th>
                            <th>CPF/CNPJ</th>
                            <th>Empresa</th>
                            <th>Subcategoria</th>
                            <th>Diário</th>
                            <th style="white-space:nowrap;">Situação  <a style="color:red;" href="" data-toggle="modal" data-target="#modalLegenda" ><i class="fas fa-question-circle"></i></a> </th>
                            <th style="text-align:center;">Ações</th>

                        </thead>

                        <tbody>

                            @foreach ($faturas as $fatura)

                            @php
                                $modalAceitar = false;
                                $modalPublicar = false;
                                $modalApagar = false;
                            @endphp

                            <tr>
                                <td>{{$fatura->protocoloCompleto}}</td>

                                @php
                                    $dataEnvioFatura = new DateTime($fatura->dataEnvioFatura);
                                    $dataEnvioFatura = $dataEnvioFatura->format('d/m/Y H:i:s');
                                @endphp
                                <td> {{$dataEnvioFatura}}</td>
                                @php
                                    // Calculo da mascara do cpf ou cnpj

                                    if(strlen($fatura->cpfCnpj) > 11){
                                        $mask = '##.###.###/####-##';
                                    }else{
                                        $mask ='###.###.###-##';
                                    }

                                    $val = $fatura->cpfCnpj;
                                    $maskared = '';
                                    $k = 0;
                                    for($i = 0; $i<=strlen($mask)-1; $i++)
                                    {
                                        if($mask[$i] == '#')
                                        {
                                            if(isset($val[$k]))
                                                $maskared .= $val[$k++];
                                        }
                                        else
                                        {
                                            if(isset($mask[$i]))
                                                $maskared .= $mask[$i];
                                        }
                                    }

                                @endphp

                                <td style="white-space:nowrap;"> {{$maskared}} </td>
                                <td> {{$fatura->empresa}} </td>
                                <td> @if($fatura->subcategoriaNome != null) {{$fatura->subcategoriaNome}} @else Não Possui @endif </td>

                                @if($fatura->diarioData != null)
                                    @php
                                        $dataDiario = new DateTime($fatura->diarioData);
                                        $dataDiario = $dataDiario->format('d/m/Y');
                                    @endphp
                                    <td> N° {{$fatura->numeroDiario}} <br> {{$dataDiario}} </td>
                                @else
                                    <td> Não Possui </td>
                                @endif

                                {{-- Verifica a situação e muda a cor do texto --}}
                                @if($fatura->situacaoNome == "Enviada")
                                    <td> <p  class="form-control" style="text-align:center; border-color:blue; background-color:transparent; color:blue;"><b>Cadastrada</b> </p> </td>
                                @else
                                    @if($fatura->situacaoNome == "Aceita")
                                        <td> <p  class="form-control" style="text-align:center; border-color:darkgreen; background-color:transparent; color:darkgreen;"><b>Paga</b> </p> </td>
                                    @else
                                        @if($fatura->situacaoNome == "Publicada")
                                            <td> <p  class="form-control" style="text-align:center; border-color:limegreen; background-color:transparent; color:limegreen;"><b>{{$fatura->situacaoNome}}</b> </p> </td>
                                        @else
                                            @if($fatura->situacaoNome == "Rejeitada")
                                                <td> <p  class="form-control" style="text-align:center; border-color:orange; background-color:transparent; color:orange;"><b>{{$fatura->situacaoNome}}</b> </b> </p> </td>
                                            @else
                                                {{-- APAGADA --}}
                                                <td> <p  class="form-control" style="text-align:center; border-color:red; background-color:transparent; color:red;"><b>{{$fatura->situacaoNome}}</b> </p> </td>
                                            @endif
                                        @endif
                                    @endif
                                @endif

                                <td style="white-space:nowrap;">

                                    <a href='{{ url("/fatura/ver") }}/{{$fatura->protocoloCompleto}}' class="btn btn-dark" style="width:75px">Ver</a>

                                    {{-- @if ($fatura->situacaoNome == "Apagada" || date('Y-m-d') >= $fatura->diarioData)
                                    @else
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalEditar{{$fatura->protocoloCompleto}}" style="width:75px">Editar</button>
                                    @endif --}}

                                    @if ($fatura->diarioData <= date('Y-m-d') && $fatura->situacaoNome != "Publicada" && $fatura->situacaoNome != "Apagada" && $fatura->situacaoNome == "Aceita" &&  ( Gate::allows('administrador', Auth::user()) ||  Gate::allows('publicador', Auth::user()) ) )
                                        @php
                                            $modalPublicar = true;
                                        @endphp
                                        <button class="btn btn-success" data-toggle="modal" data-target="#modalPublicar{{$fatura->protocoloCompleto}}" style="width:75px">Publicar</button>
                                    @endif

                                    @if ($fatura->situacaoNome != "Apagada"  && Gate::allows('administrador', Auth::user()) && $fatura->situacaoNome != "Publicada")
                                        @php
                                            $modalApagar = true;
                                        @endphp
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#modalApagar{{$fatura->protocoloCompleto}}" style="width:75px">Apagar</button>
                                    @endif

                                </td>

                            </tr>


                            @if ($modalApagar)

                                <form action="{{ url("/fatura/apagar") }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="protocolo" value="{{$fatura->protocoloCompleto}}">
                                    {{-- situacao Apagada --}}
                                    <div class='modal fade' id="modalApagar{{$fatura->protocoloCompleto}}" role='dialog'>
                                            <div class='modal-dialog row justify-content-center'>
                                                <div class="modal-content">
                                                        <div class="modal-header">
                                                            <Strong class=" offset-md-5" > ATENÇÃO </Strong>
                                                        </div>
                                                        <div class="modal-body">

                                                            <p> Segue protocolo de fatura:</p>
                                                            <p> <b> {{$fatura->protocoloCompleto}} </b> </p>

                                                            <p> <b>Ao apagar esta fatura, o arquivo será removido do servidor e não será maos possível editar ou publicar! </b> </p>

                                                            <br><br>

                                                            <p><strong>Deseja realmente Apagar?</strong></p>

                                                            <div>
                                                                    <div style="float: left;" class="offset-md-3">
                                                                        <div>
                                                                            <input type="submit" class="btn btn-danger" name="publicar" value="Confirmar Apagar">
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
                                </form>

                            @endif

                            @if ($modalPublicar)
                                <form action="{{ url("/fatura/publicar") }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="protocolo" value="{{$fatura->protocoloCompleto}}">
                                    <input type="hidden" name="voltar" value="sim">
                                    <div class='modal fade' id="modalPublicar{{$fatura->protocoloCompleto}}" role='dialog'>
                                            <div class='modal-dialog row justify-content-center'>
                                                <div class="modal-content">
                                                        <div class="modal-header">
                                                            <Strong class=" offset-md-4" > Confirmar Publicar </Strong>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div id="divLimite{{$fatura->protocoloCompleto}}" style="display:none;">
                                                                    <br>
                                                                    <h4 id="textoLimite{{$fatura->protocoloCompleto}}" style="text-align:center; color:red;">Texto</h4>
                                                                    <br>
                                                            </div>

                                                            <p> Segue protocolo de fatura:</p>
                                                            <p> <b> {{$fatura->protocoloCompleto}} </b> </p>

                                                            <p> Ao realizar esta ação você confirma que a fatura será publicada no diário especificado.</p>


                                                            {{-- Escolher o Diário --}}
                                                            <div class="form-group row">
                                                                    @php
                                                                        $diariosDatas = json_decode($diarioDatas);
                                                                    @endphp
                                                                        <label for="diario" class="col-md-4 col-form-label text-md-right">{{ __('Diário') }} <span style="color:red;">*</span></label>
                                                                        <div class="col-md-6">
                                                                            <select id="diario{{$fatura->protocoloCompleto}}" class="custom-select  mr-sm-2" name="diarioDataID" required onchange='dataLimite("diario{{$fatura->protocoloCompleto}}", "divBotao{{$fatura->protocoloCompleto}}", "divLabel{{$fatura->protocoloCompleto}}", "divLimite{{$fatura->protocoloCompleto}}", "textoLimite{{$fatura->protocoloCompleto}}", "labelText{{$fatura->protocoloCompleto}}")'>
                                                                                    <option slected value=""> Escolha o Diário </option>
                                                                                    @foreach ($diariosDatas as $item)
                                                                                        @php
                                                                                            $data = new DateTime($item->diarioData);
                                                                                            $data = $data->format('d/m/Y');
                                                                                        @endphp
                                                                                        <option  value="{{$item->diarioDataID}} "> N°{{$item->numeroDiario}} Data: {{$data}} </option>
                                                                                    @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>


                                                            <p><strong>Deseja realmente Publicar?</strong></p>

                                                            <div>
                                                                    <div style="float: left; display:none;" class="offset-md-3" id="divBotao{{$fatura->protocoloCompleto}}">
                                                                            <div>
                                                                                <input type="submit" class="btn btn-success" name="publicar" value="Confirmar Publicar">
                                                                            </div>
                                                                    </div>

                                                                    <div style="float: left;" class="offset-md-3" id="divLabel{{$fatura->protocoloCompleto}}">
                                                                        <Strong><span style="color:red; white-space:nowrap;" id="labelText{{$fatura->protocoloCompleto}}">Escolha um Diário!</span></Strong>
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
                                </form>
                            @endif

                            @endforeach

                        </tbody>

                    </table>

          {{$faturas->links()}}

            </div>




            </div>
        </div>

        <div class='modal fade' id="modalLegenda" role='dialog'>
                <div class='modal-dialog row justify-content-center'>
                    <div class="modal-content">
                            <div class="modal-header">
                                <Strong class=" offset-md-5" > Legenda Fatura </Strong>
                            </div>
                            <div class="modal-body">

                                <p> <strong> Cadastrada: </strong> </p>
                                <p> Fatura enviada para o sistema, aguardando pagamento, para ser aceita.</p>

                                <p> <strong> Paga: </strong> </p>
                                <p> Fatura paga, aguardando ser publicada</p>

                                <p> <strong> Rejeitada: </strong> </p>
                                <p> Fatura Rejeitada pelo administrador, devido a algum motivo descrito pelo mesmo.</p>

                                <p> <strong> Publicada: </strong> </p>
                                <p> Fatura publicada pelo administrador, presente no diário referente.</p>

                                <p> <strong> Apagada: </strong> </p>
                                <p> Fatura apagada pelo administrador. Não podendo mais ser publicada ou aceita</p>

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

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function($) {

            var diariosDiasLimites = <?php echo $diarioDatas; ?>;

            $("#subcategoriaID").select2();

            var dataBegin = "tudo";
            var dataEnd = "tudo";

            checarDataBegin = function(){
                if($('#dateBegin').attr('type') == 'text'){
                    $('#dateBegin').attr('type', 'date');
                }else{
                    dataBegin = $('#dateBegin').val();
                    var datas =  $('#dateBegin').val().split('-');
                    var datanormal = datas[2]+'/'+datas[1]+'/'+datas[0];
                    $('#dateBegin').attr('type', 'text');
                    if($('#dateBegin').val() != ""){
                        $('#dateBegin').val(datanormal);
                    }else{
                        dataBegin = "tudo";
                        $('#dateBegin').val("");
                    }

                }
            }


            checarDataEnd = function(){
                if($('#dateEnd').attr('type') == 'text'){
                    $('#dateEnd').attr('type', 'date');
                }else{
                    dataEnd = $('#dateEnd').val();
                    var datas =  $('#dateEnd').val().split('-');
                    var datanormal = datas[2]+'/'+datas[1]+'/'+datas[0];
                    $('#dateEnd').attr('type', 'text');
                    if($('#dateEnd').val() != ""){
                        $('#dateEnd').val(datanormal);
                    }else{
                        dataEnd = "tudo";
                        $('#dateEnd').val("");
                    }

                }
            }


            $('#filtrar').click(function(){
                $('#dataInicial').val(dataBegin);
                $('#dataFinal').val(dataEnd);
                $('#formFiltro').submit();
            });

            dataLimite = function(idDiario, idBotao, idLabel, idDivLimite, idTextoLimite, idTextLabel){
             if(!$("#"+idDiario).val() == ""){
                diariosDiasLimites.forEach(element => {

                    if(element.diarioDataID == $("#"+idDiario).val()){

                        var podeEnviar = false;

                        var horaEnvio =  "<?php echo Auth::user()->horaEnvio; ?>";
                        horaEnvio = horaEnvio.split(':');
                        var horaAtual = "<?php echo date('H:i:s') ?>"

                        horaAtual = horaAtual.split(':');

                        var dataAtual = ("<?php echo date('Y-m-d') ?>").split('-');
                        var dataLimite = element.diaLimite.split('-');

                        dataAtual = new Date(dataAtual[0], dataAtual[1]-1, dataAtual[2]);
                        dataLimite = new Date(dataLimite[0], dataLimite[1]-1, dataLimite[2]);

                        if(dataLimite.getTime() > dataAtual.getTime()){
                            podeEnviar = true;
                        }else{
                            if(dataLimite.getTime() == dataAtual.getTime()){

                                if(horaAtual[0] > horaEnvio[0]){
                                    podeEnviar = false;
                                }else if(horaAtual[0] == horaEnvio[0]){
                                    if(horaAtual[1] > horaEnvio[1]){
                                        podeEnviar = false;
                                    }else{
                                        podeEnviar = true;
                                    }
                                }else{
                                    podeEnviar = true;
                                }
                            }
                        }
                        $("#"+idDivLimite).css('display', 'block');
                        dataLimite = element.diaLimite.split('-');


                        if(podeEnviar){
                            $('#'+idLabel).css('display', 'none');
                            $('#'+idBotao).css('display', 'block');
                            $("#"+idTextoLimite).text('Para esse diário, você pode enviar até o dia: '+dataLimite[2]+'/'+dataLimite[1]+'/'+dataLimite[0]+' ás: '+ horaEnvio[0]+':'+ horaEnvio[1] + ' Horas');
                        }else{
                            $('#'+idBotao).css('display', 'none');
                            $('#'+idLabel).css('display', 'block');
                            $("#"+idTextoLimite).text('Para esse diário, você poderia enviar até o dia: '+dataLimite[2]+'/'+dataLimite[1]+'/'+dataLimite[0]+' ás: '+ horaEnvio[0]+':'+ horaEnvio[1] + ' Horas');
                            $('#'+idTextLabel).text('Horário de envio ultrapassado!');
                        }

                    }
                });
             }else{
                $("#"+idDivLimite).css('display', 'none');
                $('#'+idBotao).css('display', 'none');
                $('#'+idLabel).css('display', 'block');
                $("#"+idTextLabel).text('Escolha um Diário');
             }

         }

        });

    </script>

@endsection
