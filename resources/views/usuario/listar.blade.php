@extends('layouts.app')

@section('content')

@guest



@else




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


<br/><br/>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pesquisar Usuário</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div class="col-md-12">
                        <form action="{{url("usuario/chamarListar")}}" method="POST">
                            @csrf
                            <div style="float: left;" class="col-md-4">
                                    <label><strong>Nome ou Documento</strong></label>
                            </div>
                            <div style="float: left;" class="col-md-5">
                                <input class="form-control" type="text" name="nomeDoc" placeholder="Apenas letras ou números">
                            </div>
                            <div style="float: left;" class="col-md-3">
                                <input class="btn btn-primary" type="submit" value="Pesquisar" name="enviar">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<br>

<div class="container">
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Lista de Usuários</strong> </h4>  <a style="margin-left:auto" href='{{ url("/usuario/cadastrar") }}' class="btn btn-success">Cadastrar</a> </div> <br>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>
                       <th>Nome</th>
                       <th>Órgão</th>
                       <th>e-mail</th>
                       <th>CPF</th>
                       <th>Status</th>
                       <th>Editar</th>
                       <th>Desativar</th>
                       </thead>
        <tbody>

         @foreach ($usuarios as $usuario)

         @php
            $mask = "###.###.###-##";
            $usuario->cpf = str_replace(" ","",$usuario->cpf);
            for($i=0;$i<strlen($usuario->cpf);$i++){
                $mask[strpos($mask,"#")] = $usuario->cpf[$i];
            }
            $usuario->cpf = $mask;
        @endphp

         <tr>

            <td style="text-transform:capitalize;">{{$usuario->name}}</td>
            <td>{{$usuario->orgao}}</td>
            <td>{{$usuario->email}}</td>
            <td>{{$usuario->cpf}}</td>
            <td>{{$usuario->descricao}}</td>
            <td> <a href='{{ url("/usuario/editar") }}/{{$usuario->id}}' class="btn btn-primary">Editar</a></td>
            <td>  <button class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$usuario->id}}">Desativar</button> </td>
        </tr>




        <!-- Se Existir Alguma Publicacão para este tipo Documento -->

        <div class='modal fade' id="myModal{{$usuario->id}}" role='dialog'>
            <div class='modal-dialog row justify-content-center'>
                <div class="modal-content">
                        <div class="modal-header">
                            <Strong class=" offset-md-5" > ATENÇÃO </Strong>
                        </div>
                        <div class="modal-body">

                            <p> <b> Ao desativar um usuário ele não podera mais acessar o sistema. </b> </p>

                            <br><br>

                            <p><strong>Deseja realmente Desativar?</strong></p>

                            <div>
                                    <div style="float: left;" class="offset-md-3">
                                        <div>
                                            <a  style="color:white;" class="btn btn-danger"  href='{{ url("/usuario/desativar") }}/{{$usuario->id}}'>
                                                    {{ __('Confirmar Desativar') }}
                                            </a>
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




         @endforeach



        </tbody>

    </table>

    {{$usuarios->links()}}

                </div>

            </div>
        </div>
    </div>


@endguest



@endsection
