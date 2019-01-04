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


<div class="container">

        <br>
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Lista de Faturas </strong> </h4> </div> <br>


            <form id="formFiltro" action="{{url("fatura/chamarListar")}}" method="POST">
            @csrf

            <div class="table-responsive">
                    <table class="table table-bordred table-striped" style="background-color:#DEDDDD; border-radius: 20px;">

                            <tbody>
                                <tr style="background-color:transparent;">
                                    <td style="border-color:transparent;"><input style="resize:none; width: 150px;" type="text" class="form-control" name="cpfCnpj" placeholder="N° CPF / CNPJ"></td>
                                    <td><input style="resize:none; width: 150px;" type="text" class="form-control" name="empresa" placeholder="Empresa"></td>
                                    <td><input style="resize:none; width: 150px;" type="text" class="form-control" name="protocolo" placeholder="Protocolo"></td>

                                    <td>
                                            <select style="resize:none; width: 150px;" class="custom-select" name="subcategoria" id="subcategoriaID" >
                                                    <option slected value="tudo">Subcategoria</option>
                                                    <option  value="NaoPossui">Não Possui</option>
                                                @foreach ($subcategorias as $subcategoria)
                                                    <option value=" {{$subcategoria->subcategoriaID}} "> {{$subcategoria->subcategoriaNome}} </option>
                                                @endforeach
                                            </select>
                                    </td>

                                    <td>
                                            <input style="resize:none; width: 150px;" placeholder="Data Diário" class="form-control" type="text" onfocus="checarData()" onfocusout="checarData()" id="date">
                                            <input type="hidden" name="diario" value="tudo" id="diario">
                                        </td>

                                    <td>
                                        <select style="resize:none; width: 100px;" class="custom-select" name="situacao" >
                                                <option slected value="tudo">Situação</option>
                                            @foreach ($situacoes as $situacao)
                                                <option value=" {{$situacao->situacaoNome}} "> {{$situacao->situacaoNome}} </option>
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
                                <td> @if($fatura->subcategoriaNome != null) {{$fatura->subcategoriaNome}} @else Não Possui @endif</td>

                                @php
                                    $dataDiario = new DateTime($fatura->diarioData);
                                    $dataDiario = $dataDiario->format('d/m/Y');
                                @endphp
                                <td> N° {{$fatura->numeroDiario}} <br> {{$dataDiario}} </td>

                                <td> {{$fatura->situacaoNome}} </td>

                                <td style="white-space:nowrap;">

                                    <a href='/fatura/ver/{{$fatura->protocoloCompleto}}' class="btn btn-dark" style="width:75px">Ver</a>

                                    {{-- @if ($fatura->situacaoNome == "Apagada" || date('Y-m-d') >= $fatura->diarioData)
                                    @else
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalEditar{{$fatura->protocoloCompleto}}" style="width:75px">Editar</button>
                                    @endif --}}

                                    @if ($fatura->diarioData <= date('Y-m-d') && $fatura->situacaoNome != "Publicada" && $fatura->situacaoNome != "Apagada" && $fatura->situacaoNome == "Aceita" && Gate::allows('administrador', Auth::user()))
                                        @php
                                            $modalPublicar = true;
                                        @endphp
                                        <button class="btn btn-success" data-toggle="modal" data-target="#modalPublicar{{$fatura->protocoloCompleto}}" style="width:75px">Publicar</button>
                                    @endif

                                    @if ($fatura->situacaoNome != "Apagada")
                                        @php
                                            $modalApagar = true;
                                        @endphp
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#modalApagar{{$fatura->protocoloCompleto}}" style="width:75px">Apagar</button>
                                    @endif

                                </td>

                            </tr>


                            @if ($modalApagar)

                                <form action="/fatura/apagar" method="POST">
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
                                <form action="/fatura/publicar" method="POST">
                                    @csrf
                                    <input type="hidden" name="protocolo" value="{{$fatura->protocoloCompleto}}">
                                    {{-- situacao publicada --}}
                                    <div class='modal fade' id="modalPublicar{{$fatura->protocoloCompleto}}" role='dialog'>
                                            <div class='modal-dialog row justify-content-center'>
                                                <div class="modal-content">
                                                        <div class="modal-header">
                                                            <Strong class=" offset-md-4" > Confirmar Publicar </Strong>
                                                        </div>
                                                        <div class="modal-body">

                                                            <p> Segue protocolo de fatura:</p>
                                                            <p> <b> {{$fatura->protocoloCompleto}} </b> </p>

                                                            <p> Ao realizar esta ação você confirma que a fatura foi publicada no diário especificado.</p>

                                                            <p><strong>Deseja realmente Publicar?</strong></p>

                                                            <div>
                                                                    <div style="float: left;" class="offset-md-3">
                                                                        <div>
                                                                            <input type="submit" class="btn btn-success" name="publicar" value="Confirmar Publicar">
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

                                <p> <strong> Enviada: </strong> </p>
                                <p> Fatura enviada para o sistema, aguardando pagamento, para ser aceita.</p>

                                <p> <strong> Aceita: </strong> </p>
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

            var data = "tudo";

            $("#subcategoriaID").select2();

            checarData = function(){
                if($('#date').attr('type') == 'text'){
                    $('#date').attr('type', 'date');
                }else{
                    data = $('#date').val();
                    var datas =  $('#date').val().split('-');
                    var datanormal = datas[2]+'/'+datas[1]+'/'+datas[0];
                    $('#date').attr('type', 'text');
                    if($('#date').val() != ""){
                        $('#date').val(datanormal);
                    }else{
                        data = "tudo";
                        $('#date').val("");
                    }

                }
            }


            $('#filtrar').click(function(){
                $('#diario').val(data);
                $('#formFiltro').submit();
            });

        });

    </script>

@endsection
