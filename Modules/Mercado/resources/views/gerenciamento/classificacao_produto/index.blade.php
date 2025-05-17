@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Classificações de produto']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Classificação de produtos</h3>
            <p class="lead">Nesta tela você pode vidualizar, cadastrar e editar as classificações do produto.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.classificacao_produto.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i>Classificação de produto
            </a>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive mt-1">
                <table class="table table-hover" id="tabela-classificacoes" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
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

    <br>
    <script>
        const columns = [
            ['id', '#'],
            ['descricao', 'Nome'],
            ['acao', 'Ação', false, false]
        ];
        var routeGetClassificacaoProduto = @json(route('yajra.service.gerenciamento.classificao_produto.get'));
        montaDatatableYajra('tabela-classificacoes', montaColunasParaYajra(columns), routeGetClassificacaoProduto);
    </script>
@endsection
