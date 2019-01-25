@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Relatório De Faturas Em Um Período</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status')}}
                        </div>
                    @endif

                    <div>
                        <form action="{{ url("/fatura/relatorioFiltro") }}" method="POST">
                            @csrf
                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Inicial</label>

                            {{-- Logica para pegar o primeiro dia do mês --}}
                            @if (date('d') > 1)
                                <input class="form-control col-md-6" type="date" name="dataInicio" value="{{  date('Y-m-d', strtotime("-".date('d', strtotime("-1 day"))." day"))}}" required>
                            @else
                                <input class="form-control col-md-6" type="date" name="dataInicio" value="{{ date('Y-m-d') }}" required>
                            @endif

                            </div>

                            <div class="form-group row offset-md-2">
                                <label class="col-md-3 text-md-right">Data Final</label>
                                <input class="form-control col-md-6" type="date" name="dataFinal" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group row offset-md-2 ">
                                <label class="col-md-3 text-md-right">Situação</label>
                                <select class="custom-select col-md-6" name="situacao" >
                                        <option slected value="tudo">Todos</option>
                                        <option  value="Aceita-Publicada">Paga e Publicada</option>
                                    @foreach ($situacoes as $situacao)
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
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row offset-md-4">
                                    <input class=" btn btn-primary col-md-5" type="submit" name="enviar">
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<br/><br/>

@if (isset($subcategorias))


<div class="container">
        <div class="row">


            <div class="col-md-8 offset-md-2">
            <div class="table-responsive">
                <div class="card">
                        <div class="card-header"><strong>RESULTADO</strong></div>
                        <table id="mytable" class="table table-bordred table-striped">

                            <thead>
                                <th>Subcategoria</th>
                                <th>Quantidade de Faturas</th>
                                <th>Valor Das Faturas</th>

                            </thead>

                            <tbody>

                                @foreach ($subcategorias as $sub)

                                <tr>
                                    <td> @if($sub->subcategoriaNome != null) {{$sub->subcategoriaNome}} @else Não Possui @endif </td>
                                    <td>{{$sub->quantidade}}</td>
                                    <td>{{$sub->total}}</td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>

                        <div class="offset-md-8">
                        <p><strong> Quantidade De Faturas:  {{$faturas}}</strong></p>
                        <p><strong> Valor Total Das Faturas: R$ {{$valorTotal->total}}</strong></p>
                        </div>
                </div>
                {{-- <br>
                @if(isset($quantitativoSetor))
                <div class="col-md-4 offset-md-8">
                <a class="btn btn-primary" href="/gerarPdfQuantitativo" target="_blank"> Gerar PDF</a>
                </div>
                @endif --}}
                </div>

            </div>
        </div>
    </div>
@endif

@endsection
