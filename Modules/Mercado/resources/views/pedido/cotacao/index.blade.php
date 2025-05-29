@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['titulo' => 'Cotações']]])

@section('content')
    @vite('Modules/Mercado/resources/assets/js/views/pedido/cotacao/index.js', 'build/.vite')

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

    <div id="dataView"
    data-get-cotacoes="{{route('yajra.service.cotacao.get')}}"
    ></div>

@endsection
