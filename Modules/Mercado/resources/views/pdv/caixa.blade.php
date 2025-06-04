@extends('mercado::layouts.pdv')

@section('content')
    <button id="finalizaVenda">Finalizar</button>
    <button id="devolucaoVenda">Devolução</button>
    <button id="orcamentoVenda">Orcamento</button>
    <button id="suprirCaixa">Suprir</button>
    <button id="sangriaCaixa">Sangria</button>
    <button id="receberConta">Receber</button>

    <div id="dataView" data-rota-finalizar-venda="{{ route('caixa.finalizar.venda') }}"
        data-rota-devolucao-venda="{{ route('caixa.devolucao.venda') }}"
        data-rota-orcamento-venda="{{ route('caixa.orcamento.venda') }}" data-rota-suprir-caixa="{{ route('caixa.suprir') }}"
        data-rota-sangria-caixa="{{ route('caixa.sangria.post') }}"
        data-rota-receber-conta="{{ route('caixa.receber.conta.post') }}"></div>
@endsection
