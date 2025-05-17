@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['titulo' => 'Compras']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Compras</h3>
            <p class="lead">Nesta tela você pode ver as compras realizadas.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.compra.selecionar_cotacoes') }}" class="btn btn-success">
                <i class="bi bi-hourglass-top"></i>
                Aguardando compra
                <span class="badge badge-dark ml-1">{{ $cotacoes_cotadas }}</span>
            </a>
        </div>

    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table id="tabela-compras" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuário</th>
                        <th>Status</th>
                        <th>Data criação</th>
                        <th>Previsão entrega</th>
                        <th>Descrição</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>


                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Usuário</th>
                        <th>Status</th>
                        <th>Data criação</th>
                        <th>Previsão entrega</th>
                        <th>Descrição</th>
                        <th>Ação</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <script>
        var getCompras = @json(route('yajra.service.compra.get'));
        const columns = [
            ['id', 'ID'],
            ['usuario_id', 'Usuário'],
            ['status.descricao', 'Status'],
            ['cotacao.data_abertura', 'Data criação'],
            ['cot_fornecedor.previsao_entrega', 'Previsão entrega'],
            ['cot_fornecedor.observacao', 'Descrição'],
            ['acao', 'Ação', false, false]
        ];

        montaDatatableYajra('tabela-compras', montaColunasParaYajra(columns), getCompras);
    </script>
@endsection
