@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['titulo' => 'Formas de pagamento']]])

@section('content')
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="cabecalho">
                <div class="page-header">
                    <h3>Listagem de formas de pagamento</h3>
                    <p class="lead">Nesta tela você pode visualizar as formas de pagamento da loja:
                        <u><b>{{ auth()->user()->getUserModulo->loja->nome ?? '' }}</b></u> .
                    </p>
                </div>
                {{-- <div>
                    <a href="{{ route('cadastro.forma_pagamento.create') }}" class="btn btn-dark">Forma de pagamento
                        <i class="bi bi-plus"></i>
                    </a>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tabela-forma-pagamento" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Loja</th>
                            <th>Ativo</th>
                            {{-- <th>Ação</th> --}}
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Loja</th>
                            <th>Ativo</th>
                            {{-- <th>Ação</th> --}}
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

    <script>
        const columns = [
            ['id', '#'],
            ['descricao', 'Nome'],
            ['loja.nome', 'Loja'],
            ['ativo', 'Ativo'],

        ];

        var routeGetFormasPagamento = @json(route('yajra.service.gerenciamento.forma_pagamento.get'));
        montaDatatableYajra('tabela-forma-pagamento', montaColunasParaYajra(columns), routeGetFormasPagamento);
    </script>
@endsection
