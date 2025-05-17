@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('estoque.balanco.index'), 'titulo' => 'Balanços'], ['titulo' => 'Detalhes do balanço']]])


@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Balanço</h3>
            <p class="lead">
                Nesta tela você pode ver os detalhes da operação do balanço</strong>.
            </p>
        </div>
    </div>

    <div class="card card-body">
        <div class="row">
            <div class="col-md-10">
                <h5 style="color: black !important;">Produtos movimentados</h5>
            </div>
            <div class="col-md-2">
                <div class="px-3 text-center">
                    <span class="{{ $balanco->status->badge() }}">
                        STATUS: {{ $balanco->status->descricao() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="row justify-content-start">
            <div class="col-auto">
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        • <strong>Movimentado por:</strong> {{ $balanco->usuario->master->name ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Loja:</strong> {{ $balanco->loja->nome ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Data de início:</strong> {{ formatarData($balanco->created_at ?? '-') }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Data de finalização:</strong> {{ formatarData($balanco->updated_at ?? '-') }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Observação:</strong> {{ $balanco->observacao }}
                    </li>
                </ul>
            </div>
        </div>
        <br>
        <table id="tabela-balanco-item" class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>Fabricante</th>
                    <th>Qtd no sistema</th>
                    <th>Qtd real</th>
                    <th>Resultado operacional</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>Fabricante</th>
                    <th>Qtd no sistema</th>
                    <th>Qtd real</th>
                    <th>Resultado operacional</th>
                </tr>
            </tfoot>
        </table>

        <div class="card-footer">
            <a href="{{ route('estoque.balanco.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>


    <script>
        var getBalancoItens = @json(route('yajra.service.estoque.balanco.itens.get'));
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
                data: 'quantidade_estoque_sistema',
                title: 'Qtd no sistema'
            },
            {
                data: 'quantidade_estoque_real',
                title: 'Qtd real'
            },
            {
                data: 'quantidade_resultado_operacional',
                title: 'Resultado operacional'
            }
        ];

        const dataAjax = {
            balanco_id: @json($balanco->id),
        }

        montaDatatableYajra('tabela-balanco-item', columns, getBalancoItens, dataAjax);
    </script>
@endsection
