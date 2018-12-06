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

<div id="erro" class="container">
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

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">DIÁRIO OFICIAL CACHOEIRO DE ITAPEMIRIM</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p> Bem vindo </p>
                    <span>Hoje é <strong>{{date('d/m/Y')}}</strong></span>
                    <span style="margin-left:2%;">Horário Atual: <strong>{{date('H:i')}}</strong></span>


                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div id="texto" style="width:5cm; text-align:justify; text-justify: inter-word;">
    <p   style="font-size:2.95mm; font-family:Arial; white-space:pre-line;">RESULTADO DE LICITAÇÃO
        A Prefeitura Municipal de Cachoeiro de Itapemirim, por intermédio da CPL, torna público nos termos da lei, o resultado do julgamento da fase de proposta comercial da Tomada de Preços nº 010/2018, cujo objeto é a Contratação de empresa de engenharia para a construção de drenagem e pavimentação de trecho das ruas: das Gaivotas, do Juriti, das Araras, dos Coleiros, dos Tucanos e do Faisão, no Bairro Fé e Raça, no município de Cachoeiro de Itapemirim/ES. DECLARA VENCEDORA: Trilhos Construções Eireli ME, com base no parecer técnico, exarado pela equipe técnica da Secretaria Municipal de Obras. Lote único do certame da TP 010/2018, no valor global de R$ 404.451,87.
        Na forma disposta no artigo 109 da lei 8.666/93, fica aberto o prazo de 05 dias úteis, a partir desta publicação, para interposição de recursos.
    </p>
    </div>

<script>

    $(document).ready(function(){
        alert($("#texto").height()/37.8);
    });

</script> --}}

@endsection
