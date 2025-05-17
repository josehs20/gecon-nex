<div class="titulo-view text-center">
    <h3>PEDIDOS</h3>
</div>

<div class="row d-flex justify-content-center">

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Pedidos</h5>
                <small>Quantidade total de pedidos</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_pedidos'] }}</h5>
        </div>
    </div>

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Comprados</h5>
                <small>Quantidade de pedidos comprados</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_pedidos_comprados'] }}</h5>
        </div>
    </div>

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Cancelados</h5>
                <small>Quantidade de pedidos cancelados</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_pedidos_cancelados'] }}</h5>
        </div>
    </div>

</div>

<div class="row d-flex justify-content-center">



    <div class="col-md-2 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Em aberto</h5>
                <small>Quantidade de pedidos abertos</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_pedidos_em_aberto'] }}</h5>
        </div>
    </div>

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Aguardando cotação</h5>
                <small>Quantidade de pedidos aguardando cotação</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_pedidos_aguardando_cotacao'] }}</h5>
        </div>
    </div>

    <div class="col-md-2 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Em cotação</h5>
                <small>Quantidade de pedidos em cotação</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_pedidos_em_cotacao'] }}</h5>
        </div>
    </div>

    <div class="col-md-2 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Cotados</h5>
                <small>Quantidade de pedidos cotados</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_pedidos_cotados'] }}</h5>
        </div>
    </div>

</div>

<div class="row d-flex justify-content-center">
    <div class="col-md-7 col-12 card card-body mr-2">
        @include('mercado::dashboard.pedidos.grafico')
    </div>
    <div class="col-md-4 col-12 card card-body">
        @include('mercado::dashboard.pedidos.graficoPedidosCotacoes')
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 card card-body">
        <h5>Listagem de pedidos</h5>
        <table class="table table-bordered" id="view-tabela-listagem-pedidos" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Data limite</th>
                    <th>Qtd itens</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($view_renderizada['listagem_pedidos'] as $pedido)
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->usuario->master->name }}</td>
                        <td>
                            <span class="{{ $pedido->status->badge }}">
                                {{ $pedido->status->descricao }}
                            </span>
                        </td>
                        <td>{{ dataBancoDeDadosParaDataString($pedido->data_limite) }}</td>
                        <td>{{ count($pedido->pedido_itens) }}</td>
                        <td>{{ $pedido->observacao }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Data limite</th>
                    <th>Qtd itens</th>
                    <th>Observação</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        renderizarTabela();
    });

    function renderizarTabela() {
        montaDatatable('view-tabela-listagem-pedidos');
    }
</script>
