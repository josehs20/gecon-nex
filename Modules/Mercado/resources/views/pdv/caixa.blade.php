@extends('mercado::layouts.pdv')

@section('content')
<button id="finalizaVenda">Finalizar</button>
<button id="devolucaoVenda">Devolução</button>
<button id="orcamentoVenda">Orcamento</button>


<div id="dataView"
    data-rota-finalizar-venda="{{ route('caixa.finalizar.venda') }}"
    data-rota-devolucao-venda="{{ route('caixa.devolucao.venda') }}"
    data-rota-orcamento-venda="{{ route('caixa.orcamento.venda') }}"
></div>

@endsection
