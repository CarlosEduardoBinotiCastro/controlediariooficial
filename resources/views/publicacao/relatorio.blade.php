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

    <h2>Relatório de Publicações</h2>

    <div class="attendance-table">

        <table style="font-size:12px;">

            <tr>
                    <th class="attendance-cell" style="width:10%;">Protocolo</th>
                    <th class="attendance-cell" style="width:25%;">Título</th>
                    <th class="attendance-cell" style="width:10%;">Diário</th>
                    <th class="attendance-cell" style="width:10%;">Órgão</th>
                    <th class="attendance-cell" style="width:10%;">Data Envio</th>
                    <th class="attendance-cell" style="width:20%;">Usuário</th>
                    <th class="attendance-cell" style="white-space:nowrap; width:10%;">Situação</th>

            </tr>

                <tbody>

                    @foreach ($publicacoes as $publicacao)


                    <tr>

                        @php
                            $dataDiario = new DateTime($publicacao->diarioData);
                            $dataDiario = $dataDiario->format('d/m/Y');

                            $dataEnviado = new DateTime($publicacao->dataEnvio);
                            $dataEnviado = $dataEnviado->format('d/m/Y à\s\ H:i')
                        @endphp

                        <td class="attendance-cell" style="width:10%;">{{$publicacao->protocoloCompleto}}</td>
                        <td class="attendance-cell" style="width:25%;"> {{$publicacao->titulo}} </td>
                        <td class="attendance-cell" style="width:10%;"> N° {{$publicacao->numeroDiario}}<br>{{$dataDiario}}</td>


                        <td class="attendance-cell" style="width:10%;"> {{$publicacao->orgaoNome}}</td>
                        <td class="attendance-cell" style="width:10%;"> {{$dataEnviado}} </td>
                        <td class="attendance-cell" style="text-transform:capitalize; width:20%;"> {{$publicacao->nomeUsuario}} </td>

                        {{-- Verifica a situação e muda a cor do texto --}}
                        @if($publicacao->situacaoNome == "Enviada")
                                                <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:blue; background-color:transparent; color:blue;"><b>{{$publicacao->situacaoNome}}</b> </p> </td>
                                            @else
                                                @if($publicacao->situacaoNome == "Aceita")
                                                    <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:darkgreen; background-color:transparent; color:darkgreen; "><b>{{$publicacao->situacaoNome}}</b> </p> </td>
                                                @else
                                                    @if($publicacao->situacaoNome == "Publicada")
                                                        <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:limegreen; background-color:transparent; color:limegreen;"><b>{{$publicacao->situacaoNome}}</b> </p> </td>
                                                    @else
                                                        @if($publicacao->situacaoNome == "Rejeitada")
                                                            <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:orange; background-color:transparent; color:orange;"><b>{{$publicacao->situacaoNome}}</b> </b> </p> </td>
                                                        @else
                                                            {{-- APAGADA --}}
                                                            <td class="attendance-cell" style="width:15%;"> <p style="text-align:center; border-color:red; background-color:transparent; color:red;"><b>{{$publicacao->situacaoNome}}</b> </p> </td>
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
