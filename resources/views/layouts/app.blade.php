<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Diário Oficial Cachoeiro de Itapemirim </title>

    <!-- Scripts -->
    {{-- <link rel="icon" type="image/png" sizes="16x16" href="/logo"> --}}
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

    </style>


</head>
<body>

    <div id="app">
        @auth

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="background: lightblue; padding: 0px">

            <span class="col-md-10" style="white-space: nowrap;"> Usuário Logado: <strong style="text-transform:capitalize;"> {{Auth::user()->name}} </strong> </span>

            <div class="container col-md-2">

                    <ul class="navbar-nav ml-auto">


                        <li class="nav-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <input class="btn" id="inputLogout" style="background-color: transparent; border-color: transparent; color:black" type="submit" name="logout", value="Desconectar">
                                </form>
                        </li>
                    </ul>
                </div>



            </nav>




        <nav class="navbar navbar-expand-md navbar-light" style="background-color: #e3f2fd;">
                <a href="/home"><img src="/logo" alt="" width="60" height="50"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse offset-md-1" id="navbarCollapse">
                  <ul class="navbar-nav mr-auto">

                        @if (!Gate::allows('faturas', Auth::user()))

                        <li class="nav-item active">
                                <a class="nav-link" href="/publicacao/listar">Publicações<span class="sr-only">(current)</span></a>
                              </li>

                              <li class="nav-item active">
                                  <a class="nav-link" href="/publicacao/cadastrar">Enviar <span class="sr-only">(current)</span></a>
                              </li>

                              <li class="nav-item active">
                                  <a class="nav-link" href="/publicacao/apagadas">Apagadas <span class="sr-only">(current)</span></a>
                              </li>

                        @endif

                              @if (Gate::allows('administrador', Auth::user()) || Gate::allows('faturas', Auth::user()))

                                @can('cadernoFatura', Auth::user())

                                 <li class="nav-item active">
                                    <div class="dropdown nav-item" >
                                        <a style="background-color: transparent; border-color:transparent; color:black;" class="btn btn-secondary dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Faturas
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item" href="/fatura/listar">Listar</a>
                                          <a class="dropdown-item" href="/fatura/cadastrar">Cadastrar</a>
                                          <a class="dropdown-item" href="/fatura/configuracao">Configurações</a>
                                          <a class="dropdown-item" href="/fatura/relatorio">Relatório Quantitativo Por Período</a>
                                          <a class="dropdown-item" href="/fatura/relatorioDetalhado">Relatório Detalhado</a>
                                        </div>
                                    </div>
                                 </li>

                                 @endcan

                              @endif
                    </ul>

                    @can('administrador', Auth::user())
                        <div class="dropdown nav-item" >
                            <button style="background-color: transparent; border-color:transparent; color:black;" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Cadastros
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item" href="{{url("/usuario/listar")}}">Usuários</a>
                              <a class="dropdown-item" href="/orgaorequisitante/listar">Órgão Requisitante</a>
                              <a class="dropdown-item" href="/caderno/listar">Caderno</a>
                              <a class="dropdown-item" href="/tipodocumento/listar">Matéria</a>
                              <a class="dropdown-item" href="/subcategoria/listar">Subcategorias</a>
                              <a class="dropdown-item" href="/diariodata/listar">Datas Diários Oficiais</a>
                              <a class="dropdown-item" href="/diasnaouteis/listar">Feriados/Pontos Facultativos</a>
                            </div>
                        </div>
                    @endcan

                    <div class="nav-item" >
                        <a style="color:black;"  class="nav-link" style="float:right;" href="#">Mensagens <span class="sr-only">(current)</span></a>
                    </div>


                    <div class="nav-item" >
                        <a  style="color:black;" class="nav-link" style="float:right;" href="/usuario/editar/{{Auth::user()->id}}">Meus Dados <span class="sr-only">(current)</span></a>
                    </div>


                </div>
              </nav>



                    @else
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="background-color: #e3f2fd;">

                    <div class="container">

                        <div class="col-md-12">
                        <h3 style="text-align: center;"><strong>Diário Oficial de Cachoeiro de Itapemirim</strong></h3>
                    </div>

                    @endauth
                </div>
                </div>
            </nav>

        <main class="py-4" id="corpo">
            @yield('content')
        </main>
        {{-- <a class="btn" href="/fatura/caixaDeTexto">Teste</a> --}}
    </div>

    <footer class="border-top">
        <div class="container">
            <div class="row justify-content-center">

                    <p style="margin-top:1%;">Desenvolvido por Carlos Eduardo B. de Castro (CGM), Matheus Mauricio de S. Araujo (CGM) e Mauricio P. Lima (SEMAD) - {{date('Y')}}</p>

            </div>
        </div>
    </footer>

</body>

<script>

    $(document).ready(function(){
        $("#corpo").css("min-height", $(document).height()*0.8);
    });

</script>

</html>

