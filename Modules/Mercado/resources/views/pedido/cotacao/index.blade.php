@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['titulo' => 'Cotações']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Cotações</h3>
            <p class="lead">Nesta tela você pode ver as cotações criadas.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.cotacao.selecionar_pedidos') }}" class="btn btn-success">
                <i class="bi bi-hourglass-top"></i>
                Aguardando cotação
                <span class="badge badge-dark ml-1">{{ $aguardando_cotacao }}</span>
            </a>
        </div>

    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table id="tabela-cotacoes" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuário</th>
                        <th>Status</th>
                        <th>Data criação</th>
                        <th>Data limite</th>
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
                        <th>Data limite</th>
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
        var getCotacoes = @json(route('yajra.service.cotacao.get'));
        const columns = [
            ['id', 'ID'],
            ['usuario_id', 'Usuário'],
            ['status.descricao', 'Status'],
            ['data_abertura', 'Data criação'],
            ['data_encerramento', 'Data limite'],
            ['descricao', 'Descrição'],
            ['acao', 'Ação', false, false]
        ];

        montaDatatableYajra('tabela-cotacoes', montaColunasParaYajra(columns), getCotacoes);
    </script>
@endsection
