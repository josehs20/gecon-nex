@extends('mercado::layouts.pdv')

@section('content')
    <button id="finalizaVenda">Finalizar</button>
    <button id="devolucaoVenda">Devolução</button>


    <div id="dataView" data-rota-finalizar-venda="{{ route('caixa.finalizar.venda') }}"
        data-rota-devolucao-venda="{{ route('caixa.devolucao.venda') }}" "></div>
@endsection
