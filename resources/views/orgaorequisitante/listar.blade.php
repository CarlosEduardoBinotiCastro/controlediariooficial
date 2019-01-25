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
                @if (session()->has('usuarios'))
                    <div style="display: block;" class="form-group row mb-0 alert alert-danger" style="font-size:20px">

                        <p> Segue usuários cadastrados neste órgão: </p>

                        @foreach (session()->get('usuarios') as $item)
                            <span> <i>{{$item->name}}</i> <strong> / </strong> </span>
                        @endforeach

                    </div>
                @endif
            @endif
            </div>
</div>


<br/><br/>
<div class="container">
        <div class="row">


            <div class="col-md-12">
            <div class="row"> <h4> <strong> Lista de Órgãos Requisitantes </strong> </h4>  <a style="margin-left:auto" href='{{ url("/orgaorequisitante/cadastrar") }}' class="btn btn-success">Cadastrar</a> </div> <br>
            <div class="table-responsive">


                  <table id="mytable" class="table table-bordred table-striped">

                       <thead>
                       <th>Nome</th>
                       <th>Editar</th>
                       <th>Apagar</th>
                       </thead>
        <tbody>

         @foreach ($orgaosRequisitantes as $orgaoRequisitante)


         <tr>

            <td>{{$orgaoRequisitante->orgaoNome}}</td>
            <td> <a href='{{ url("/orgaorequisitante/editar") }}/{{$orgaoRequisitante->orgaoID}}' class="btn btn-primary">Editar</a></td>
            <td> <a href='{{ url("/orgaorequisitante/deletar") }}/{{$orgaoRequisitante->orgaoID}}' class="btn btn-danger">Deletar</a> </td>
        </tr>

         @endforeach



        </tbody>

    </table>

    {{$orgaosRequisitantes->links()}}

                </div>

            </div>
        </div>
    </div>


@endguest



@endsection
