<div class="titulo-view text-center">
    <h3>Cotações</h3>
</div>

<div class="row d-flex justify-content-center">
    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Cotações</h5>
                <small>Quantidade total de cotações</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_cotacoes'] }}</h5>
        </div>
    </div>
    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Comprados</h5>
                <small>Quantidade de cotações compradas</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_cotacoes_compradas'] }}</h5>
        </div>
    </div>

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Cancelados</h5>
                <small>Quantidade de cotações canceladas</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_cotacoes_canceladas'] }}</h5>
        </div>
    </div>
</div>
<div class="row d-flex justify-content-center">
    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Em aberto</h5>
                <small>Quantidade de cotações abertas</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_cotacoes_em_aberto'] }}</h5>
        </div>
    </div>

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Em cotação</h5>
                <small>Quantidade de cotações em cotação</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_cotacoes_em_cotacao'] }}</h5>
        </div>
    </div>

    <div class="col-md-3 col-12 card-dashboard p-3 mb-2 mr-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Cotados</h5>
                <small>Quantidade de cotações cotadas</small>
            </div>
            <h5 class="mb-0">{{ $view_renderizada['quantidade_cotacoes_cotados'] }}</h5>
        </div>
    </div>
</div>

<div class="row d-flex justify-content-center">
    <div class="col-md-7 col-12 card card-body mr-2">
        @include('mercado::dashboard.cotacoes.grafico')
    </div>
    <div class="col-md-4 col-12 card card-body">
        @include('mercado::dashboard.cotacoes.graficoCotacoesCompras')
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 card card-body">
        <h5>Listagem de cotações</h5>
        <table class="table table-bordered" id="view-tabela-listagem-cotacoes" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Data criação</th>
                    <th>Data limite</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($view_renderizada['listagem_cotacoes'] as $cotacao)
                    <tr>
                        <td>{{ $cotacao->id }}</td>
                        <td>{{ $cotacao->usuario->master->name }}</td>
                        <td>
                            <span class="{{$cotacao->status->badge}}">
                                {{ $cotacao->status->descricao }}
                            </span>
                        </td>
                        <td>{{ dataBancoDeDadosParaDataString($cotacao->data_abertura) ?? '' }}</td>
                        <td>{{ dataBancoDeDadosParaDataString($cotacao->data_encerramento) ?? '' }}</td>
                        <td>{{ $cotacao->descricao ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Data criação</th>
                    <th>Data limite</th>
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
        montaDatatable('view-tabela-listagem-cotacoes');
    }
</script>
