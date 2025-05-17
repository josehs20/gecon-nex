@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Movimentação']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Movimentações</h3>
            <p class="lead">Nesta tela você pode ver as movimentações de estoque.</p>
        </div>
        <div>
            <a href="{{ route('estoque.movimentacao.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i>Movimentação
            </a>
        </div>
    </div>

    <div class="card card-body">
        <div class="table-responsive mt-1">
            <table id="tabela-movimentacao" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuário</th>
                        <th>Status</th>
                        <th>Qtd Itens</th>
                        <th>Data de criação</th>
                        <th>Observação</th>
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
                        <th>Qtd Itens</th>
                        <th>Data de criação</th>
                        <th>Observação</th>
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
        var getMovimentacoes = @json(route('yajra.service.estoques.movimentacoes.get'));
        var columns = [
                ['id', 'ID'],
                ['usuario_id', 'Usuário'],
                ['status.descricao', 'Status'],
                ['qtd_itens', 'Qtd itens'],
                ['created_at', 'Data de criação'],
                ['observacao', 'Observação'],
                ['acao', 'Ação', false, false],
        ];

        montaDatatableYajra('tabela-movimentacao', montaColunasParaYajra(columns), getMovimentacoes);
    </script>
@endsection
