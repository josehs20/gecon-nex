@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('pedido.recebimento.index'), 'titulo' => 'Recebimentos'], ['rota' => route('pedido.recebimento.create'), 'titulo' => 'Novo recebimento'], ['titulo' => 'Receber']]])

@section('content')
    @vite('Modules/Mercado/resources/assets/js/views/pedido/recebimento/receber.js', 'build/.vite')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Receber</h3>
            <p class="lead">Nesta tela você pode ver os detalhes da compra a ser recebida e finalizar o recebimento.</p>
        </div>
    </div>

    <div class="card card-body">
        <h5><span class="badge badge-dark">Compra {{ $compra->id }}</span></h5>
        <div class="row">
            <div class="col-12 col-md-4">
                <span><strong>Usuário: </strong> {{ $compra->usuario->master->name }}</span>
            </div>
            <div class="col-12 col-md-4">
                <span><strong>Loja: </strong> {{ $compra->loja->nome }}</span>
            </div>
            <div class="col-12 col-md-4">
                <span><strong>Espécie de pagamento: </strong> {{ $compra->especie_pagamento->nome }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <span><strong>Fornecedor: </strong> {{ $compra->cot_fornecedor->fornecedor->nome }}</span>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <h5>Materiais da compra</h5>
        <table class="table table-stripped">
            <thead>
                <th>Material</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Preço unitário</th>
                <th>Total</th>
            </thead>
            <tbody>
                @foreach ($compra->compra_itens as $compra_item)
                    <tr>
                        <td>{{ $compra_item->cotacaoFornecedorItem->produto->id }}</td>
                        <td>{{ $compra_item->cotacaoFornecedorItem->produto->nome }}</td>
                        <td>{{ $compra_item->cotacaoFornecedorItem->quantidade }}</td>
                        <td>{{ converterParaReais($compra_item->cotacaoFornecedorItem->preco_unitario) }}</td>
                        <td>{{ converterParaReais($compra_item->cotacaoFornecedorItem->preco_unitario * $compra_item->cotacaoFornecedorItem->quantidade) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <form action="{{ route('pedido.recebimento.store', ['compra_id' => $compra->id]) }}" method="post">
        @csrf
        <div class="card card-body">

            <div class="row">
                <div class="col-md-4 col-12">
                    <label for="data_recebimento" class="form-label">Data do recebimento*</label>
                    <input 
                        required 
                        type="date" 
                        name="data_recebimento" 
                        id="data_recebimento" 
                        class="form-control"
                        value="{{ $dataLimite }}" 
                        min="{{ $minDate }}" 
                        {{ $disabled }}>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <label for="observacao">Observações *</label>
                    <textarea 
                        required 
                        class="form-control" 
                        name="observacao" 
                        id="observacao" 
                        rows="3"
                        {{ $compra_recebida ? 'disabled' : '' }}
                        placeholder="Informações adicionais sobre o recebimento"
                    >{{ $compra_recebida ? $recebimento->observacoes : '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white">
            <a href="{{ route('pedido.recebimento.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            @if (!$compra_recebida)                
                <button id="btn-receber" type="submit"
                    class="btn btn-success">
                    <i class="bi bi-check"></i> Receber
                </button>
            @endif
        </div>
    </form>

@endsection
