@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Estoque']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Estoque</h3>
            <p class="lead">Nesta tela você pode visualizar, editar os parâmetros de cada estoque.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive mt-1">
                <table class="table table-hover" id="tabela-estoque" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produto</th>
                            <th>Fabricante</th>
                            <th>Qtd Total</th>
                            <th>Qtd Disponível</th>
                            <th>Qtd Mínima</th>
                            <th>Qtd Máxima</th>
                            <th>Localização</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Produto</th>
                            <th>Fabricante</th>
                            <th>Qtd Total</th>
                            <th>Qtd Disponível</th>
                            <th>Qtd Mínima</th>
                            <th>Qtd Máxima</th>
                            <th>Localização</th>
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
    </div>

    {{-- @include('mercado::estoque.estoque.modalEstoqueShow') --}}

    <script>
        var getEstoques = @json(route('yajra.service.estoques.get'));
        const columns = [{
                data: 'id',
                title: 'ID'
            }, // Coluna ID
            {
                data: 'produto@nome',
                title: 'Produto'
            },
            {
                data: 'produto@fabricante@nome',
                title: 'Fabricante'
            },
            {
                data: 'quantidade_total',
                title: 'Qtd Total'
            },
            {
                data: 'quantidade_disponivel',
                title: 'Qtd Disponível'
            },
            {
                data: 'quantidade_minima',
                title: 'Qtd Mínima'
            },
            {
                data: 'quantidade_maxima',
                title: 'Qtd Máxima'
            },
            {
                data: 'localizacao',
                title: 'Localização'
            },
            {
                data: 'acao',
                title: 'Ação',
                orderable: false, // Desabilita ordenação
                searchable: false // Desabilita pesquisa na coluna
            }, // Ação
        ];

        montaDatatableYajra('tabela-estoque', columns, getEstoques);

    </script>
@endsection
