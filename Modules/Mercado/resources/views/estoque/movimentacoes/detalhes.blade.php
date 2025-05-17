@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('estoque.movimentacao.index'), 'titulo' => 'Movimentações'], ['titulo' => 'Detalhes da movimentação']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Detalhes da movimentação</h3>
            <p class="lead">
                Nesta tela você pode ver os detalhes da movimentações de estoque.
            </p>
        </div>
    </div>

    <div class="card card-body">
        <div class="row">
            <div class="col-md-10">
                <h5 style="color: black !important;">Produtos movimentados</h5>
            </div>
        </div>
        <br>
        <table id="tabela-movimentacao-item" class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>Fabricante</th>
                    <th>Qtd disponível</th>
                    <th>Qtd movimentar</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>Fabricante</th>
                    <th>Qtd disponível</th>
                    <th>Qtd movimentar</th>
                    <th>Tipo</th>
                </tr>
            </tfoot>
        </table>

        <div class="card-footer">
            <a href="{{ route('estoque.movimentacao.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>

        </div>
    </div>
    <br>
    <script>
        var urlGetProdutos = @json(route('estoque.movimentacao.getProdutos'));
        var urlGetEstoque = @json(route('estoque.movimentacao.getEstoque'));
        var getMovimentacaoItens = @json(route('yajra.service.estoque.movimentacao.itens.get'));
        var routeAddItem = @json(route('estoque.movimentacao.movimentar'));
        var routeRemoveItem = @json(route('estoque.movimentacao.delete'));
        const columns = [{
                data: 'id',
                title: 'ID'
            }, // Coluna ID
            {
                data: 'estoque@produto@nome',
                title: 'Produto'
            },
            {
                data: 'estoque@produto@fabricante@nome',
                title: 'Fabricante'
            },
            {
                data: 'estoque@quantidade_disponivel',
                title: 'Qtd disponível'
            },
            {
                data: 'quantidade_movimentada',
                title: 'Qtd movimentar'
            },
            {
                data: 'tipo_movimentacao@descricao',
                title: 'Tipo'
            }

        ];
        const dataAjax = {
            movimentacao_id: @json($movimentacao->id),
        }
        montaDatatableYajra('tabela-movimentacao-item', columns, getMovimentacaoItens, dataAjax);

    </script>
@endsection
