
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

    .infoRows{margin: 0 !important;}

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
        <h2> Comprovante De Envio de Fatura </h2>

        <span>Protocolo: <b>{{$fatura->protocoloCompleto}}</b> </span> {{--Protocolo--}}

        <br><br>

        <span> O <b>DEPARTAMENTO DO DIÁRIO OFICIAL</b> declara que o conteúdo abaixo foi recebido pelo sistema Diário Oficial de Cachoeiro de Itapemirim, para publicação
                no Diário Oficial na Categoria e Data descritas abaixo. </span>



        <h3>Remetente</h3>

        <table class="remetente">
                <tbody>

                <tr>
                     <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Nome do Cliente</td>
                     <td class="infoRows" style="width: 70% !important; margin-top:1% !important; text-transform:capitalize !important;"> {{$fatura->requisitante}} </td></tr>

                <tr>
                       <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Empresa</td>
                       <td class="infoRows" style="width: 70% !important; margin-top:1% !important;"> {{$fatura->empresa}} </td></tr>

                <tr>
                     <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">CPF/CNPJ</td>

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

                     <td class="infoRows" style="width: 70% !important; margin-top:1% !important;"> {{$maskared}} </td></tr>


                         @if ($fatura->usuarioNomePublicou != null)
                            <tr>
                                <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Publicador</td>
                                <td class="infoRows" style="width: 70% !important; margin-top:1% !important; text-transform:capitalize !important;"> {{$fatura->usuarioNomePublicou}} </td></tr>
                        @endif

                    @php
                        $data = new DateTime($fatura->dataEnvioFatura);
                        $data = $data->format('d/m/Y H:i:s');
                    @endphp

                <tr>
                    <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Data Recebimento</td>
                    <td class="infoRows" style="width: 70% !important; margin-top:1% !important;"> {{$data}} </td></tr>


                    @if ($fatura->email != null)
                        <tr>
                            <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Email</td>
                            <td class="infoRows" style="width: 70% !important; margin-top:1% !important; text-transform:capitalize !important;"> {{$fatura->email}} </td></tr>

                    @endif

                    @if ($fatura->telefoneFixo != null)
                        <tr>
                            <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Telefone Fixo</td>
                            <td class="infoRows" style="width: 70% !important; margin-top:1% !important; text-transform:capitalize !important;"> {{$fatura->telefoneFixo}} </td></tr>

                    @endif

                    @if ($fatura->telefoneCelular != null)
                        <tr>
                            <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Telefone Celular</td>
                            <td class="infoRows" style="width: 70% !important; margin-top:1% !important; text-transform:capitalize !important;"> {{$fatura->telefoneCelular}} </td></tr>
                    @endif


                  </tbody>

        </table>



        <h3>Identificação da Matéria</h3>

        <table class="materia">
                <tbody>


                <tr>
                    <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Matéria</td>
                    <td class="infoRows" style="width: 70% !important; margin-top:1% !important;"> {{$fatura->tipoDocumento}} </td></tr>

                @if ($fatura->subcategoriaNome != null)
                    <tr>
                        <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Subcategoria</td>
                        <td class="infoRows" style="width: 70% !important; margin-top:1% !important;">{{$fatura->subcategoriaNome}}</td></tr>
                @else
                    <tr>
                        <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Subcategoria</td>
                        <td class="infoRows" style="width: 70% !important; margin-top:1% !important;">Não Possui</td></tr>
                @endif


                @if ($fatura->numeroDiario != null && $fatura->situacaoNome != "Apagada")

                @php
                    $dataPub = new DateTime($fatura->dataDiario);
                    $dataPub = $dataPub->format('d/m/Y');
                @endphp

                    <tr>
                        <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Data Publicação</td>
                        <td class="infoRows" style="width: 70% !important; margin-top:1% !important;">{{$dataPub}}</td></tr>

                    <tr>
                        <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Número Diário</td>
                        <td class="infoRows" style="width: 70% !important; margin-top:1% !important;">{{$fatura->numeroDiario}}</td></tr>

                @endif

                <tr>
                    <td class="infoRows" style="width: 30% !important; margin-top:1% !important;">Situação</td>
                    <td class="infoRows" style="width: 70% !important; margin-top:1% !important;">
                        @if ($fatura->situacaoNome == "Aceita")
                            Paga
                        @else
                            @if ($fatura->situacaoNome == "Enviada")
                                Cadastrada
                            @else
                                {{$fatura->situacaoNome}}
                            @endif
                        @endif
                     </td></tr>

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

        <h4>INFORMAÇÕES GERAIS</h4>

        <div id="informações" style="text-align:justify !important;  font-size:12px;">

            O compromisso de <b> ELABORAR </b> o texto a ser publicado é de única e exclusivamente de
            <b>RESPONSABILIDADE</b> do Contribuinte, ficando esse <b>OBRIGADO</b> a elaborá-lo em conformidade com
            a <b>Resolução Conama n° 6</b>, de 24 de janeiro de 1986, publicada no DOU de 17 de fevereiro de 1986
            (Seção 1 – Página 2550), c/c com o Anexo XIV, da <b>Instrução Normativa/Semma n° 002</b> (Decreto
            Municipal 26.094), de 02 de maio de 2016, publicado no DOM n° 5087 de 03 de maio de 2016 (Página 2
            e seguintes).

            <br>

            <b>  A publicação do conteúdo será efetivada após a confirmação do pagamento do boleto (DAM), que pode
                 levar até 72 horas após o pagamento.  </b>

        </div>

  </main>
</body>
</html>
