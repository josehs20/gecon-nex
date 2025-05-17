<div class="titulo-view text-center">
    <h3>COMPRAS</h3>
</div>
<div class="row d-flex justify-content-center">

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Compras</h5>
                <small>Quantidade total de compras</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_compras'] }}</h5>
        </div>
    </div>

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Efetivadas</h5>
                <small>Quantidade de compras efetivadas</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_compras_compradas'] }}</h5>
        </div>
    </div>

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Canceladas</h5>
                <small>Quantidade de compras canceladas</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_compras_canceladas'] }}</h5>
        </div>
    </div>

</div>

<div class="row d-flex justify-content-center">
    <div class="col-md-7 col-12 card card-body mr-2">
        @include('mercado::dashboard.compras.graficoComprasEmReais')
    </div>
    <div class="col-md-4 col-12 card card-body mr-2">
        @include('mercado::dashboard.compras.grafico')
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 card card-body">
        <h5>Listagem de compras</h5>
        <table class="table table-bordered" id="view-tabela-listagem-compras" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Data de criação</th>
                    <th>Previsão de entrega</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($view_renderizada['listagem_compras'] as $compra) 
                    <tr>
                        <td>{{ $compra->id }}</td>
                        <td>{{ $compra->usuario->master->name }}</td>
                        <td>
                            <span class="{{ $compra->status->badge }}">
                                {{ $compra->status->descricao }}
                            </span>
                        </td>
                        <td>{{ dataBancoDeDadosParaDataString($compra->created_at) }}</td>
                        <td>{{ dataBancoDeDadosParaDataString($compra->cot_fornecedor->previsao_entrega) }}</td>
                        <td>{{ $compra->cot_fornecedor->observacao }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Data de criação</th>
                    <th>Previsão de entrega</th>
                    <th>Descrição</th>
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
        montaDatatable('view-tabela-listagem-compras');
    }
</script>
