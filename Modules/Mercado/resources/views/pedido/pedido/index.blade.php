@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['titulo' => 'Pedidos']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Pedidos</h3>
            <p class="lead">Nesta tela você pode ver os pedidos realizados, ou realizar um novo.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.pedido.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i>Pedido
            </a>
        </div>
    </div>

    <div class="card card-body">

        <table id="tabela-pedidos" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>status</th>
                    <th>Data limite</th>
                    <th>Qtd Itens</th>
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
                    <th>status</th>
                    <th>Data limite</th>
                    <th>Qtd Itens</th>
                    <th>Observação</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
        </table>
        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <script>
        var getPedidos = @json(route('yajra.service.pedidos.get'));
        const columns = [
                    ['id', 'ID'],
                    ['usuario_id', 'Usuário'],
                    ['status.descricao', 'Status'],
                    ['data_limite', 'Data limite'],
                    ['qtd_itens', 'Qtd Itens'],
                    ['observacao', 'Observação'],
                    ['acao', 'Ação', false, false]
                ];

                montaDatatableYajra('tabela-pedidos', montaColunasParaYajra(columns), getPedidos);
    </script>
@endsection
