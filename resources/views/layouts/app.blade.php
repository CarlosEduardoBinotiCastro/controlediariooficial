<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Diário Oficial Cachoeiro de Itapemirim </title>

    <!-- Scripts -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('/logoBrasao')}}">
    <script src="{{ asset('js/app.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.ptbr.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">

    {{-- Icones do Font Awesome --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>

        a {
            color: white;
        }

        .btn-primary-outline {
            background-color: transparent;
            border-color: transparent;
        }

        .my-error-class {
            color:#FF0000;  /* red */
        }


        #inputLogout:hover {
            color: white !important;
        }

        a:hover {
            color: lightgray !important;
        }

        [type="date"]::-webkit-inner-spin-button {
            display: none;
        }

        .loader {
          border: 16px solid #f3f3f3;
          border-radius: 50%;
          border-top: 16px solid #3498db;
          width: 120px;
          height: 120px;
          -webkit-animation: spin 2s linear infinite; /* Safari */
          animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
          0% { -webkit-transform: rotate(0deg); }
          100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }


         .select2-selection__rendered{
            line-height: 32px!important;
            color: gray !important;
         }
        .select2 {
            width: 100% !important;
            border-width: 1px !important;
            border-radius: 5px !important;
        }

        .select2-selection__arrow{
            height: 34px!important;
        }

        .select2-container--default .select2-selection--single{
            background-color: white !important;
            height: 37px!important;
            font-size: 15px!important;
            border-width: 1px !important;
            margin-left: -1px!important;
        }

    </style>


</head>
<body>

    <div id="app">
        @auth

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="background: #F1F1F2; padding: 0px">

            <div style="font-size: 14px; color:#1872B3;" class="col-md-5">
               <b>SISPUDIO - Sistemas de Publicações do Diário Oficial de Cachoeiro de Itapemirim</b>
            </div>

            <div class="col-md-7">

                <form id="logout-form" action="{{ route('logout') }}" method="POST"  style="float:right;">
                        @csrf
                        <span  style="white-space: nowrap; color:#1872B3;"> <b>Usuário Logado: </b> <span style="text-transform:capitalize; color:#1872B3;"> {{Auth::user()->name}} </span> </span>
                        <input class="btn" id="inputLogout" style="background-color: #1872B3; border-color: transparent; color:white;  !important; margin: 3px;" type="submit" name="logout" value="Desconectar">
                </form>

            </div>

        </nav>




        <nav class="navbar navbar-expand-md navbar-light" style="background-color: #1872B3; padding:0;">
                <a href="{{url('/home')}}"><img src="{{url('/logo')}}" alt="home" width="130" height="60"></a>
                <a class="nav-link" href="{{url('/home')}}" style="float:left; text-transform:uppercase;">Home<span class="sr-only">(current)</span></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>



                <div class="collapse navbar-collapse " id="navbarCollapse">
                  <ul class="navbar-nav mr-auto">


                        @if (!Gate::allows('faturas', Auth::user()))

                        <li class="nav-item active" style="text-transform:uppercase;">
                                <a class="nav-link" style="color:white;" href="{{url('/publicacao/listar')}}">Publicações<span class="sr-only">(current)</span></a>
                              </li>

                              <li class="nav-item active" style="text-transform:uppercase;">
                                  <a class="nav-link" style="color:white;" href="{{url('/publicacao/cadastrar')}}">Enviar <span class="sr-only">(current)</span></a>
                              </li>

                              <li class="nav-item active" style="text-transform:uppercase;">
                                  <a class="nav-link" style="color:white;" href="{{url('/publicacao/apagadas')}}">Apagadas <span class="sr-only">(current)</span></a>
                              </li>

                        @endif

                              @if (Gate::allows('administrador', Auth::user()) || Gate::allows('faturas', Auth::user()) ||  Gate::allows('publicador', Auth::user()))

                                @can('cadernoFatura', Auth::user())

                                 <li class="nav-item active">
                                    <div class="dropdown nav-item" >
                                        <a style="background-color: transparent; border-color:transparent; color:white; text-transform:uppercase;" class="btn btn-secondary dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Faturas
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item" href="{{url('/fatura/listar')}}">Listar</a>
                                          <a class="dropdown-item" href="{{url('/fatura/cadastrar')}}">Cadastrar</a>
                                            @if (Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()))
                                                <a class="dropdown-item" href="{{url('/fatura/configuracao')}}">Configurações</a>
                                            @endif

                                        @if (Gate::allows('administrador', Auth::user()))
                                          <a class="dropdown-item" href="{{url('/fatura/relatorio')}}">Relatório Quantitativo Por Período</a>
                                          <a class="dropdown-item" href="{{url('/fatura/relatorioDetalhado')}}">Relatório Detalhado</a>
                                        @endif
                                        </div>
                                    </div>
                                 </li>

                                 @endcan

                              @endif
                    </ul>

                    @if (Gate::allows('administrador', Auth::user()) || Gate::allows('publicador', Auth::user()))
                        <div class="dropdown nav-item" >
                            <button style="background-color: transparent; border-color:transparent; color:white; text-transform:uppercase;" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Cadastros
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                             @if (Gate::allows('administrador', Auth::user()))
                                <a class="dropdown-item" href="{{url("/usuario/listar")}}">Usuários</a>
                             @endif
                              <a class="dropdown-item" href="{{url('/orgaorequisitante/listar')}}">Órgão Requisitante</a>
                              <a class="dropdown-item" href="{{url('/caderno/listar')}}">Caderno</a>
                              <a class="dropdown-item" href="{{url('/tipodocumento/listar')}}">Matéria</a>
                              <a class="dropdown-item" href="{{url('/subcategoria/listar')}}">Subcategorias</a>
                              <a class="dropdown-item" href="{{url('/diariodata/listar')}}">Datas Diários Oficiais</a>
                              <a class="dropdown-item" href="{{url('/diasnaouteis/listar')}}">Feriados/Pontos Facultativos</a>
                              @if (Gate::allows('administrador', Auth::user()))
                                <a class="dropdown-item" href="{{url("/log/listar")}}">LOGS</a>
                             @endif
                            </div>
                        </div>

                        <div class="nav-item" >
                            <a class="nav-link" style="float:right; color:white; text-transform:uppercase;" href="{{url('/comunicado/listar')}}">Comunicados <span class="sr-only">(current)</span></a>
                        </div>

                    @endif

                    <div class="nav-item" >
                        <a class="nav-link" style="float:right; color:white; text-transform:uppercase;" href="{{url('/usuario/editar')}}/{{Auth::user()->id}}">Meus Dados <span class="sr-only">(current)</span></a>
                    </div>


                </div>
              </nav>



                    @else
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="background-color: #1872B3; padding: 0">

                    <div class="container">
                        <div class="col-md-12 row" >
                            <img src="{{url('/logo')}}" alt="home" width="130" height="60">
                            <h4 style="text-align: center; color:white; margin-top:15px; margin-left: 1%;"><strong>SISPUDIO - Sistemas de Publicações do Diário Oficial de Cachoeiro de Itapemirim</strong></h4>
                        </div>
                    @endauth
                </div>
                </div>
            </nav>

        <main class="py-4" id="corpo">

            @auth

                @php
                    $comunicadoController = new App\Http\Controllers\ComunicadoController;
                    $comunicados = $comunicadoController->verificarComunicados();
                @endphp

                <div id="Erro" class="container">
                        <div class="col-md-12 offset-md-0">
                            @if(sizeof($comunicados))
                                <br>
                                @foreach ($comunicados as $comunicado)
                                    <form action="{{url('/comunicado/visualizarComunicado')}}"  method="POST">
                                        @csrf
                                        <input type="hidden" name="comunicadoID" value=" {{$comunicado->comunicadoID}} ">
                                        <div class="form-group alert alert-primary" style="font-size:20px">
                                            <div style=" background-color:lightblue; text-align:center; border-radius:10px; "> <h2 style="font-weight:bold;">Comunicado</h2></div>

                                            <b>{{$comunicado->tituloMensagem}}</b>
                                            <br>
                                            {{$comunicado->mensagem}}
                                            {{-- <a href="/fatura/irParaAceita" class="btn btn-success" style="margin-left:2%;">Ver Faturas</a> --}}
                                            <br>
                                            <div class=" form-group row mb-0 justify-content-end" style="margin-right:1%;">
                                                <button type="submit" class="btn btn-primary"> Entendi </button>
                                            </div>

                                        </div>
                                    </form>
                                    <br>
                                @endforeach
                            @endif
                            </div>
                    </div>

            @endauth


            <div id="Erro" class="container">
                <div class="col-md-12 offset-md-0">
                    <div class="form-group alert alert-primary" style="font-size:20px">
                        <div style=" background-color:red; text-align:center; border-radius:10px; "> <h2 style="font-weight:bold;">Comunicado</h2></div>

                        <p> NÃO USAR ESSE SISTEMA POR AQUI, ACESSE O SERVIDOR 10.1.19.220/sispudio . ESSA AREA AGORA É SOMENTE DE TESTES!</p>

                    </div>
                    </div>
            </div>


            @yield('content')
        </main>
        {{-- <a class="btn" href="/fatura/caixaDeTexto">Teste</a> --}}
    </div>

    <footer class="border-top">
        <div class="col-md-12" style="background-color:#DEDDDD">
            <div class="row col-md-12">
                    <div class="col-md-10">
                        <p style=" font-size: 12px; margin-top:20px; text-align:left;"> <b> Publicações de matérias INTERNAS e EXTERNAS:</b>  SEMAD - DIÁRIO OFICIAL - (28) 3522 4708 <br> <b>Publicação de LICENÇAS AMBIENTAIS:</b> SEMMA - (28) 3155 5326</p>
                    </div>
                    <div class="col-md-2">
                        <img style="margin-top:5px;" src="{{url('/logoSis')}}" alt="home" height="60px">
                    </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="background: #1872B3; padding: 0px">

            <span class="col-md-7" style="color:white; font-size:12px;">
                Desenvolvido por Carlos Eduardo B. de Castro (CGM), Matheus Mauricio de S. Araujo (CGM) e Mauricio P. Lima (SEMAD) - {{date('Y')}}
            </span>

            <span class="col-md-5" style="color:white; font-size:12px;">
                    Copyright © 2019 Prefeitura Municipal de Cachoeiro de Itapemirim - Todos os direitos reservados
            </span>



            </nav>
    </footer>

</body>

<script>

    $(document).ready(function(){
        $("#corpo").css("min-height", $(document).height()*0.8);
    });

</script>

</html>

