@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Clientes']]])

@section('content')
@vite('Modules/Mercado/resources/assets/js/views/gerenciamento/cliente/index.js', 'build/.vite')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Clientes </h3>
            <p class="lead">Nesta tela você pode realizar ações no que diz respeito aos clientes.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.cliente.create') }}" class="btn btn-success"><i class="bi bi-plus"></i>Cliente </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive elevated">
            <table class="table table-bordered" id="tabela-clientes" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Documento</th>
                        <th>Status</th>
                        <th>Ativo</th>
                        <th>Celular</th>
                        <th>E-mail</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Documento</th>
                        <th>Status</th>
                        <th>Ativo</th>
                        <th>Celular</th>
                        <th>E-mail</th>
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

    </div>

    {{-- Modal para exibir os dados do cliente com uma melhor leitura --}}
    @include('mercado::gerenciamento.cliente.show')
    <div id="dataView"
    data-route-get-clientes="{{route('yajra.service.gerenciamento.clientes.get')}}"
    data-route-get-cliente="{{route('cadastro.cliente.get.cliente')}}"

    ></div>

@endsection
