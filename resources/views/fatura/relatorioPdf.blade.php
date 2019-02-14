<html>
<head>
  <style>
    @page { margin: 50px 25px; }
    header { top: -100px;  background-color: lightblue; }
    main   { left: 0px; right: 0px; }
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }


    .attendance-table table{
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #000;
    }

    .attendance-cell{
        padding: 5px;
    }

    .attendance-table table th.attendance-cell, .attendance-table table td.attendance-cell {
        border: 1px solid #000;
    }





  </style>
</head>
<body>
  <header style="margin-top:-50px;">@php echo '<img src="'.$path.'">'; @endphp</header>
  <main>

    <h2>Relatório de Faturas</h2>

    <div class="attendance-table">

        <table style="font-size:12px;">

            <tr>
                    <th class="attendance-cell" style="width:10%;">Protocolo</th>
                    <th class="attendance-cell" style="width:10%;">Data Envio</th>
                    <th class="attendance-cell" style="width:10%;">CPF/CNPJ</th>
                    <th class="attendance-cell" style="width:25%;">Empresa</th>
                    <th class="attendance-cell" style="width:20%;">Subcategoria</th>
                    <th class="attendance-cell" style="width:10%;">Diário</th>
                    <th class="attendance-cell" style="width:10%;">Situação</th>

            </tr>

                <tbody>

                    @foreach ($faturas as $fatura)

                    <tr>

                        @php
                            $dataDiario = new DateTime($fatura->diarioData);
                            $dataDiario = $dataDiario->format('d/m/Y');

                            $dataEnviado = new DateTime($fatura->dataEnvioFatura);
                            $dataEnviado = $dataEnviado->format('d/m/Y à\s\ H:i');


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

                        <td class="attendance-cell" style="width:10%;">{{$fatura->protocoloCompleto}}</td>
                        <td class="attendance-cell" style="width:10%;"> {{$dataEnviado}} </td>
                        <td class="attendance-cell" style="width:10%; white-space:nowrap;"> {{$maskared}} </td>
                        <td class="attendance-cell" style="width:25%;"> {{$fatura->empresa}}</td>

                        @if ($fatura->subcategoriaNome != null)
                            <td class="attendance-cell" style="width:20%;"> {{$fatura->subcategoriaNome}}</td>
                        @else
                            <td class="attendance-cell" style="width:20%;"> Não Possui </td>
                        @endif

                        @if ($fatura->diarioData != null)
                            <td class="attendance-cell" style="text-transform:capitalize; width:10%;"> N° {{$fatura->numeroDiario}}<br>{{$dataDiario}} </td>
                        @else
                            <td class="attendance-cell" style="text-transform:capitalize; width:10%;"> Não Possui </td>
                        @endif

                        {{-- Verifica a situação e muda a cor do texto --}}
                        @if($fatura->situacaoNome == "Enviada")
                                                <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:blue; background-color:transparent; color:blue;"><b>Cadastrada</b> </p> </td>
                                            @else
                                                @if($fatura->situacaoNome == "Aceita")
                                                    <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:darkgreen; background-color:transparent; color:darkgreen; "><b>Paga</b> </p> </td>
                                                @else
                                                    @if($fatura->situacaoNome == "Publicada")
                                                        <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:limegreen; background-color:transparent; color:limegreen;"><b>{{$fatura->situacaoNome}}</b> </p> </td>
                                                    @else
                                                        @if($fatura->situacaoNome == "Rejeitada")
                                                            <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:orange; background-color:transparent; color:orange;"><b>{{$fatura->situacaoNome}}</b> </b> </p> </td>
                                                        @else
                                                            {{-- APAGADA --}}
                                                            <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:red; background-color:transparent; color:red;"><b>{{$fatura->situacaoNome}}</b> </p> </td>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif


                        </tr>

                        @endforeach
                </tbody>

        </table>
    </div>



  </main>
</body>
</html>
