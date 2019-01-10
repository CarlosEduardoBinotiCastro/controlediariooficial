
<html>
<head>
  <style>
    @page { margin: 100px 25px; }
    header { position: fixed; top: -100px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    footer { position: fixed; bottom: -80px; left: 0px; right: 0px; background-color: #1C6EA4; height: 100px; }
    main   { position: fixed; top: 100px; left: 0px; right: 0px; }
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
    .spanFooter{color:white; font-weight: bold; text-align: center;}


    table.blueTable {
      border: 1px solid #1C6EA4;
      background-color: #EEEEEE;
      width: 50%;
      text-align: left;
      border-collapse: collapse;


    }
    table.blueTable td, table.blueTable th {
      border: 1px solid #AAAAAA;

    }
    table.blueTable tbody td {
      font-size: 13px;

    }
    table.blueTable tr:nth-child(even) {
      background: #D0E4F5;

    }
    table.blueTable thead {
      background: #1C6EA4;
      background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
      background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
      background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
      border-bottom: 2px solid #444444;
    }
    table.blueTable thead th {
      font-size: 15px;
      font-weight: bold;
      color: #FFFFFF;
      border-left: 2px solid #D0E4F5;

    }
    table.blueTable thead th:first-child {
      border-left: none;

    }

    table.blueTable tfoot {
      font-size: 14px;
      font-weight: bold;
      color: #FFFFFF;
      background: #D0E4F5;
      background: -moz-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
      background: -webkit-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
      background: linear-gradient(to bottom, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
      border-top: 2px solid #444444;

    }
    table.blueTable tfoot td {
      font-size: 14px;

    }
    table.blueTable tfoot .links {
      text-align: right;
    }
    table.blueTable tfoot .links a{
      display: inline-block;
      background: #1C6EA4;
      color: #FFFFFF;
      padding: 2px 8px;
      border-radius: 5px;

    }

    table.remetente {
      width: 100%;
      height: 100px;
      text-align: left;
      border: none;
    }
    table.remetente td, table.remetente th {
      border: none;
    }
    table.remetente tfoot td {
      font-size: 14px;
    }
    table.remetente tfoot .links {
      text-align: right;
    }
    table.remetente tfoot .links a{
      display: inline-block;
      background: #1C6EA4;
      color: #FFFFFF;
      padding: 2px 8px;
      border-radius: 5px;
    }



    table.materia {
      width: 100%;
      height: 100px;
      text-align: left;
      border: none;
    }
    table.materia td, table.materia th {
      border: none;
    }
    table.materia tfoot td {
      font-size: 14px;
    }
    table.materia tfoot .links {
      text-align: right;
    }
    table.materia tfoot .links a{
      display: inline-block;
      background: #1C6EA4;
      color: #FFFFFF;
      padding: 2px 8px;
      border-radius: 5px;
    }

  </style>
</head>
<body>
  <header>@php echo '<img src="'.$path.'">'; @endphp</header>
  <footer style="text-align:center !important;">
        <span class="spanFooter"> DEPARTAMENTO DO DIÁRIO OFICIAL </span> <br>
        <span class="spanFooter"> Informações de Contato para Publicações </span> <br>
        <span class="spanFooter"> Telefone: (28) 3522 4708 </span> <br>
        <span class="spanFooter"> Email: diário.oficial@cachoeiro.es.gov.br </span> <br>
        <span class="spanFooter"> Atendimento: Segunda à Sexta, 09:00h às 18:00h </span>
  </footer>
  <main>
        <h2> Comprovante De Envio de Fatura </h2> <br>

        <span>Protocolo: <b>{{$fatura->protocoloCompleto}}</b> </span> {{--Protocolo--}}

        <br><br>

        <span> O <b>DEPARTAMENTO DO DIÁRIO OFICIAL</b> declara que o conteúdo abaixo foi recebido pelo sistema Diário Oficial de Cachoeiro de Itapemirim, para publicação
                no Diário Oficial na Categoria e Data descritas abaixo, sendo de exclusiva responsabilidade do Usuário Publicador o conteúdo da
                matéria e a data de publicação selecionada.</span>

        <br><br>

        <h3>Remetente</h3>

        <table class="remetente">
                <tbody>

                <tr>
                     <td style="width: 50% !important; margin-top:1% !important;">Nome do Cliente</td>
                     <td style="width: 50% !important; margin-top:1% !important; text-transform:capitalize !important;"> {{$fatura->requisitante}} </td></tr>

                <tr>
                       <td style="width: 50% !important; margin-top:1% !important;">Empresa</td>
                       <td style="width: 50% !important; margin-top:1% !important;"> {{$fatura->empresa}} </td></tr>

                <tr>
                     <td style="width: 50% !important; margin-top:1% !important;">CPF/CNPJ</td>

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

                     <td style="width: 50% !important; margin-top:1% !important;"> {{$maskared}} </td></tr>

                <tr>
                    <td style="width: 50% !important; margin-top:1% !important;">Publicador</td>
                    <td style="width: 50% !important; margin-top:1% !important; text-transform:capitalize !important;"> {{$fatura->usuarioNome}} </td></tr>


                    @php
                        $data = new DateTime($fatura->dataEnvioFatura);
                        $data = $data->format('d/m/Y H:i:s');
                    @endphp

                <tr>
                    <td style="width: 50% !important; margin-top:1% !important;">Data Recebimento</td>
                    <td style="width: 50% !important; margin-top:1% !important;"> {{$data}} </td></tr>

                  </tbody>

        </table>

        <br>

        <h3>Identificação da Matéria</h3>

        <table class="materia">
                <tbody>


                <tr>
                    <td style="width: 50% !important; margin-top:1% !important;">Matéria</td>
                    <td style="width: 50% !important; margin-top:1% !important;"> {{$fatura->tipoDocumento}} </td></tr>

                @if ($fatura->subcategoriaNome != null)
                    <tr>
                        <td style="width: 50% !important; margin-top:1% !important;">Subcategoria</td>
                        <td style="width: 50% !important; margin-top:1% !important;">{{$fatura->subcategoriaNome}}</td></tr>
                @else
                    <tr>
                        <td style="width: 50% !important; margin-top:1% !important;">Subcategoria</td>
                        <td style="width: 50% !important; margin-top:1% !important;">Não Possui</td></tr>
                @endif


                @if ($fatura->numeroDiario != null && $fatura->situacaoNome != "Apagada")

                @php
                    $dataPub = new DateTime($fatura->dataDiario);
                    $dataPub = $dataPub->format('d/m/Y');
                @endphp

                    <tr>
                        <td style="width: 50% !important; margin-top:1% !important;">Data Publicação</td>
                        <td style="width: 50% !important; margin-top:1% !important;">{{$dataPub}}</td></tr>

                    <tr>
                        <td style="width: 50% !important; margin-top:1% !important;">Número Diário</td>
                        <td style="width: 50% !important; margin-top:1% !important;">{{$fatura->numeroDiario}}</td></tr>

                @endif

                <tr>
                    <td style="width: 50% !important; margin-top:1% !important;">Situação</td>
                    <td style="width: 50% !important; margin-top:1% !important;"> {{$fatura->situacaoNome}} </td></tr>

                  </tbody>

        </table>

        <br><br>

        <table class="blueTable">
            <thead>

                <th>Centimetragem</th>
                <th>Valor Coluna </th>
                <th>Valor Total </th>
            </thead>

            <tbody>
                <tr>
                    <td> {{$fatura->centimetragem}} </td>
                    <td> R$ {{$fatura->valorColuna}} </td>
                    <td> R$ {{$fatura->valor}} </td>
                </tr>
            </tbody>

        </table>


  </main>
</body>
</html>
