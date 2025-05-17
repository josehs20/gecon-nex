@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['rota' => route('cadastro.pedido.index'), 'titulo' => 'Pedidos'], ['titulo' => 'Novo pedido']]])

@section('content')
    <style>
        .nav-link.active {
            background-color: #007bff !important;
            /* Cor primária do Bootstrap */
            color: #fff !important;
            /* Texto branco */
        }
    </style>
    <div class="cabecalho">
        <div class="page-header">
            <h3>Pedido</h3>
            <p class="lead">
                Nesta tela, você pode visualizar os detalhes do pedido.
            </p>

        </div>
    </div>

    <div class="card card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Informações do pedido</h5>
                <br>
            </div>
            <div>
                @if ($pedido->status->id === config('config.status.aberto'))
                    <a href="{{ route('estoque.recebimento.iniciar', ['pedido_id' => $pedido->id]) }}" class="btn btn-dark">
                        <i class="bi bi-box-arrow-in-down"></i>  Receber Pedido
                    </a>
                @else
                    <a href="{{ route('estoque.recebimento.iniciar', ['pedido_id' => $pedido->id]) }}" class="btn btn-dark">
                        <i class="bi bi-info-circle"></i>  Visualizar Recebimento
                    </a>
                @endif

            </div>
        </div>

        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="px-3 text-center">
                    <strong>Nº Pedido:</strong> {{ $pedido->id }}
                </div>
                <div class="px-3 text-center">
                    <strong>Realizado por:</strong> {{ $pedido->usuario->master->name }}
                </div>
                <div class="px-3 text-center">
                    <strong>Fornecedor:</strong> {{ $pedido->fornecedor->nome }}
                </div>
                <div class="px-3 text-center">
                    <strong>Loja:</strong> {{ $pedido->loja->nome }}
                </div>
                <div class="px-3 text-center">
                    <strong>Data solicitado:</strong>
                    {{ formatarData($pedido->data_pedido) }}
                </div>
                <div class="px-3 text-center">
                    <strong>Previsão de entrega:</strong>
                    {{ aplicarMascaraDataNascimento($pedido->previsao_entrega) }}
                </div>
                <div class="px-3 text-center">
                    <strong>Valor total:</strong>
                    {{ converterParaReais($pedido->pedido_itens->sum('total')) }}
                </div>
                <div class="px-3 text-center">
                    <strong>Status:</strong><span
                        class="badge
                        @if ($pedido->status->id === config('config.status.aberto')) badge-info @endif
                        @if ($pedido->status->id === config('config.status.concluido')) badge-success @endif">
                        {{ $pedido->status->descricao }}
                    </span>
                </div>
            </div>
        </div>

        <br>
        <h5 class="mt-5">Listagem de item</h5>
        <br>

        <table id="tabela-item-pedido" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th style="width: 30%;">Item</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>

                </tr>
            </thead>
            <tbody>
                @if ($pedido->pedido_itens && $pedido->pedido_itens->count())
                    @foreach ($pedido->pedido_itens as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->produto->nome ?? 'N/A' }}</td>
                            <td>R$ {{ converterParaReais($item->preco_unitario) }}</td>
                            <td>{{ number_format($item->quantidade_pedida, 3, ',', '.') }}</td>
                            <td>R$ {{ converterParaReais($item->total) }}</td>

                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="mt-4">
            <a type="button" href="{{ route('cadastro.pedido.index') }}" class="btn btn-outline-danger"> <i
                    class="bi bi-arrow-left"></i> Voltar</a>
        </div>
    </div>


    <script>
        localStorage.removeItem('itensRecebimentoPedido');
        montaDatatable('tabela-item-pedido');
    </script>
@endsection
