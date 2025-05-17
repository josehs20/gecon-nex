@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Balanço']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Balanço</h3>
            <p class="lead">Nesta tela você pode visualizar os balanços realizados.</p>
        </div>
        <div>
            <a href="{{ route('estoque.balanco.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i> balanço
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tabela-balanco" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuário</th>
                            <th>Status</th>
                            <th>Qtd itens</th>
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
                            <th>Qtd itens</th>
                            <th>Data de criação</th>
                            <th>Observação</th>
                            <th>Ação</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <script>
        var getBalancos = @json(route('yajra.service.estoque.balanco.get'));
        const columns = [
            ['id', 'ID'],
            ['usuario_id', 'Usuário'],
            ['status.descricao', 'Status'],
            ['qtd_itens', 'Qtd itens'],
            ['created_at', 'Data de criação'],
            ['observacao', 'Observação'],
            ['acao', 'Ação', false, false],
        ];

        montaDatatableYajra('tabela-balanco', montaColunasParaYajra(columns), getBalancos);
    </script>
@endsection
